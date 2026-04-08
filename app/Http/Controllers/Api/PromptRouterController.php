<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromptRouterRequest;
use App\Services\EndpointOrchestrator;
use App\Services\NaturalLanguageResponder;
use App\Services\PromptIntentDetector;
use Illuminate\Http\JsonResponse;

class PromptRouterController extends Controller
{
    public function __invoke(
        PromptRouterRequest $request,
        PromptIntentDetector $detector,
        EndpointOrchestrator $orchestrator,
        NaturalLanguageResponder $responder,
    ): JsonResponse {
        $content = $request->string('content')->toString();

        $detection = $detector->detect($content);
        $orchestration = $orchestrator->call(
            $detection['intent'],
            $detection['entities'] ?? [],
            $content,
        );

        $naturalAnswer = $responder->respond($content, $detection, $orchestration);

        return response()->json([
            'success' => true,
            'intent' => $detection['intent'],
            'entities' => $detection['entities'],
            'forwarded_to' => $orchestration['target'],
            'data' => $orchestration['downstream_response'],
            'answer' => $naturalAnswer,
        ]);
    }
}
