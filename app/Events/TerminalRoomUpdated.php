<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TerminalRoomUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $code;
    public string $status;
    public ?string $player2Name;

    public function __construct(string $code, string $status, ?string $player2Name = null)
    {
        $this->code = $code;
        $this->status = $status;
        $this->player2Name = $player2Name;
    }

    public function broadcastOn(): array
    {
        return [new Channel('robco-lobby')];
    }

    public function broadcastAs(): string
    {
        return 'TerminalRoomUpdated';
    }
}
