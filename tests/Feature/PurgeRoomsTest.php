<?php

namespace Tests\Feature;

use App\Models\RobcoRoom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurgeRoomsTest extends TestCase
{
    use RefreshDatabase;

    public function test_purge_stale_rooms_removes_old_waiting_rooms(): void
    {
        $oldRoom = RobcoRoom::create([
            'code'             => 'OLD1',
            'secret_word'      => 'SECRET',
            'word_length'      => 6,
            'grid_data'        => [],
            'status'           => 'waiting',
            'player1_name'     => 'OldHost',
            'player1_token'    => 'token_old',
            'player1_attempts' => 4,
            'player2_attempts' => 4,
        ]);
        $oldRoom->updated_at = now()->subMinutes(30);
        $oldRoom->save();

        $freshRoom = RobcoRoom::create([
            'code'             => 'NEW1',
            'secret_word'      => 'SECRET',
            'word_length'      => 6,
            'grid_data'        => [],
            'status'           => 'waiting',
            'player1_name'     => 'FreshHost',
            'player1_token'    => 'token_fresh',
            'player1_attempts' => 4,
            'player2_attempts' => 4,
        ]);

        $this->artisan('robco:purge-rooms --minutes=20')
            ->assertSuccessful();

        $this->assertDatabaseMissing('robco_rooms', ['code' => 'OLD1']);
        $this->assertDatabaseHas('robco_rooms', ['code' => 'NEW1']);
    }

    public function test_purge_endpoint_from_lobby(): void
    {
        RobcoRoom::create([
            'code'             => 'PURG1',
            'secret_word'      => 'SECRET',
            'word_length'      => 6,
            'grid_data'        => [],
            'status'           => 'waiting',
            'player1_name'     => 'Abandon',
            'player1_token'    => 'token_abandon',
            'player1_attempts' => 4,
            'player2_attempts' => 4,
        ]);

        $response = $this->postJson(route('terminal.rooms.purge'), ['all' => true]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('robco_rooms', ['code' => 'PURG1']);
    }
}
