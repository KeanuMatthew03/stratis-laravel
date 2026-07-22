<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $json = file_get_contents(storage_path('app/dataset.json'));
        $data = json_decode($json, true);
        
        $sumberMap = [];
        $negaraMap = [];

        // Create Indonesia as destination country
        $sumberBPS = \App\Models\Sumber::create(['nama_sumber' => 'BPS']);
        $sumberMap['BPS'] = $sumberBPS->id_sumber;
        
        $indonesia = \App\Models\Negara::create([
            'nama_negara' => 'Indonesia',
            'id_sumber' => $sumberBPS->id_sumber
        ]);
        $negaraMap['Indonesia'] = $indonesia->id_negara;

        foreach ($data as $item) {
            $negara = \App\Models\Negara::create([
                'nama_negara' => $item['Country'],
                'id_sumber' => $sumberMap['BPS']
            ]);

            $months = [
                'Jan' => 'JAN',
                'Feb' => 'FEB',
                'Mar' => 'MAR',
                'Apr' => 'APR',
                'Mei' => 'MAY'
            ];

            foreach ($months as $bulanIndo => $bulanEng) {
                if (isset($item[$bulanEng])) {
                    \App\Models\Kunjungan::create([
                        'jumlah' => $item[$bulanEng],
                        'bulan' => $bulanIndo,
                        'id_negara_asal' => $negara->id_negara,
                        'id_negara_tujuan' => $indonesia->id_negara
                    ]);
                }
            }
        }
    }
}