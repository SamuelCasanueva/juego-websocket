<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LobbyChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $id;
    public string $playerName;
    public string $playerToken;
    public string $message;
    public string $time;

    public function __construct(int $id, string $playerName, string $playerToken, string $message, string $time)
    {
        $this->id = $id;
        $this->playerName = $playerName;
        $this->playerToken = $playerToken;
        $this->message = $message;
        $this->time = $time;
    }

    public function broadcastOn(): array
    {
        return [new Channel('robco-lobby-chat')];
    }

    public function broadcastAs(): string
    {
        return 'LobbyChatMessage';
    }
}
