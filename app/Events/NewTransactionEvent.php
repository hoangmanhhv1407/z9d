<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NewTransactionEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $userId;
    public $amount;
    public $currentCoins;

    public function __construct($userId, $amount, $currentCoins)
    {
        $this->userId = $userId;
        $this->amount = $amount;
        $this->currentCoins = $currentCoins;
    }

    public function broadcastOn()
    
    {
        return new PrivateChannel('user.' . $this->userId);
    }
        // Thêm method này để định nghĩa tên event
        public function broadcastAs()
        {
            return 'NewTransactionEvent';
        }
}
