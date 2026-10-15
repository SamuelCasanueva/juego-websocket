<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RobcoRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'secret_word',
        'word_length',
        'grid_data',
        'status',
        'settings',
        'player1_name',
        'player2_name',
        'player1_token',
        'player2_token',
        'player1_attempts',
        'player2_attempts',
        'winner_token',
        'winner_name',
        'attempts_used',
    ];

    protected $casts = [
        'grid_data'  => 'array',
        'settings'   => 'array',
        'word_length' => 'integer',
        'player1_attempts' => 'integer',
        'player2_attempts' => 'integer',
    ];

    /**
     * Lectura segura de una clave de settings con valor por defecto.
     */
    public function getSetting(string $key, mixed $default = null): mixed
    {
        return ($this->settings ?? [])[$key] ?? $default;
    }

    public function isPlayer1(string $token): bool
    {
        return $this->player1_token === $token;
    }

    public function isPlayer2(string $token): bool
    {
        return $this->player2_token === $token;
    }

    public function isParticipant(string $token): bool
    {
        return $this->isPlayer1($token) || $this->isPlayer2($token);
    }

    /**
     * Purga terminales obsoletas / inactivas según su antigüedad y estado.
     * También limpia los mensajes de chat asociados a esas salas.
     */
    public static function purgeStale(int $waitingMinutes = 20, int $playingMinutes = 45, int $finishedMinutes = 120): int
    {
        $now = now();

        $staleWaitingCodes = static::where('status', 'waiting')
            ->where('updated_at', '<', $now->copy()->subMinutes($waitingMinutes))
            ->pluck('code');

        $stalePlayingCodes = static::where('status', 'playing')
            ->where('updated_at', '<', $now->copy()->subMinutes($playingMinutes))
            ->pluck('code');

        $staleFinishedCodes = static::where('status', 'finished')
            ->where('updated_at', '<', $now->copy()->subMinutes($finishedMinutes))
            ->pluck('code');

        $roomCodes = $staleWaitingCodes
            ->concat($stalePlayingCodes)
            ->concat($staleFinishedCodes)
            ->unique()
            ->values()
            ->all();

        if (empty($roomCodes)) {
            return 0;
        }

        ChatMessage::whereIn('room_code', $roomCodes)->delete();
        return static::whereIn('code', $roomCodes)->delete();
    }

    /**
     * Purga todas las salas incompletas (waiting/playing) o absolutamente todas.
     */
    public static function purgeAll(bool $includeFinished = false): int
    {
        $query = static::query();
        if (!$includeFinished) {
            $query->where('status', '!=', 'finished');
        }

        $roomCodes = $query->pluck('code')->all();
        if (empty($roomCodes)) {
            return 0;
        }

        ChatMessage::whereIn('room_code', $roomCodes)->delete();
        return static::whereIn('code', $roomCodes)->delete();
    }
}

