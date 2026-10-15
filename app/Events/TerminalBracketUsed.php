<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TerminalBracketUsed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $roomCode;
    public int $playerNumber;
    public string $playerName;
    public string $bonusType; // 'remove_dud' o 'restore_attempts'
    public ?string $removedDud;
    public int $attempts;

    public function __construct(
        string $roomCode,
        int $playerNumber,
        string $playerName,
        string $bonusType,
        ?string $removedDud = null,
        int $attempts = 4
    ) {
        $this->roomCode = $roomCode;
        $this->playerNumber = $playerNumber;
        $this->playerName = $playerName;
        $this->bonusType = $bonusType;
        $this->removedDud = $removedDud;
        $this->attempts = $attempts;
    }

    public function broadcastOn(): array
    {
        return [new Channel('robco-room.' . $this->roomCode)];
    }

    public function broadcastAs(): string
    {
        return 'TerminalBracketUsed';
    }
}
