<?php

namespace App\Services;

use App\Models\VisitorStatistic;

class TourismDataService
{
    public function getRawDataset(): string
    {
        return VisitorStatistic::all()->toJson();
    }

    public function getParsedDataset(): array
    {
        return VisitorStatistic::all()->toArray();
    }

    public function getDashboardStats(): array
    {
        $dataset = VisitorStatistic::all();
        
        $totalCountries = $dataset->count();
        $totalVisitors = 0;
        $highest = ['name' => '', 'total' => 0];
        $lowest = ['name' => '', 'total' => PHP_INT_MAX];
        
        foreach ($dataset as $d) {
            $total = $d->jan + $d->feb + $d->mar + $d->apr + $d->may;
            $totalVisitors += $total;
            if ($total > $highest['total']) { 
                $highest = ['name' => $d->country_name, 'total' => $total]; 
            }
            if ($total < $lowest['total']) { 
                $lowest = ['name' => $d->country_name, 'total' => $total]; 
            }
        }

        if ($totalCountries === 0) {
            $lowest = ['name' => '-', 'total' => 0];
        }

        return compact('totalCountries', 'totalVisitors', 'highest', 'lowest');
    }

    public function getVisitorById(int $id): ?VisitorStatistic
    {
        return VisitorStatistic::find($id);
    }

    public function createVisitor(array $data): VisitorStatistic
    {
        return VisitorStatistic::create($data);
    }

    public function updateVisitor(int $id, array $data): bool
    {
        $visitor = VisitorStatistic::findOrFail($id);
        return $visitor->update($data);
    }

    public function deleteVisitor(int $id): bool
    {
        $visitor = VisitorStatistic::findOrFail($id);
        return $visitor->delete();
    }
}
