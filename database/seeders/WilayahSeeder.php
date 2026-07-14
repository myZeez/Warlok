<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WilayahSeeder extends Seeder
{
    private const CHUNK_SIZE = 500;

    public function run(): void
    {
        if (DB::table('wilayah_provinces')->exists()) {
            $this->command?->info('Wilayah reference data already seeded — skipping.');

            return;
        }

        DB::connection()->disableQueryLog();

        DB::transaction(function () {
            $this->importProvinces();
            $this->importRegencies();
            $this->importDistricts();
            $this->importVillages();
        });
    }

    private function importProvinces(): void
    {
        $this->streamCsv(
            'provinces.csv.gz',
            fn (array $row) => [
                'id' => $row[0],
                'name' => $this->titleCaseProvince($row[1]),
            ],
            'wilayah_provinces',
        );
    }

    private function importRegencies(): void
    {
        $this->streamCsv(
            'regencies.csv.gz',
            fn (array $row) => [
                'id' => $row[0],
                'province_id' => $row[1],
                'name' => $this->titleCase($row[2]),
            ],
            'wilayah_regencies',
        );
    }

    private function importDistricts(): void
    {
        $this->streamCsv(
            'districts.csv.gz',
            fn (array $row) => [
                'id' => $row[0],
                'regency_id' => $row[1],
                'name' => $this->titleCase($row[2]),
            ],
            'wilayah_districts',
        );
    }

    private function importVillages(): void
    {
        $this->streamCsv(
            'villages.csv.gz',
            fn (array $row) => [
                'id' => $row[0],
                'district_id' => $row[1],
                'name' => $this->titleCase($row[2]),
            ],
            'wilayah_villages',
        );
    }

    private function streamCsv(string $filename, callable $mapRow, string $table): void
    {
        $path = database_path("seeders/data/wilayah/{$filename}");
        $handle = fopen("compress.zlib://{$path}", 'rb');

        $chunk = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2 || blank($row[0])) {
                continue;
            }

            $chunk[] = $mapRow($row);

            if (count($chunk) >= self::CHUNK_SIZE) {
                // insertOrIgnore, not insert: the source dataset has a handful of duplicate
                // IDs (e.g. village 9107182005 appears twice, under different names) — an
                // upstream data-quality artifact, not something this seeder should crash on.
                DB::table($table)->insertOrIgnore($chunk);
                $chunk = [];
            }
        }

        if ($chunk !== []) {
            DB::table($table)->insertOrIgnore($chunk);
        }

        fclose($handle);
    }

    private function titleCase(string $name): string
    {
        return Str::title(Str::lower(trim($name)));
    }

    private function titleCaseProvince(string $name): string
    {
        $name = $this->titleCase($name);

        return preg_replace_callback(
            '/^(Dki|Di)\s/',
            fn (array $matches) => strtoupper($matches[1]).' ',
            $name,
            1,
        );
    }
}
