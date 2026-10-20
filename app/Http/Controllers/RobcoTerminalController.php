<?php

namespace App\Http\Controllers;

use App\Events\LobbyChatMessage;
use App\Events\RoomChatMessage;
use App\Events\TerminalAttemptMade;
use App\Events\TerminalBracketUsed;
use App\Events\TerminalHacked;
use App\Events\TerminalInterference;
use App\Events\TerminalPlayerJoined;
use App\Events\TerminalRematch;
use App\Events\TerminalRoomCreated;
use App\Events\TerminalRoomUpdated;
use App\Events\TerminalRoomsPurged;
use App\Models\ChatMessage;
use App\Models\RobcoRoom;
use App\Services\RobcoTerminalGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RobcoTerminalController extends Controller
{
    // ─── Modos de juego válidos ───────────────────────────────────────────
    protected const VALID_MODES    = ['competitive', 'cooperative', 'timed'];
    protected const VALID_ATTEMPTS = [2, 3, 4, 6];
    protected const VALID_LIMITS   = [30, 60, 90];
    protected const VALID_PACKS    = ['yermo', 'robco', 'vault', 'vegas'];
    protected const VALID_PERKS    = ['science', 'luck', 'perception', 'injection'];

    /**
     * Obtiene o genera el token de identidad único del jugador en la sesión.
     */
    protected function getPlayerToken(): string
    {
        if (!session()->has('robco_player_token')) {
            session()->put('robco_player_token', Str::random(32));
        }
        return session()->get('robco_player_token');
    }

    /**
     * Normaliza y valida el array de settings recibido del request.
     */
    protected function parseSettings(Request $request): array
    {
        $mode     = $request->input('mode', 'competitive');
        $attempts = (int) $request->input('attempts', 4);
        $limit    = (int) $request->input('time_limit', 0);
        $pack     = $request->input('word_pack', 'yermo');
        $perk     = $request->input('perk', 'science');

        return [
            'mode'          => in_array($mode, self::VALID_MODES) ? $mode : 'competitive',
            'attempts'      => in_array($attempts, self::VALID_ATTEMPTS) ? $attempts : 4,
            'time_limit'    => in_array($limit, self::VALID_LIMITS) ? $limit : null,
            'word_pack'     => in_array($pack, self::VALID_PACKS) ? $pack : 'yermo',
            'show_likeness' => $request->input('show_likeness', '1') !== '0',
            'perk'          => in_array($perk, self::VALID_PERKS) ? $perk : 'science',
        ];
    }

    /**
     * Menú principal de RobCo Industries (Lobby).
     */
    public function index()
    {
        // Auto-mantenimiento de la subred: purga salas en espera > 20m, en juego > 45m, finalizadas > 120m
        try {
            RobcoRoom::purgeStale(20, 45, 120);
        } catch (\Throwable $e) {
            // Silencioso ante tablas no migradas o pruebas aisladas
        }

        $token = $this->getPlayerToken();
        $recentRooms = RobcoRoom::where('status', '!=', 'finished')
            ->latest()
            ->take(6)
            ->get();

        $leaderboard = RobcoRoom::whereNotNull('winner_name')
            ->where('winner_token', '!=', 'none')
            ->latest()
            ->take(5)
            ->get();

        $globalChat = ChatMessage::recentGlobal(35);

        return view('terminal.lobby', [
            'recentRooms'  => $recentRooms,
            'leaderboard'  => $leaderboard,
            'token'        => $token,
            'operatorName' => session()->get('robco_operator_name', ''),
            'globalChat'   => $globalChat,
        ]);
    }

    /**
     * Registra el identificador de operador en la sesión activa.
     */
    public function setOperatorId(Request $request)
    {
        $request->validate([
            'player_name' => 'required|string|max:24',
        ]);

        $name = trim($request->input('player_name'));
        session()->put('robco_operator_name', $name);

        return response()->json([
            'status'   => 'ok',
            'operator' => $name,
        ]);
    }

    /**
     * Crea una nueva sala de terminal con configuración personalizada.
     */
    public function createRoom(Request $request)
    {
        $request->validate([
            'player_name'   => 'nullable|string|max:24',
            'mode'          => 'nullable|string',
            'attempts'      => 'nullable|integer',
            'time_limit'    => 'nullable|integer',
            'word_pack'     => 'nullable|string',
            'show_likeness' => 'nullable|string',
            'perk'          => 'nullable|string',
        ]);

        $token    = $this->getPlayerToken();
        $code     = 'V-' . strtoupper(Str::random(4));
        $settings = $this->parseSettings($request);
        $settings['player1_perk'] = $settings['perk'];

        $gameData = RobcoTerminalGenerator::generateGame(12, 16, 12, $settings['word_pack']);

        $chosenName = trim($request->input('player_name') ?: session()->get('robco_operator_name', ''));
        if ($chosenName) {
            session()->put('robco_operator_name', $chosenName);
        } else {
            $chosenName = 'Supervisor 101';
        }

        $room = RobcoRoom::create([
            'code'              => $code,
            'secret_word'       => $gameData['secret_word'],
            'word_length'       => $gameData['word_length'],
            'grid_data'         => $gameData,
            'status'            => 'waiting',
            'settings'          => $settings,
            'player1_name'      => $chosenName,
            'player1_token'     => $token,
            'player1_attempts'  => $settings['attempts'],
            'player2_attempts'  => $settings['attempts'],
        ]);

        broadcast(new TerminalRoomCreated($room->code, $room->player1_name, $room->status));

        return redirect()->route('terminal.room', ['code' => $room->code]);
    }

    /**
     * Unirse a una sala existente como Jugador 2.
     */
    public function joinRoom(Request $request, string $code)
    {
        $request->validate([
            'player_name' => 'nullable|string|max:24',
            'perk'        => 'nullable|string',
        ]);

        $token = $this->getPlayerToken();
        $room  = RobcoRoom::where('code', strtoupper($code))->firstOrFail();

        if ($room->isPlayer1($token)) {
            return redirect()->route('terminal.room', ['code' => $room->code]);
        }

        $chosenName = trim($request->input('player_name') ?: $request->query('name') ?: session()->get('robco_operator_name', ''));
        if ($chosenName) {
            session()->put('robco_operator_name', $chosenName);
        } else {
            $chosenName = 'Infiltrado del Yermo';
        }

        if (empty($room->player2_token) || $room->isPlayer2($token)) {
            $room->player2_token = $token;
            $room->player2_name  = $chosenName;
            $room->status        = 'playing';

            if ($request->has('perk')) {
                $p2Perk = $request->input('perk');
                if (in_array($p2Perk, self::VALID_PERKS)) {
                    $s = $room->settings ?? [];
                    $s['player2_perk'] = $p2Perk;
                    $room->settings = $s;
                }
            }

            $room->save();

            broadcast(new TerminalPlayerJoined($room->code, $room->player2_name));
            broadcast(new TerminalRoomUpdated($room->code, 'playing', $room->player2_name));
        }

        return redirect()->route('terminal.room', ['code' => $room->code]);
    }

    /**
     * Pantalla de la terminal de hackeo.
     */
    public function showRoom(Request $request, string $code)
    {
        $token = $this->getPlayerToken();
        $room  = RobcoRoom::where('code', strtoupper($code))->firstOrFail();

        $playerNumber = 0;
        $playerName   = 'Espectador';

        if ($room->isPlayer1($token)) {
            $playerNumber = 1;
            $playerName   = $room->player1_name;
        } elseif ($room->isPlayer2($token)) {
            $playerNumber = 2;
            $playerName   = $room->player2_name;
        } elseif (empty($room->player2_token)) {
            $chosenName = trim($request->query('name') ?: session()->get('robco_operator_name', ''));
            if ($chosenName) {
                session()->put('robco_operator_name', $chosenName);
            } else {
                $chosenName = 'Infiltrado del Yermo';
            }

            $playerNumber        = 2;
            $room->player2_token = $token;
            $room->player2_name  = $chosenName;
            $room->status        = 'playing';

            if ($request->has('perk')) {
                $p2Perk = $request->input('perk');
                if (in_array($p2Perk, self::VALID_PERKS)) {
                    $s = $room->settings ?? [];
                    $s['player2_perk'] = $p2Perk;
                    $room->settings = $s;
                }
            }

            $room->save();

            broadcast(new TerminalPlayerJoined($room->code, $room->player2_name));
            broadcast(new TerminalRoomUpdated($room->code, 'playing', $room->player2_name));
            $playerName = $room->player2_name;
        } else {
            return redirect()->route('terminal.spectate', ['code' => $room->code]);
        }

        $roomChat = ChatMessage::recentForRoom($room->code, 30);
        $settings = $room->settings ?? [];
        $activePerk = ($playerNumber === 2 && !empty($settings['player2_perk']))
            ? $settings['player2_perk']
            : ($settings['player1_perk'] ?? $settings['perk'] ?? 'science');

        return view('terminal.game', [
            'room'         => $room,
            'playerNumber' => $playerNumber,
            'playerName'   => $playerName,
            'activePerk'   => $activePerk,
            'token'        => $token,
            'roomChat'     => $roomChat,
        ]);
    }

    /**
     * Procesa el intento de adivinar una palabra (AJAX / fetch).
     */
    public function guessWord(Request $request, string $code)
    {
        $request->validate([
            'word' => 'required|string',
        ]);

        $token = $this->getPlayerToken();
        $room  = RobcoRoom::where('code', strtoupper($code))->firstOrFail();

        if ($room->status === 'finished') {
            return response()->json([
                'success' => false,
                'message' => 'La terminal ya ha sido resuelta.',
            ]);
        }

        $settings     = $room->settings ?? [];
        $mode         = $settings['mode'] ?? 'competitive';
        $showLikeness = $settings['show_likeness'] ?? true;

        // ── Determinar jugador y decrementar intentos según modo ─────────
        $playerNumber = 0;
        $playerName   = '';
        $attemptsLeft = 0;

        if ($mode === 'cooperative') {
            // Pool compartido: ambos jugadores consumen de player1_attempts
            if (!$room->isParticipant($token)) {
                return response()->json(['success' => false, 'message' => 'No autorizado']);
            }
            if ($room->player1_attempts <= 0) {
                return response()->json(['success' => false, 'message' => 'Terminal bloqueada. No quedan intentos.']);
            }
            $room->player1_attempts--;
            $room->player2_attempts = $room->player1_attempts; // espejo para la UI
            $attemptsLeft  = $room->player1_attempts;
            $playerNumber  = $room->isPlayer1($token) ? 1 : 2;
            $playerName    = $playerNumber === 1 ? $room->player1_name : $room->player2_name;

        } elseif ($room->isPlayer1($token)) {
            $playerNumber = 1;
            $playerName   = $room->player1_name;
            if ($room->player1_attempts <= 0) {
                return response()->json(['success' => false, 'message' => 'Terminal bloqueada. No quedan intentos.']);
            }
            $room->player1_attempts--;
            $attemptsLeft = $room->player1_attempts;

        } elseif ($room->isPlayer2($token)) {
            $playerNumber = 2;
            $playerName   = $room->player2_name;
            if ($room->player2_attempts <= 0) {
                return response()->json(['success' => false, 'message' => 'Terminal bloqueada. No quedan intentos.']);
            }
            $room->player2_attempts--;
            $attemptsLeft = $room->player2_attempts;

        } else {
            return response()->json(['success' => false, 'message' => 'No autorizado']);
        }

        $guess    = strtoupper(trim($request->input('word')));
        $secret   = strtoupper($room->secret_word);
        $likeness = RobcoTerminalGenerator::calculateLikeness($guess, $secret);
        $isCorrect = ($guess === $secret);

        if ($isCorrect) {
            $room->status       = 'finished';
            $room->winner_token = $token;
            $room->winner_name  = $mode === 'cooperative'
                ? ($room->player1_name . ' & ' . ($room->player2_name ?? ''))
                : $playerName;
            $room->attempts_used = ($settings['attempts'] ?? 4) - $attemptsLeft;
            $room->save();

            broadcast(new TerminalHacked($room->code, $playerNumber, $room->winner_name, $secret, $room->attempts_used));
            broadcast(new TerminalRoomUpdated($room->code, 'finished'));

            return response()->json([
                'success'       => true,
                'is_correct'    => true,
                'likeness'      => strlen($secret),
                'attempts_left' => $attemptsLeft,
                'attempts_used' => $room->attempts_used,
                'message'       => $mode === 'cooperative' ? '¡ACCESO CONCEDIDO! PROTOCOLO COOPERATIVO EXITOSO.' : '¡ACCESO CONCEDIDO!',
            ]);
        }

        // ── Perk de Suerte: 30% de probabilidad de no gastar intento en fallo ──
        $playerPerk = ($playerNumber === 2 && !empty($settings['player2_perk']))
            ? $settings['player2_perk']
            : ($settings['player1_perk'] ?? $settings['perk'] ?? 'science');

        $luckSaved = false;
        if (!$isCorrect && $playerPerk === 'luck' && mt_rand(1, 100) <= 30) {
            if ($mode === 'cooperative') {
                $room->player1_attempts++;
                $room->player2_attempts = $room->player1_attempts;
                $attemptsLeft           = $room->player1_attempts;
            } elseif ($playerNumber === 1) {
                $room->player1_attempts++;
                $attemptsLeft           = $room->player1_attempts;
            } else {
                $room->player2_attempts++;
                $attemptsLeft           = $room->player2_attempts;
            }
            $luckSaved = true;
        }

        // ── Verificar bloqueo ────────────────────────────────────────────
        $isLocked   = ($attemptsLeft <= 0);
        $bothLocked = ($room->player1_attempts <= 0 && $room->player2_attempts <= 0);

        if ($bothLocked) {
            $room->status       = 'finished';
            $room->winner_token = 'none';
            $room->winner_name  = 'NINGUNO (BLOQUEADOS)';
            $room->save();

            broadcast(new TerminalAttemptMade($room->code, $playerNumber, $playerName, 0, $likeness, $room->word_length, true));
            broadcast(new TerminalHacked($room->code, 0, 'AMBOS BLOQUEADOS', $secret, $settings['attempts'] ?? 4));
            broadcast(new TerminalRoomUpdated($room->code, 'finished'));

            return response()->json([
                'success'      => true,
                'is_correct'   => false,
                'likeness'     => $showLikeness ? $likeness : null,
                'attempts_left' => 0,
                'is_locked'    => true,
                'both_locked'  => true,
                'luck_saved'   => false,
                'secret_word'  => $secret,
                'message'      => 'AMBOS OPERADORES BLOQUEADOS. ACCESO DENEGADO.',
            ]);
        }

        $room->save();

        broadcast(new TerminalAttemptMade(
            $room->code,
            $playerNumber,
            $playerName,
            $attemptsLeft,
            $likeness,
            $room->word_length,
            $isLocked
        ));

        return response()->json([
            'success'       => true,
            'is_correct'    => false,
            'likeness'      => $showLikeness ? $likeness : null,
            'attempts_left' => $attemptsLeft,
            'is_locked'     => $isLocked,
            'luck_saved'    => $luckSaved,
            // En modo cooperativo se notifica el pool compartido
            'shared_pool'   => $mode === 'cooperative' ? $attemptsLeft : null,
        ]);
    }

    /**
     * Procesa la bonificación por corchetes (Dud removed o Attempts restored).
     */
    public function useBracket(Request $request, string $code)
    {
        $request->validate([
            'bonus_type'  => 'required|string',
            'active_words' => 'nullable|array',
        ]);

        $token = $this->getPlayerToken();
        $room  = RobcoRoom::where('code', strtoupper($code))->firstOrFail();

        $playerNumber = $room->isPlayer1($token) ? 1 : ($room->isPlayer2($token) ? 2 : 0);
        if ($playerNumber === 0) {
            return response()->json(['success' => false, 'message' => 'No autorizado']);
        }

        $settings  = $room->settings ?? [];
        $mode      = $settings['mode'] ?? 'competitive';
        $maxAtt    = $settings['attempts'] ?? 4;
        $playerName = ($playerNumber === 1) ? $room->player1_name : $room->player2_name;
        $bonusType  = $request->input('bonus_type');
        $removedDud = null;

        if ($bonusType === 'restore_attempts') {
            if ($mode === 'cooperative') {
                $room->player1_attempts = $maxAtt;
                $room->player2_attempts = $maxAtt;
            } elseif ($playerNumber === 1) {
                $room->player1_attempts = $maxAtt;
            } else {
                $room->player2_attempts = $maxAtt;
            }
            $room->save();

            broadcast(new TerminalBracketUsed($room->code, $playerNumber, $playerName, 'restore_attempts', null, $maxAtt));

            return response()->json([
                'success'    => true,
                'bonus_type' => 'restore_attempts',
                'attempts'   => $maxAtt,
                'message'    => 'INTENTOS RESTABLECIDOS',
            ]);
        }

        if ($bonusType === 'offensive_glitch') {
            broadcast(new TerminalInterference($room->code, $playerNumber, $playerName, 'crt_glitch'));
            broadcast(new TerminalBracketUsed($room->code, $playerNumber, $playerName, 'offensive_glitch', null, $maxAtt));

            return response()->json([
                'success'    => true,
                'bonus_type' => 'offensive_glitch',
                'message'    => '¡ATAQUE ELECTROMAGNÉTICO EMITIDO AL RIVAL!',
            ]);
        }

        // Eliminar palabra incorrecta
        $activeWords = $request->input('active_words', []);
        $duds = array_filter($activeWords, fn($w) => strtoupper($w) !== strtoupper($room->secret_word));

        if (!empty($duds)) {
            $removedDud = $duds[array_rand($duds)];
        }

        broadcast(new TerminalBracketUsed($room->code, $playerNumber, $playerName, 'remove_dud', $removedDud, $maxAtt));

        return response()->json([
            'success'     => true,
            'bonus_type'  => 'remove_dud',
            'removed_dud' => $removedDud,
            'message'     => 'PALABRA FALSA ELIMINADA',
        ]);
    }

    /**
     * Inicia una revancha preservando la configuración de la sala.
     */
    public function rematch(Request $request, string $code)
    {
        $token = $this->getPlayerToken();
        $room  = RobcoRoom::where('code', strtoupper($code))->firstOrFail();

        if (!$room->isParticipant($token)) {
            return response()->json(['success' => false, 'message' => 'No autorizado']);
        }

        $settings = $room->settings ?? ['mode' => 'competitive', 'attempts' => 4, 'word_pack' => 'yermo', 'show_likeness' => true];
        $attempts = $settings['attempts'] ?? 4;
        $newGame  = RobcoTerminalGenerator::generateGame(12, 16, 12, $settings['word_pack'] ?? 'yermo');

        $room->secret_word      = $newGame['secret_word'];
        $room->word_length      = $newGame['word_length'];
        $room->grid_data        = $newGame;
        $room->player1_attempts = $attempts;
        $room->player2_attempts = $attempts;
        $room->status           = 'playing';
        $room->winner_token     = null;
        $room->winner_name      = null;
        $room->attempts_used    = null;
        $room->save();

        broadcast(new TerminalRematch($room->code, $newGame, $newGame['word_length']));
        broadcast(new TerminalRoomUpdated($room->code, 'playing'));

        return response()->json([
            'success'     => true,
            'grid_data'   => $newGame,
            'word_length' => $newGame['word_length'],
            'attempts'    => $attempts,
            'message'     => 'REVANCHA INICIALIZADA',
        ]);
    }

    /**
     * Modo espectador para la terminal (pantalla dividida).
     */
    public function spectateRoom(string $code)
    {
        $room = RobcoRoom::where('code', strtoupper($code))->firstOrFail();
        $roomChat = ChatMessage::recentForRoom($room->code, 30);

        return view('terminal.spectator', [
            'room'     => $room,
            'roomChat' => $roomChat,
        ]);
    }

    /**
     * Envía un mensaje al Hub Global (Lobby).
     */
    public function sendLobbyMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:240',
        ]);

        $messageText = trim($request->input('message'));
        if ($messageText === '') {
            return response()->json(['success' => false, 'message' => 'Mensaje vacío.'], 422);
        }

        $token = $this->getPlayerToken();
        $name  = session()->get('robco_operator_name');
        if (empty($name)) {
            $name = 'Operador_' . substr($token, 0, 4);
        }

        $title = trim($request->input('title', ''));
        if ($title !== '') {
            $name .= ' ' . $title;
        }

        $chat = ChatMessage::create([
            'room_code'    => null,
            'player_token' => $token,
            'player_name'  => $name,
            'message'      => $messageText,
        ]);

        ChatMessage::purgeOld(null, 60);

        broadcast(new LobbyChatMessage(
            $chat->id,
            $chat->player_name,
            $chat->player_token,
            $chat->message,
            $chat->created_at->format('H:i')
        ));

        return response()->json([
            'success' => true,
            'data'    => [
                'id'          => $chat->id,
                'playerName'  => $chat->player_name,
                'playerToken' => $chat->player_token,
                'message'     => $chat->message,
                'time'        => $chat->created_at->format('H:i'),
            ],
        ]);
    }

    /**
     * Envía un mensaje al chat interno de la sala de juego.
     */
    public function sendRoomMessage(Request $request, string $code)
    {
        $request->validate([
            'message' => 'required|string|max:240',
        ]);

        $messageText = trim($request->input('message'));
        if ($messageText === '') {
            return response()->json(['success' => false, 'message' => 'Mensaje vacío.'], 422);
        }

        $token = $this->getPlayerToken();
        $room  = RobcoRoom::where('code', strtoupper($code))->firstOrFail();

        $playerNumber = 0;
        if ($room->isPlayer1($token)) {
            $playerNumber = 1;
            $playerName   = $room->player1_name;
        } elseif ($room->isPlayer2($token)) {
            $playerNumber = 2;
            $playerName   = $room->player2_name;
        } else {
            $playerName = session()->get('robco_operator_name') ?: 'Espectador_' . substr($token, 0, 4);
        }

        $chat = ChatMessage::create([
            'room_code'    => $room->code,
            'player_token' => $token,
            'player_name'  => $playerName,
            'message'      => $messageText,
        ]);

        ChatMessage::purgeOld($room->code, 50);

        broadcast(new RoomChatMessage(
            $room->code,
            $playerName,
            $playerNumber,
            $token,
            $messageText,
            $chat->created_at->format('H:i')
        ));

        return response()->json([
            'success' => true,
            'data'    => [
                'id'           => $chat->id,
                'playerName'   => $playerName,
                'playerNumber' => $playerNumber,
                'playerToken'  => $token,
                'message'      => $messageText,
                'time'         => $chat->created_at->format('H:i'),
            ],
        ]);
    }

    /**
     * Crea una sala en modo solitario contra la IA de RobCo (ZAX Mainframe).
     */
    public function createAiRoom(Request $request)
    {
        $token      = $this->getPlayerToken();
        $code       = 'ZAX-' . strtoupper(Str::random(3));
        $settings   = $this->parseSettings($request);
        $difficulty = $request->input('ai_difficulty', 'normal');
        $settings['ai_mode']       = true;
        $settings['ai_difficulty'] = in_array($difficulty, ['easy', 'normal', 'hard']) ? $difficulty : 'normal';
        $settings['perk']          = in_array($request->input('perk'), self::VALID_PERKS) ? $request->input('perk') : 'science';
        $settings['player1_perk']  = $settings['perk'];

        $gameData = RobcoTerminalGenerator::generateGame(12, 16, 12, $settings['word_pack']);

        $chosenName = trim($request->input('player_name') ?: session()->get('robco_operator_name', ''));
        if ($chosenName) {
            session()->put('robco_operator_name', $chosenName);
        } else {
            $chosenName = 'Supervisor 101';
        }

        $room = RobcoRoom::create([
            'code'              => $code,
            'secret_word'       => $gameData['secret_word'],
            'word_length'       => $gameData['word_length'],
            'grid_data'         => $gameData,
            'status'            => 'playing',
            'settings'          => $settings,
            'player1_name'      => $chosenName,
            'player1_token'     => $token,
            'player2_name'      => 'ZAX MAINFRAME v1.0',
            'player2_token'     => 'AI_ZAX',
            'player1_attempts'  => $settings['attempts'],
            'player2_attempts'  => $settings['attempts'],
        ]);

        return redirect()->route('terminal.room', ['code' => $room->code]);
    }

    /**
     * Emite una interferencia electromagnética manual o por perk hacia el rival.
     */
    public function sendInterference(Request $request, string $code)
    {
        $token = $this->getPlayerToken();
        $room  = RobcoRoom::where('code', strtoupper($code))->firstOrFail();

        if ($room->status === 'finished') {
            return response()->json(['success' => false, 'message' => 'Partida finalizada']);
        }

        $playerNumber = $room->isPlayer1($token) ? 1 : ($room->isPlayer2($token) ? 2 : 0);
        $playerName   = ($playerNumber === 1) ? $room->player1_name : (($playerNumber === 2) ? $room->player2_name : 'Operador');
        $type         = $request->input('type', 'crt_glitch');

        broadcast(new TerminalInterference($room->code, $playerNumber, $playerName, $type));

        return response()->json([
            'success' => true,
            'message' => 'Interferencia transmitida con éxito',
        ]);
    }

    /**
     * Purga manual o bajo demanda de terminales inactivas/incompletas desde el lobby.
     */
    public function purgeRooms(Request $request)
    {
        $purgeAll = $request->boolean('all', false);
        $minutes  = max(1, (int) $request->input('minutes', 15));

        $count = $purgeAll
            ? RobcoRoom::purgeAll(false)
            : RobcoRoom::purgeStale($minutes, $minutes * 2, 90);

        if ($count > 0) {
            broadcast(new TerminalRoomsPurged($count, "Subred depurada por comando de mantenimiento."));
        }

        return response()->json([
            'success'      => true,
            'purged_count' => $count,
            'message'      => $count > 0
                ? "PROTOCOLO EJECUTADO: {$count} TERMINAL(ES) OBSOLETA(S) PURGADA(S) DE LA SUBRED."
                : "REGISTRO DE LA SUBRED YA SE ENCUENTRA OPTIMIZADO Y LIMPIO.",
        ]);
    }
}
