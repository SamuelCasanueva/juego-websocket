<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class ChatMessage extends Model
{
    protected $fillable = [
        'room_code',
        'player_token',
        'player_name',
        'message',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────

    public function scopeGlobal(Builder $query): Builder
    {
        return $query->whereNull('room_code');
    }

    public function scopeForRoom(Builder $query, string $code): Builder
    {
        return $query->where('room_code', strtoupper($code));
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    /**
     * Últimos N mensajes globales en orden cronológico.
     */
    public static function recentGlobal(int $limit = 50): Collection
    {
        return static::global()
            ->latest()
            ->take($limit)
            ->get()
            ->reverse()
            ->values();
    }

    /**
     * Últimos N mensajes de una sala en orden cronológico.
     */
    public static function recentForRoom(string $code, int $limit = 30): Collection
    {
        return static::forRoom($code)
            ->latest()
            ->take($limit)
            ->get()
            ->reverse()
            ->values();
    }

    /**
     * Mantiene solo los últimos $keep mensajes por contexto.
     * Evita crecimiento ilimitado de la tabla.
     */
    public static function purgeOld(?string $roomCode, int $keep = 100): void
    {
        $keepIds = static::when(
            $roomCode === null,
            fn (Builder $q) => $q->whereNull('room_code'),
            fn (Builder $q) => $q->where('room_code', $roomCode)
        )
            ->latest()
            ->take($keep)
            ->pluck('id');

        static::when(
            $roomCode === null,
            fn (Builder $q) => $q->whereNull('room_code'),
            fn (Builder $q) => $q->where('room_code', $roomCode)
        )
            ->when($keepIds->isNotEmpty(), fn (Builder $q) => $q->whereNotIn('id', $keepIds))
            ->delete();
    }

    /**
     * Elimina todos los mensajes de una sala (al finalizar la partida).
     */
    public static function clearRoom(string $code): int
    {
        return static::forRoom($code)->delete();
    }

    /**
     * Elimina todos los mensajes del lobby global.
     */
    public static function clearGlobal(): int
    {
        return static::global()->delete();
    }

    /**
     * Elimina absolutamente todos los mensajes (global y salas).
     */
    public static function clearAll(): int
    {
        return static::query()->delete();
    }
}
