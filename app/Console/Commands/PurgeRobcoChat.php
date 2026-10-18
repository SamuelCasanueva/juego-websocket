<?php

namespace App\Console\Commands;

use App\Events\LobbyChatCleared;
use App\Models\ChatMessage;
use Illuminate\Console\Command;

class PurgeRobcoChat extends Command
{
    /**
     * Nombre y firma del comando en la consola.
     */
    protected $signature = 'robco:purge-chat
                            {--all : Purgar todos los mensajes (lobby y todas las salas)}
                            {--room= : Purgar solo los mensajes de una sala específica (código de sala)}
                            {--quiet-broadcast : No emitir evento WebSocket al lobby}';

    /**
     * Descripción del comando.
     */
    protected $description = 'Limpia los mensajes del chat global del Lobby de RobCo (o de salas de juego)';

    /**
     * Ejecuta el comando de consola.
     */
    public function handle(): int
    {
        $this->line('');
        $this->info('====================================================');
        $this->info('   ROBCO INDUSTRIES (TM) // CHAT PURGE SUBSYSTEM    ');
        $this->info('====================================================');

        $isAll = (bool) $this->option('all');
        $roomCode = $this->option('room');

        if ($roomCode) {
            $code = strtoupper(trim($roomCode));
            $this->comment("> Purgando mensajes de la terminal/sala [{$code}]...");
            $count = ChatMessage::clearRoom($code);
            $this->info("> [OK] {$count} mensaje(s) de la sala [{$code}] purgado(s).");
        } elseif ($isAll) {
            $this->warn('> Iniciando purga total de todos los chats (Lobby + todas las salas)...');
            $count = ChatMessage::clearAll();
            $this->info("> [OK] Subred depurada: {$count} mensaje(s) eliminados en total.");
            $this->broadcastClear("Canal y salas purgados por protocolo administrativo.");
        } else {
            $this->comment('> Purgando mensajes del chat global del Lobby...');
            $count = ChatMessage::clearGlobal();
            $this->info("> [OK] Lobby depurado: {$count} mensaje(s) eliminados del chat global.");
            $this->broadcastClear("Canal de subred purgado por el Administrador.");
        }

        $this->line('====================================================');
        $this->line('');

        return Command::SUCCESS;
    }

    protected function broadcastClear(string $message): void
    {
        if (!$this->option('quiet-broadcast')) {
            try {
                broadcast(new LobbyChatCleared($message));
            } catch (\Throwable $e) {
                // Silencioso si Reverb no está activo en este momento
            }
        }
    }
}
