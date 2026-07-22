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
        $this->call([
            VisitorStatisticSeeder::class,
        ]);

        $json = file_get_contents(storage_path('app/dataset.json'));
        $data = json_decode($json, true);
        
        $sumberMap = [];

        foreach ($data as $item) {
            // Assume sumber is generic for now if not provided, or create one dummy sumber
            if (empty($sumberMap['BPS'])) {
                $sumber = \App\Models\Sumber::create(['nama_sumber' => 'BPS']);
                $sumberMap['BPS'] = $sumber->id_sumber;
            }

            $negara = \App\Models\Negara::create([
                'nama_negara' => $item['country_name'],
                'id_sumber' => $sumberMap['BPS']
            ]);

            $months = [
                'Januari' => 'jan',
                'Februari' => 'feb',
                'Maret' => 'mar',
                'April' => 'apr',
                'Mei' => 'may'
            ];

            foreach ($months as $bulanIndo => $bulanEng) {
                if (isset($item[$bulanEng])) {
                    \App\Models\Kunjungan::create([
                        'jumlah' => $item[$bulanEng],
                        'bulan' => $bulanIndo,
                        'id_negara_asal' => $negara->id_negara,
                        'id_negara_tujuan' => null // Nullable for generic
                    ]);
                }
            }
        }
    }
}