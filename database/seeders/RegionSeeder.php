<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['kecamatan' => 'Coblong', 'kelurahan' => 'Dago', 'kota' => 'Kota Bandung'],
            ['kecamatan' => 'Coblong', 'kelurahan' => 'Sekeloa', 'kota' => 'Kota Bandung'],
            ['kecamatan' => 'Coblong', 'kelurahan' => 'Lebak Siliwangi', 'kota' => 'Kota Bandung'],
        ])->each(fn (array $region) => Region::create($region));
    }
}
