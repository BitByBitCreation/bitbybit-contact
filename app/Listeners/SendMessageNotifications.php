<?php

namespace App\Listeners;

use App\Events\MessageAnalyzed;
use App\Mail\ConfirmationMail;
use Illuminate\Support\Facades\Mail;

/**
 * Sends confirmation email to sender after message was analyzed.
 */
class SendMessageNotifications
{
    public function handle(MessageAnalyzed $event): void
    {
        $dto = $event->messageDTO;
        Mail::to($dto->email)->send(new ConfirmationMail($dto));
    }
}
