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
    public array $gridData;
    public int $wordLength;
    public string $message;

    public function __construct(string $roomCode, array $gridData, int $wordLength)
    {
        $this->roomCode = $roomCode;
        $this->gridData = $gridData;
        $this->wordLength = $wordLength;
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
