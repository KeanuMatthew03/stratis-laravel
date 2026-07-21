<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VisitorStatistic;
use Illuminate\Support\Facades\Storage;

class VisitorStatisticSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(storage_path('app/dataset.json'));
        $data = json_decode($json, true);

        foreach ($data as $item) {
            VisitorStatistic::create([
                'country_name' => $item['Country'],
                'jan' => $item['JAN'] ?? 0,
                'feb' => $item['FEB'] ?? 0,
                'mar' => $item['MAR'] ?? 0,
                'apr' => $item['APR'] ?? 0,
                'may' => $item['MAY'] ?? 0,
            ]);
        }
    }
}
