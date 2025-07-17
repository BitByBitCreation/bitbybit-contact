<?php

namespace App\Listeners;

use App\Events\MessageAnalyzed;
use App\Mail\CombinedContactMail;
use App\Services\AIResponseService;
use Illuminate\Support\Facades\Mail;

/**
 * Sends analyzed contact email to admin.
 */
class SendCombinedContactMail
{
    public function __construct(
        private readonly AIResponseService $aiResponseService
    ) {}

    public function handle(MessageAnalyzed $event): void
    {
        $dto = $event->messageDTO;

        if ($dto->spam_score < 50) {
            $draft = $this->aiResponseService->generateDraft($dto);

            Mail::to(config('mail.admin_address'))->send(
                new CombinedContactMail($dto, $draft)
            );
        }
    }
}
