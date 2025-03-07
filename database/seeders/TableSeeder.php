<?php

namespace Database\Seeders;

use App\Models\Restaurant\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tablesCount = rand(5, 20);

        for ($i = 0; $i < $tablesCount; $i++) {
            Table::create([
                'capacity' => [2, 4, 5, 6, 7, 9][rand(0, 4)],
            ]);
        }
    }
}
