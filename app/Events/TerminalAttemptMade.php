<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TerminalAttemptMade implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $roomCode;
    public int $playerNumber;
    public string $playerName;
    public int $attemptsLeft;
    public int $likeness;
    public int $wordLength;
    public bool $isLocked;

    public function __construct(
        string $roomCode,
        int $playerNumber,
        string $playerName,
        int $attemptsLeft,
        int $likeness,
        int $wordLength,
        bool $isLocked = false
    ) {
        $this->roomCode = $roomCode;
        $this->playerNumber = $playerNumber;
        $this->playerName = $playerName;
        $this->attemptsLeft = $attemptsLeft;
        $this->likeness = $likeness;
        $this->wordLength = $wordLength;
        $this->isLocked = $isLocked;
    }

    public function broadcastOn(): array
    {
        return [new Channel('robco-room.' . $this->roomCode)];
    }

    public function broadcastAs(): string
    {
        return 'TerminalAttemptMade';
    }
}
