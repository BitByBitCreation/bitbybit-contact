<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;

class OpenAiClient implements OpenAiClientInterface
{
    private const API_ENDPOINT = 'https://api.openai.com/v1/chat/completions';
    private const MODEL = 'gpt-3.5-turbo';
    private const TEMPERATURE = 0.4;
    private const TIMEOUT_SECONDS = 30;
    private const FALLBACK_MESSAGE = 'Unfortunately no API response received.';

    public function getResponse(array $messages): string
    {
        try {
            $response = Http::withToken(config('services.openai.key'))
                ->timeout(self::TIMEOUT_SECONDS)
                ->post(self::API_ENDPOINT, [
                    'model' => self::MODEL,
                    'messages' => $messages,
                    'temperature' => self::TEMPERATURE,
                ]);

            $response->throw();

            return $response->json('choices.0.message.content') ?? self::FALLBACK_MESSAGE;

        } catch (RequestException $e) {
            Log::error('OpenAI API request failed', [
                'error' => $e->getMessage(),
                'status' => $e->response?->status(),
                'messages_count' => count($messages)
            ]);
            
            return self::FALLBACK_MESSAGE;
        }
    }
}
