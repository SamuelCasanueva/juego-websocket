<?php

namespace Tests\Feature;

use App\Models\RobcoRoom;
use App\Services\RobcoTerminalGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RobcoTerminalTest extends TestCase
{
    use RefreshDatabase;

    public function test_terminal_lobby_loads_successfully(): void
    {
        $response = $this->get('/terminal');
        $response->assertStatus(200);
        $response->assertSee('ROBCO INDUSTRIES');
    }

    public function test_can_create_a_terminal_room(): void
    {
        $response = $this->post('/terminal/crear', [
            'player_name' => 'Supervisor 101',
        ]);

        $response->assertStatus(302);

        $room = RobcoRoom::first();
        $this->assertNotNull($room);
        $this->assertEquals('Supervisor 101', $room->player1_name);
        $this->assertEquals(4, $room->player1_attempts);
        $this->assertEquals('waiting', $room->status);
        $this->assertNotEmpty($room->secret_word);
    }

    public function test_player_can_join_existing_room(): void
    {
        $gameData = RobcoTerminalGenerator::generateGame();
        $room = RobcoRoom::create([
            'code' => 'V-TEST',
            'secret_word' => $gameData['secret_word'],
            'word_length' => $gameData['word_length'],
            'grid_data' => $gameData,
            'status' => 'waiting',
            'player1_name' => 'Host 101',
            'player1_token' => 'token-host',
            'player1_attempts' => 4,
            'player2_attempts' => 4,
        ]);

        // Simular segundo jugador con diferente sesión
        $response = $this->withSession(['robco_player_token' => 'token-rival'])
            ->post("/terminal/unirse/{$room->code}", [
                'player_name' => 'Rival Hacker',
            ]);

        $response->assertStatus(302);

        $room->refresh();
        $this->assertEquals('playing', $room->status);
        $this->assertEquals('Rival Hacker', $room->player2_name);
        $this->assertEquals('token-rival', $room->player2_token);
    }

    public function test_word_guess_calculates_likeness_and_decrements_attempts(): void
    {
        $gameData = RobcoTerminalGenerator::generateGame();
        $room = RobcoRoom::create([
            'code' => 'V-TEST2',
            'secret_word' => 'BOVEDA',
            'word_length' => 6,
            'grid_data' => $gameData,
            'status' => 'playing',
            'player1_name' => 'Host',
            'player1_token' => 'token-p1',
            'player1_attempts' => 4,
            'player2_attempts' => 4,
        ]);

        // Probar palabra incorrecta
        $response = $this->withSession(['robco_player_token' => 'token-p1'])
            ->postJson("/terminal/sala/{$room->code}/probar", [
                'word' => 'BOMBEO',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_correct' => false,
            'attempts_left' => 3,
        ]);

        $room->refresh();
        $this->assertEquals(3, $room->player1_attempts);
    }

    public function test_correct_word_guess_wins_game(): void
    {
        $gameData = RobcoTerminalGenerator::generateGame();
        $room = RobcoRoom::create([
            'code' => 'V-TEST3',
            'secret_word' => 'BOVEDA',
            'word_length' => 6,
            'grid_data' => $gameData,
            'status' => 'playing',
            'player1_name' => 'Host',
            'player1_token' => 'token-p1',
            'player1_attempts' => 4,
            'player2_attempts' => 4,
        ]);

        // Probar palabra correcta
        $response = $this->withSession(['robco_player_token' => 'token-p1'])
            ->postJson("/terminal/sala/{$room->code}/probar", [
                'word' => 'BOVEDA',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_correct' => true,
        ]);

        $room->refresh();
        $this->assertEquals('finished', $room->status);
        $this->assertEquals('token-p1', $room->winner_token);
    }

    public function test_creating_room_broadcasts_terminal_room_created_event(): void
    {
        \Illuminate\Support\Facades\Event::fake([
            \App\Events\TerminalRoomCreated::class
        ]);

        $this->post('/terminal/crear', [
            'player_name' => 'Supervisor Event Test',
        ]);

        \Illuminate\Support\Facades\Event::assertDispatched(\App\Events\TerminalRoomCreated::class);
    }

    public function test_double_lockout_marks_room_as_finished(): void
    {
        $gameData = RobcoTerminalGenerator::generateGame();
        $room = RobcoRoom::create([
            'code' => 'V-LOCK',
            'secret_word' => 'BOVEDA',
            'word_length' => 6,
            'grid_data' => $gameData,
            'status' => 'playing',
            'player1_name' => 'P1',
            'player1_token' => 'tok-p1',
            'player1_attempts' => 1,
            'player2_attempts' => 0,
        ]);

        $response = $this->withSession(['robco_player_token' => 'tok-p1'])
            ->postJson("/terminal/sala/{$room->code}/probar", [
                'word' => 'ATOMOS',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'both_locked' => true,
            'secret_word' => 'BOVEDA',
        ]);

        $room->refresh();
        $this->assertEquals('finished', $room->status);
    }

    public function test_rematch_resets_room_to_playing_with_fresh_attempts(): void
    {
        $gameData = RobcoTerminalGenerator::generateGame();
        $room = RobcoRoom::create([
            'code' => 'V-REMATCH',
            'secret_word' => 'BOVEDA',
            'word_length' => 6,
            'grid_data' => $gameData,
            'status' => 'finished',
            'player1_name' => 'P1',
            'player1_token' => 'tok-p1',
            'player1_attempts' => 0,
            'player2_attempts' => 0,
        ]);

        $response = $this->withSession(['robco_player_token' => 'tok-p1'])
            ->postJson("/terminal/sala/{$room->code}/revancha");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $room->refresh();
        $this->assertEquals('playing', $room->status);
        $this->assertEquals(4, $room->player1_attempts);
        $this->assertEquals(4, $room->player2_attempts);
    }

    public function test_spectator_screen_loads_successfully(): void
    {
        $gameData = RobcoTerminalGenerator::generateGame();
        $room = RobcoRoom::create([
            'code' => 'V-SPEC',
            'secret_word' => 'BOVEDA',
            'word_length' => 6,
            'grid_data' => $gameData,
            'status' => 'playing',
            'player1_name' => 'Host 101',
            'player1_token' => 'tok1',
            'player2_name' => 'Rival 13',
            'player2_token' => 'tok2',
        ]);

        $response = $this->get("/terminal/sala/{$room->code}/espectador");
        $response->assertStatus(200);
        $response->assertSee('MODO SUPERVISOR');
        $response->assertSee('HOST 101');
    }

    public function test_operator_id_endpoint_saves_to_session(): void
    {
        $response = $this->postJson('/terminal/identificador', [
            'player_name' => 'COURIER-6',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'ok',
            'operator' => 'COURIER-6',
        ]);
        $response->assertSessionHas('robco_operator_name', 'COURIER-6');
    }

    public function test_joining_room_uses_custom_operator_identifier(): void
    {
        $gameData = RobcoTerminalGenerator::generateGame();
        $room = RobcoRoom::create([
            'code' => 'V-OPID',
            'secret_word' => $gameData['secret_word'],
            'word_length' => $gameData['word_length'],
            'grid_data' => $gameData,
            'status' => 'waiting',
            'player1_name' => 'Host 101',
            'player1_token' => 'tok-host',
            'player1_attempts' => 4,
            'player2_attempts' => 4,
        ]);

        $response = $this->withSession(['robco_player_token' => 'tok-op', 'robco_operator_name' => 'PALADIN-X'])
            ->get("/terminal/sala/{$room->code}");

        $response->assertStatus(200);
        $room->refresh();
        $this->assertEquals('PALADIN-X', $room->player2_name);
        $this->assertEquals('tok-op', $room->player2_token);
        $this->assertEquals('playing', $room->status);
    }
}


