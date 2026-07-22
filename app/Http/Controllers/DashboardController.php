<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TourismDataService;
use App\Models\Negara;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    private TourismDataService $dataService;

    public function __construct(TourismDataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function index()
    {
        $stats = $this->dataService->getDashboardStats();
        $allData = Negara::with('kunjunganAsal')->get();
        
        $months = ['January', 'February', 'March', 'April', 'May'];
        $trendData = [
            $allData->sum('jan'),
            $allData->sum('feb'),
            $allData->sum('mar'),
            $allData->sum('apr'),
            $allData->sum('may')
        ];

        $countries = $allData->pluck('country_name');
        // Total per country for chart
        $totals = $allData->map(function($item) {
            return $item->jan + $item->feb + $item->mar + $item->apr + $item->may;
        });

        return view('dashboard.index', array_merge($stats, [
            'months' => $months,
            'trendData' => $trendData,
            'countries' => $countries,
            'totals' => $totals,
            'allData' => $allData
        ]));
    }

    public function datatable()
    {
        $query = Negara::with('kunjunganAsal');
        return DataTables::of($query)
            ->addColumn('actions', function ($row) {
                return '<button class="text-blue-500 hover:underline mr-2" onclick="editRow('.$row->id_negara.')">Edit</button>
                        <button class="text-red-500 hover:underline" onclick="deleteRow('.$row->id_negara.')">Delete</button>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
