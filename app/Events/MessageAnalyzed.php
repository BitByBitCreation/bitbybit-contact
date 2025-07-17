<?php

namespace App\Events;

use App\DTO\MessageAnalysisDTO;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event dispatched after AI analysis is complete.
 */
class MessageAnalyzed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public MessageAnalysisDTO $messageDTO
    ) {}
}
