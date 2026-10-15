<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $roomCode;
    public string $playerName;
    public int $playerNumber;
    public string $playerToken;
    public string $message;
    public string $time;

    public function __construct(string $roomCode, string $playerName, int $playerNumber, string $playerToken, string $message, string $time)
    {
        $this->roomCode = $roomCode;
        $this->playerName = $playerName;
        $this->playerNumber = $playerNumber;
        $this->playerToken = $playerToken;
        $this->message = $message;
        $this->time = $time;
    }

    public function broadcastOn(): array
    {
        return [new Channel('robco-room.' . $this->roomCode)];
    }

    public function broadcastAs(): string
    {
        return 'RoomChatMessage';
    }
}
