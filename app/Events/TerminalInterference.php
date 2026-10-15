<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TerminalInterference implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $roomCode;
    public int $senderPlayerNumber;
    public string $senderName;
    public string $type;
    public string $message;

    public function __construct(string $roomCode, int $senderPlayerNumber, string $senderName, string $type = 'crt_glitch')
    {
        $this->roomCode = $roomCode;
        $this->senderPlayerNumber = $senderPlayerNumber;
        $this->senderName = $senderName;
        $this->type = $type;
        $this->message = "⚠️ ¡INTERFERENCIA ELECTROMAGNÉTICA EMITIDA POR {$senderName}!";
    }

    public function broadcastOn(): array
    {
        return [new Channel('robco-room.' . $this->roomCode)];
    }

    public function broadcastAs(): string
    {
        return 'TerminalInterference';
    }
}
