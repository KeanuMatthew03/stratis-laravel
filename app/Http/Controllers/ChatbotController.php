<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TourismDataService;
use App\Services\GeminiApiService;

class ChatbotController extends Controller
{
    private TourismDataService $dataService;
    private GeminiApiService $aiService;

    // Dependency Injection according to SOLID principles
    public function __construct(TourismDataService $dataService, GeminiApiService $aiService)
    {
        $this->dataService = $dataService;
        $this->aiService = $aiService;
    }

    public function index()
    {
        $stats = $this->dataService->getDashboardStats();
        return view('welcome', $stats);
    }

    public function chat(Request $request)
    {
        $messages = $request->input('messages', []);
        $prompt = $request->input('prompt', '');
        
        $datasetContext = $this->dataService->getRawDataset();

        try {
            $text = $this->aiService->generateContent($prompt, $messages, $datasetContext);
            return response()->json(['text' => $text]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
