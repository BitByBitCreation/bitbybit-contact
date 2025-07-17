<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\DTO\MessageAnalysisDTO;

/**
 * Event dispatched after message is received.
 */
class MessageReceived
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public MessageAnalysisDTO $messageDTO)
    {
    }

}
