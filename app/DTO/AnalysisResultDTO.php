<?php

namespace App\DTO;

/**
 * DTO used to hold analysis results.
 */
class AnalysisResultDTO
{
    public function __construct(
        public string $sentiment,
        public array $keywords,
        public string $summary,
        public int $toxicity_score,
        public int $spam_score
    ) {}
}