<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TerminalRoomCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $code;
    public string $hostName;
    public string $status;
    public string $url;

    public function __construct(string $code, string $hostName, string $status = 'waiting')
    {
        $this->code = $code;
        $this->hostName = $hostName;
        $this->status = $status;
        $this->url = route('terminal.room', ['code' => $code]);
    }

    public function broadcastOn(): array
    {
        return [new Channel('robco-lobby')];
    }

    public function broadcastAs(): string
    {
        return 'TerminalRoomCreated';
    }
}
