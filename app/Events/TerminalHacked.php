<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TerminalHacked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $roomCode;
    public int $winnerNumber;
    public string $winnerName;
    public string $secretWord;
    public int $attemptsUsed;
    public string $date;

    public function __construct(string $roomCode, int $winnerNumber, string $winnerName, string $secretWord, int $attemptsUsed = 1)
    {
        $this->roomCode = $roomCode;
        $this->winnerNumber = $winnerNumber;
        $this->winnerName = $winnerName;
        $this->secretWord = $secretWord;
        $this->attemptsUsed = $attemptsUsed;
        $this->date = date('H:i');
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('robco-room.' . $this->roomCode),
            new Channel('robco-lobby'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'TerminalHacked';
    }
}
