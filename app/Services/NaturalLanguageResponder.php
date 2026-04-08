<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NaturalLanguageResponder
{
    public function respond(string $content, array $detection, array $orchestration): string
    {
        $masterPrompt = config('prompt_router.master_prompt');

        $response = Http::timeout((int) config('prompt_router.llm.timeout', 25))
            ->withToken(config('prompt_router.llm.api_key'))
            ->post(config('prompt_router.llm.url'), [
                'model' => config('prompt_router.llm.model'),
                'input' => [
                    ['role' => 'system', 'content' => $masterPrompt],
                    ['role' => 'user', 'content' => $content],
                    ['role' => 'system', 'content' => json_encode([
                        'detected_intent' => $detection['intent'],
                        'entities' => $detection['entities'] ?? [],
                        'downstream' => $orchestration,
                    ], JSON_UNESCAPED_UNICODE)],
                ],
                'temperature' => 0.2,
            ])
            ->throw()
            ->json();

        return data_get($response, 'output_text', 'Saya sudah memproses permintaan Anda, namun ringkasan respons belum tersedia.');
    }
}
