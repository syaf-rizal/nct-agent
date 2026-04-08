<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class EndpointOrchestrator
{
    public function call(string $intent, array $entities, string $content): array
    {
        $routeConfig = config("prompt_router.intent_routes.{$intent}", config('prompt_router.intent_routes.general'));

        $payload = [
            'content' => $content,
            'entities' => $entities,
            'intent' => $intent,
        ];

        $response = $this->client()
            ->withToken(config('prompt_router.downstream.token'))
            ->post(Arr::get($routeConfig, 'url'), $payload)
            ->throw()
            ->json();

        return [
            'target' => Arr::only($routeConfig, ['name', 'url']),
            'downstream_response' => $response,
        ];
    }

    private function client(): PendingRequest
    {
        return Http::timeout((int) config('prompt_router.downstream.timeout', 20))
            ->acceptJson()
            ->asJson();
    }
}
