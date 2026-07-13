<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Region;
use App\Models\Umkm;
use Illuminate\Database\Seeder;

class UmkmProductSeeder extends Seeder
{
    public function run(): void
    {
        $regions = Region::pluck('id', 'kelurahan');
        $categories = Category::pluck('id', 'name');

        $umkms = [
            [
                'name' => 'Warung Nasi Bu Siti',
                'kelurahan' => 'Dago',
                'category' => 'Makanan',
                'description' => 'Warung nasi rumahan dengan lauk pauk segar, buka dari pagi sampai malam.',
                'wa_number' => '081234567801',
                'address' => 'Jl. Ir. H. Djuanda No. 12, Dago',
                'lat' => -6.8858, 'long' => 107.6134,
                'status' => 'active',
                'products' => [
                    ['name' => 'Nasi Ayam Geprek', 'price' => 15000],
                    ['name' => 'Nasi Pecel', 'price' => 13000],
                    ['name' => 'Es Teh Manis', 'price' => 5000],
                ],
            ],
            [
                'name' => 'Angkringan Mas Bro',
                'kelurahan' => 'Sekeloa',
                'category' => 'Makanan',
                'description' => 'Angkringan malam hari, nongkrong santai dengan menu khas Jogja.',
                'wa_number' => '081234567802',
                'address' => 'Jl. Sekeloa Tengah No. 5',
                'lat' => -6.8912, 'long' => 107.6161,
                'status' => 'active',
                'products' => [
                    ['name' => 'Sate Usus', 'price' => 10000],
                    ['name' => 'Wedang Jahe', 'price' => 8000],
                    ['name' => 'Nasi Kucing', 'price' => 5000],
                ],
            ],
            [
                'name' => 'Kedai Kopi Santai',
                'kelurahan' => 'Dago',
                'category' => 'Minuman',
                'description' => 'Kedai kopi kecil dengan racikan kopi susu gula aren andalan.',
                'wa_number' => '081234567803',
                'address' => 'Jl. Dago Pojok No. 20',
                'lat' => -6.8834, 'long' => 107.6142,
                'status' => 'active',
                'is_open' => false,
                'products' => [
                    ['name' => 'Kopi Susu Gula Aren', 'price' => 18000],
                    ['name' => 'Es Kopi Americano', 'price' => 15000],
                    ['name' => 'Roti Bakar Coklat', 'price' => 12000],
                ],
            ],
            [
                'name' => 'Laundry Kilat Bersih',
                'kelurahan' => 'Lebak Siliwangi',
                'category' => 'Jasa',
                'description' => 'Laundry kiloan dan setrika express, selesai dalam sehari.',
                'wa_number' => '081234567804',
                'address' => 'Jl. Siliwangi No. 8',
                'lat' => -6.8890, 'long' => 107.6098,
                'status' => 'active',
                'products' => [
                    ['name' => 'Cuci Kiloan Reguler (per kg)', 'price' => 7000],
                    ['name' => 'Cuci Setrika Express (per kg)', 'price' => 12000],
                ],
            ],
            [
                'name' => 'Bengkel Motor Pak Ujang',
                'kelurahan' => 'Sekeloa',
                'category' => 'Jasa',
                'description' => 'Servis motor harian, ganti oli, dan tambal ban.',
                'wa_number' => '081234567805',
                'address' => 'Jl. Sekeloa Selatan No. 3',
                'lat' => -6.8925, 'long' => 107.6175,
                'status' => 'active',
                'products' => [
                    ['name' => 'Servis Ringan', 'price' => 50000],
                    ['name' => 'Ganti Oli', 'price' => 75000],
                ],
            ],
            [
                'name' => 'Kerajinan Rotan Ibu Yani',
                'kelurahan' => 'Dago',
                'category' => 'Kerajinan',
                'description' => 'Kerajinan anyaman rotan dan bambu buatan tangan.',
                'wa_number' => '081234567806',
                'address' => 'Jl. Dago Asri No. 15',
                'lat' => -6.8845, 'long' => 107.6155,
                'status' => 'active',
                'products' => [
                    ['name' => 'Keranjang Rotan Kecil', 'price' => 45000],
                    ['name' => 'Tas Anyaman', 'price' => 65000],
                ],
            ],
            [
                'name' => 'Toko Sembako Barokah',
                'kelurahan' => 'Lebak Siliwangi',
                'category' => 'Sembako',
                'description' => 'Toko kelontong lengkap untuk kebutuhan sehari-hari.',
                'wa_number' => '081234567807',
                'address' => 'Jl. Tamansari No. 45',
                'lat' => -6.8877, 'long' => 107.6112,
                'status' => 'active',
                'products' => [
                    ['name' => 'Beras 5kg', 'price' => 65000],
                    ['name' => 'Minyak Goreng 2L', 'price' => 32000],
                    ['name' => 'Gula Pasir 1kg', 'price' => 15000],
                ],
            ],
            [
                'name' => 'Jahit Permak Bu Nunung',
                'kelurahan' => 'Dago',
                'category' => 'Jasa',
                'description' => 'Jasa permak dan jahit baju custom, dikerjakan rapi dan cepat.',
                'wa_number' => '081234567808',
                'address' => 'Jl. Dago Utara No. 9',
                'lat' => -6.8821, 'long' => 107.6128,
                'status' => 'active',
                'products' => [
                    ['name' => 'Permak Celana', 'price' => 20000],
                    ['name' => 'Jahit Baju Custom', 'price' => 100000],
                ],
            ],
            [
                'name' => 'Risoles & Kue Basah Mbak Dewi',
                'kelurahan' => 'Sekeloa',
                'category' => 'Makanan',
                'description' => 'Kue basah dan risoles homemade, terima pesanan untuk acara.',
                'wa_number' => '081234567809',
                'address' => 'Jl. Sekeloa Kaler No. 2',
                'lat' => -6.8905, 'long' => 107.6149,
                'status' => 'pending',
                'products' => [
                    ['name' => 'Risoles Mayo (per pcs)', 'price' => 3000],
                    ['name' => 'Kue Lapis (per box)', 'price' => 25000],
                ],
            ],
            [
                'name' => 'Hijau Asri - Pot & Tanaman Hias',
                'kelurahan' => 'Lebak Siliwangi',
                'category' => 'Kerajinan',
                'description' => 'Pot keramik dan tanaman hias untuk mempercantik rumah.',
                'wa_number' => '081234567810',
                'address' => 'Jl. Tamansari Bawah No. 18',
                'lat' => -6.8869, 'long' => 107.6120,
                'status' => 'pending',
                'products' => [
                    ['name' => 'Pot Keramik Kecil', 'price' => 35000],
                    ['name' => 'Tanaman Lidah Mertua', 'price' => 40000],
                ],
            ],
        ];

        foreach ($umkms as $data) {
            $products = $data['products'];
            $categoryId = $categories[$data['category']];
            $regionId = $regions[$data['kelurahan']];

            unset($data['products'], $data['category'], $data['kelurahan']);

            $umkm = Umkm::create([
                ...$data,
                'region_id' => $regionId,
            ]);

            foreach ($products as $product) {
                $umkm->products()->create([
                    ...$product,
                    'category_id' => $categoryId,
                ]);
            }
        }
    }
}
