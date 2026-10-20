<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TerminalRematch implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $roomCode;
    public int $wordLength;
    public int $attempts;
    public string $message;

    public function __construct(string $roomCode, int $wordLength = 6, int $attempts = 4)
    {
        $this->roomCode = $roomCode;
        $this->wordLength = $wordLength;
        $this->attempts = $attempts;
        $this->message = "¡SUBRUTINA REINICIADA! NUEVO VOLCADO DE MEMORIA GENERADO.";
    }

    public function broadcastOn(): array
    {
        return [new Channel('robco-room.' . $this->roomCode)];
    }

    public function broadcastAs(): string
    {
        return 'TerminalRematch';
    }
}
