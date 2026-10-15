<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TerminalPlayerJoined implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $roomCode;
    public string $player2Name;
    public string $message;

    public function __construct(string $roomCode, string $player2Name)
    {
        $this->roomCode = $roomCode;
        $this->player2Name = $player2Name;
        $this->message = "¡Intruso detectado! {$player2Name} se ha conectado a la terminal.";
    }

    public function broadcastOn(): array
    {
        return [new Channel('robco-room.' . $this->roomCode)];
    }

    public function broadcastAs(): string
    {
        return 'TerminalPlayerJoined';
    }
}
