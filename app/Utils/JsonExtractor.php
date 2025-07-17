<?php

namespace App\Utils;

class JsonExtractor
{
    private const ORIGINAL_TEXT = 'original_text';
    private const CLEANED_TEXT = 'cleaned_text';
    private const JSON_CANDIDATE = 'json_candidate';
    private const ERROR = 'error';
    private const JSON_FAILED = 'JSON extraction failed';

    /**
     * Extract the first JSON object from a text block.
     *
     * @param string $text The raw text (e.g. OpenAI response)
     * @return array|null Returns decoded array if valid JSON found, null otherwise
     */
    public static function extract(string $text): ?array
    {
        // Remove optional Markdown code block markers (```json ... ```)
        $cleaned = preg_replace('/^```json|```$/m', '', trim($text));

        // Match first JSON object
        if (preg_match('/\{.*\}/s', $cleaned, $matches)) {
            $json = $matches[0];

            try {
                return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                    \Log::debug(self::JSON_FAILED, [
                    self::ORIGINAL_TEXT => $text,
                    self::CLEANED_TEXT => $cleaned,
                    self::JSON_CANDIDATE => $json,
                    self::ERROR => $e->getMessage()
                ]);
                return null;
            }
        }

        return null;
    }
}
