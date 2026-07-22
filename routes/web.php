<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;

// Dashboard Routes
Route::get('/', [DashboardController::class, 'index']);
Route::get('/api/visitors/datatable', [DashboardController::class, 'datatable']);

use App\Http\Controllers\VisitorStatisticController;
Route::get('/api/visitors/{id}', [VisitorStatisticController::class, 'show']);
Route::post('/api/visitors', [VisitorStatisticController::class, 'store']);
Route::put('/api/visitors/{id}', [VisitorStatisticController::class, 'update']);
Route::delete('/api/visitors/{id}', [VisitorStatisticController::class, 'destroy']);

// AI Assistant UI Route
Route::get('/ai-assistant', function () {
    return view('ai-assistant.index');
});

use App\Http\Controllers\ChatbotController;

// AI Chat API Route
Route::post('/api/chat', [ChatbotController::class, 'chat']);

// Remote DB Setup Route for Vercel
Route::get('/setup-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Database migrated and seeded successfully!',
            'output' => \Illuminate\Support\Facades\Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});
