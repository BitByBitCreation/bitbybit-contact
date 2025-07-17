<?php

namespace App\Services;

interface OpenAiClientInterface
{
    /**
     * Send chat messages to OpenAI and get a response string.
     *
     * @param array $messages
     * @return string
     */
    public function getResponse(array $messages): string;
}
