<?php

namespace App\Listeners;

use App\DTO\MessageAnalysisDTO;
use App\Events\MessageAnalyzed;
use App\Events\MessageReceived;
use App\Services\AIAnalysisService;
use App\Enums\AnalysisField;

class AnalyzeMessage
{
    private const KEY_EMAIL = 'email';
    private const KEY_ERROR = 'error';
    private const SPAM = 'spam';
    private const MSG_SPAM_DETECTED = 'Spam detected, skipping notifications';
    private const MSG_ANALYSE_MESSAGE = 'AnalyzeMessage listener triggered';
    private const MSG_AI_ANALYSIS_FAILED = 'AI analysis failed';

    public function __construct(
        private readonly AIAnalysisService $aiService,
    ) {}

    public function handle(MessageReceived $event): void
    {
        \Log::debug(self::MSG_ANALYSE_MESSAGE);
        
        try {
            $analysisResult = $this->aiService->analyze($event->messageDTO->message);
            $event->messageDTO->applyAnalysis($analysisResult);
            
            if (!$this->isSpam($event->messageDTO)) {
                MessageAnalyzed::dispatch($event->messageDTO);
            } else {
                \Log::info(self::MSG_SPAM_DETECTED, [
                    self::KEY_EMAIL => $event->messageDTO->email,
                    AnalysisField::SpamScore->value => $event->messageDTO->spam_score,
                    AnalysisField::ToxicityScore->value => $event->messageDTO->toxicity_score,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error(self::MSG_AI_ANALYSIS_FAILED, [
                self::KEY_ERROR => $e->getMessage(),
                self::KEY_EMAIL => $event->messageDTO->email
            ]);
        }
    }

    private function isSpam(MessageAnalysisDTO $dto): bool
    {
        return $dto->toxicity_score > 80 || 
        $dto->spam_score > 50 ||
        in_array(self::SPAM, $dto->keywords);
    }

}