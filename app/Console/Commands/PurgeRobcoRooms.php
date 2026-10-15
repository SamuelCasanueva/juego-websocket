<?php

namespace App\Console\Commands;

use App\Events\TerminalRoomsPurged;
use App\Models\RobcoRoom;
use Illuminate\Console\Command;

class PurgeRobcoRooms extends Command
{
    /**
     * Nombre y firma del comando en la consola.
     */
    protected $signature = 'robco:purge-rooms
                            {--all : Purgar todas las salas activas e incompletas inmediatamente}
                            {--force-all : Purgar absolutamente todas las salas, incluyendo finalizadas}
                            {--minutes=20 : Minutos de antigüedad para considerar una sala en espera como obsoleta}
                            {--quiet-broadcast : No emitir evento WebSocket al lobby}';

    /**
     * Descripción del comando.
     */
    protected $description = 'Limpia terminales y salas de la subred RobCo inactivas, abandonadas u obsoletas';

    /**
     * Ejecuta el comando de consola.
     */
    public function handle(): int
    {
        $this->line('');
        $this->info('====================================================');
        $this->info('  ROBCO INDUSTRIES (TM) // SUBRED MAINTENANCE CLI   ');
        $this->info('====================================================');

        $isAll = (bool) $this->option('all');
        $isForceAll = (bool) $this->option('force-all');
        $minutes = max(1, (int) $this->option('minutes'));

        if ($isForceAll) {
            $this->warn('> Iniciando purga total de la base de datos (todas las salas)...');
            $count = RobcoRoom::purgeAll(true);
        } elseif ($isAll) {
            $this->warn('> Iniciando purga de todas las salas activas e incompletas...');
            $count = RobcoRoom::purgeAll(false);
        } else {
            $this->comment("> Purgando salas en espera con más de {$minutes} min, en juego con más de " . ($minutes * 2) . " min y finalizadas con más de 120 min...");
            $count = RobcoRoom::purgeStale($minutes, $minutes * 2, 120);
        }

        if ($count > 0) {
            $this->info("> [OK] Subred depurada: {$count} terminal(es) eliminada(s).");
            if (!$this->option('quiet-broadcast')) {
                try {
                    broadcast(new TerminalRoomsPurged($count, "Subred depurada por comando de mantenimiento."));
                } catch (\Throwable $e) {
                    // Ignorar si reverb no está escuchando en este instante
                }
            }
        } else {
            $this->line('> [INFO] No se encontraron terminales obsoletas. La subred está optimizada.');
        }

        $this->line('====================================================');
        $this->line('');

        return Command::SUCCESS;
    }
}
