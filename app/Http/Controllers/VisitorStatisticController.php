<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TourismDataService;

class VisitorStatisticController extends Controller
{
    private TourismDataService $dataService;

    public function __construct(TourismDataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function show($id)
    {
        $visitor = $this->dataService->getVisitorById($id);
        if (!$visitor) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json($visitor);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'country_name' => 'required|string|max:255',
            'jan' => 'required|integer|min:0',
            'feb' => 'required|integer|min:0',
            'mar' => 'required|integer|min:0',
            'apr' => 'required|integer|min:0',
            'may' => 'required|integer|min:0',
        ]);

        $visitor = $this->dataService->createVisitor($data);
        return response()->json(['success' => true, 'data' => $visitor]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'country_name' => 'required|string|max:255',
            'jan' => 'required|integer|min:0',
            'feb' => 'required|integer|min:0',
            'mar' => 'required|integer|min:0',
            'apr' => 'required|integer|min:0',
            'may' => 'required|integer|min:0',
        ]);

        $this->dataService->updateVisitor($id, $data);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $this->dataService->deleteVisitor($id);
        return response()->json(['success' => true]);
    }
}
