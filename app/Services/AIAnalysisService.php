<?php

namespace App\Services;

use App\DTO\AnalysisResultDTO;
use App\Enums\AnalysisField;
use App\Utils\JsonExtractor;

class AIAnalysisService
{
    private const ROLE = 'role';
    private const SYSTEM = 'system';
    private const CONTENT = 'content';
    private const USER = 'user';
    private const ERROR = 'error';
    private const ANALYSE_ERROR = 'Errors during the analysis';
    private const RAW_AI_RESPONSE = 'Raw AI response';
    private const NO_VALIDE_JSON = 'No valid JSON found in response.';
    private const NEUTRAL = 'neutral';
    private const PARSING_FAILED = 'AI response parsing failed';

    public function __construct(
        private OpenAiClientInterface $client,
    ) {}

   public function analyze(string $message): AnalysisResultDTO
    {
        $promptMessages = [
            [self::ROLE => self::SYSTEM, self::CONTENT => $this->getPrompt()],
            [self::ROLE => self::USER, self::CONTENT  => $message]
        ];
        
        $responseContent = $this->client->getResponse($promptMessages);
        
        return $this->parseResponse($responseContent);
    }

    private function getPrompt(): string
    {
        return <<<PROMPT
    Analyze the message for:
    1. Sentiment (only: positiv/neutral/negativ)
    2. 3-5 main keywords
    3. Short summary (max. 1 sentence)
    4. Toxicity score (0-100)
    5. Spam score (0-100)

    Response ONLY as a pure JSON object WITHOUT additional text or explanations:

    {
        "sentiment": "neutral",
        "keywords": ["Word1", "Word2"],
        "summary": "Summary here",
        "toxicity_score": 0,
        "spam_score": 0
    }
    PROMPT;
    }


    private function parseResponse(string $responseContent): AnalysisResultDTO
    {
        \Log::debug(self::RAW_AI_RESPONSE, [self::CONTENT => $responseContent]);

        try {
            $data = JsonExtractor::extract($responseContent);

            if (!$data) {
                throw new \RuntimeException(self::NO_VALIDE_JSON);
            }

            return new AnalysisResultDTO(
                sentiment: $data[AnalysisField::Sentiment->value] ?? self::NEUTRAL,
                keywords: $data[AnalysisField::Keywords->value] ?? [],
                summary: $data[AnalysisField::Summary->value] ?? '',
                toxicity_score: $data[AnalysisField::ToxicityScore->value] ?? 0,
                spam_score: $data[AnalysisField::SpamScore->value] ?? 0
            );

        } catch (\Throwable $e) {
            \Log::error(self::PARSING_FAILED, [
                'class' => self::class,
                self::ERROR => $e->getMessage(),
                self::CONTENT => $responseContent,
            ]);

            return new AnalysisResultDTO(
                sentiment: self::NEUTRAL,
                keywords: [],
                summary: self::ANALYSE_ERROR,
                toxicity_score: 0,
                spam_score: 0
            );
        }
    }
}