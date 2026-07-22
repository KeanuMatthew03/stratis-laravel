<?php

namespace App\Services;

use App\Models\Negara;
use App\Models\Kunjungan;

class TourismDataService
{
    public function getRawDataset(): string
    {
        return Negara::with('kunjunganAsal')->get()->toJson();
    }

    public function getParsedDataset(): array
    {
        return Negara::with('kunjunganAsal')->get()->toArray();
    }

    public function getDashboardStats(): array
    {
        $dataset = Negara::with('kunjunganAsal')->get();
        
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

    public function getVisitorById(int $id): ?Negara
    {
        return Negara::with('kunjunganAsal')->find($id);
    }

    public function createVisitor(array $data): Negara
    {
        // CRUD for ERD logic
        $negara = Negara::create([
            'nama_negara' => $data['country_name'],
            'id_sumber' => 1 // default
        ]);
        
        $bulans = ['Jan' => 'jan', 'Feb' => 'feb', 'Mar' => 'mar', 'Apr' => 'apr', 'Mei' => 'may'];
        foreach ($bulans as $bulan => $key) {
            Kunjungan::create([
                'id_negara_asal' => $negara->id_negara,
                'id_negara_tujuan' => 1, // Assume Indonesia is ID 1 or adjust as needed
                'bulan' => $bulan,
                'jumlah' => $data[$key]
            ]);
        }
        
        return $negara;
    }

    public function updateVisitor(int $id, array $data): bool
    {
        $negara = Negara::findOrFail($id);
        $negara->update(['nama_negara' => $data['country_name']]);
        
        $bulans = ['Jan' => 'jan', 'Feb' => 'feb', 'Mar' => 'mar', 'Apr' => 'apr', 'Mei' => 'may'];
        foreach ($bulans as $bulan => $key) {
            Kunjungan::updateOrCreate(
                ['id_negara_asal' => $negara->id_negara, 'bulan' => $bulan],
                ['jumlah' => $data[$key], 'id_negara_tujuan' => 1]
            );
        }
        return true;
    }

    public function deleteVisitor(int $id): bool
    {
        $negara = Negara::findOrFail($id);
        Kunjungan::where('id_negara_asal', $negara->id_negara)->delete();
        return $negara->delete();
    }
}
