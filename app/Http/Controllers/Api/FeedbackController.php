<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeedbackLink;
use App\Services\MsForms\FormDefinitionService;
use App\Services\MsForms\MsFormsClient;
use App\Services\MsForms\MsFormsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    public function show(): JsonResponse
    {
        $feedbackLink = FeedbackLink::configured()->first();

        if (!$feedbackLink) {
            return response()->json(['message' => 'Feedback form is unavailable.'], 404);
        }

        try {
            $payload = app(FormDefinitionService::class)->resolve($feedbackLink);

            return response()->json($payload);
        } catch (MsFormsException $e) {
            Log::error('Feedback form load failed: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to load the form. Please try again later.'], 422);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.questionId' => ['required', 'string'],
            'answers.*.answer' => ['required'],
        ]);

        $feedbackLink = FeedbackLink::configured()->first();

        if (!$feedbackLink) {
            return response()->json(['message' => 'Feedback form is unavailable.'], 404);
        }

        try {
            $client = app(MsFormsClient::class);
            $target = $client->resolve($feedbackLink->link);

            $msAnswers = array_map(
                fn (array $answer) => [
                    'questionId' => $answer['questionId'],
                    'answer1' => is_array($answer['answer'])
                        ? json_encode($answer['answer'])
                        : (string) $answer['answer'],
                ],
                $validated['answers']
            );

            $now = now()->toIso8601String();
            $client->submitAnswers($target, $msAnswers, $now);

            return response()->json(['success' => true]);
        } catch (MsFormsException $e) {
            Log::error('Feedback form submit failed: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to submit the form. Please try again later.'], 422);
        }
    }
}