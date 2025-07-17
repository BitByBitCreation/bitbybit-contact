<?php

namespace App\DTO;

/**
 * DTO used to hold incoming message data and analysis results.
 */
class MessageAnalysisDTO
{
    public function __construct(
        public string $subject,
        public string $message,
        public string $sender,
        public string $email,
        public ?string $sentiment = null,
        public ?array $keywords = [],
        public ?string $summary = null,
        public ?int $toxicity_score = null,
        public ?int $spam_score = 0 
    ) {}


    public function applyAnalysis(AnalysisResultDTO $analysis): void
    {
        $this->sentiment = $analysis->sentiment;
        $this->keywords = $analysis->keywords;
        $this->summary = $analysis->summary;
        $this->toxicity_score = $analysis->toxicity_score;
        $this->spam_score = $analysis->spam_score;
    }
}