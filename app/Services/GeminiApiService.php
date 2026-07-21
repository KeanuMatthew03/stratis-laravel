<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiApiService
{
    private string $apiKey;
    private string $modelName;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY', '');
        $this->modelName = 'gemini-3.5-flash';
    }

    public function generateContent(string $prompt, array $history, string $systemContext): ?string
    {
        $systemInstruction = "You are an AI Tourism Data Analyst.\nYou are equipped with a specific dataset about tourism in Indonesia. Always prioritize answering from this dataset first.\nIf the user asks a general question outside the dataset, you may answer it normally.\nDataset:\n" . $systemContext;

        $contents = [
            ["role" => "user", "parts" => [["text" => $systemInstruction]]],
            ["role" => "model", "parts" => [["text" => "Understood."]]]
        ];

        foreach ($history as $msg) {
            $contents[] = [
                "role" => $msg['role'] === 'user' ? 'user' : 'model',
                "parts" => [["text" => $msg['text']]]
            ];
        }
        $contents[] = ["role" => "user", "parts" => [["text" => $prompt]]];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->modelName}:generateContent?key={$this->apiKey}", [
            'contents' => $contents
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        }

        throw new \Exception('API Error: ' . $response->body());
    }
}
