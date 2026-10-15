<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TerminalRoomsPurged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $purgedCount;
    public string $message;

    public function __construct(int $purgedCount, string $message = '')
    {
        $this->purgedCount = $purgedCount;
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [new Channel('robco-lobby')];
    }

    public function broadcastAs(): string
    {
        return 'TerminalRoomsPurged';
    }
}
