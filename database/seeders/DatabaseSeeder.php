<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Produk;
use App\Models\Kategori;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $userData = [
            [
                'name'=>'Mas Operator',
                'email'=>'operator@gmail.com',
                'role'=>'operator',
                'password'=>bcrypt('operator123')
            ],
            [
                'name'=>'Mbak Keuangan',
                'email'=>'keuangan@gmail.com',
                'role'=>'keuangan',
                'password'=>bcrypt('keuangan123')
            ],
        ];

        $kategoriData = [
            ['name' => 'Makanan'],
            ['name' => 'Kebutuhan Rumah Tangga'],
            ['name' => 'Minuman'],
            ['name' => 'Kopi & Teh'],
            ['name' => 'Sembako'],
        ];

        $produkData = [
            [
                'kategori_id' => 1,
                'name' => 'Indomie Goreng',
                'harga' => 3500,
                'stok' => 120,
                'berat' => 80,
                'gambar' => 'uploads/images/indomie.jpg',
            ],
            [
                'kategori_id' => 2,
                'name' => 'Sabun Lifebuoy',
                'harga' => 4000,
                'stok' => 90,
                'berat' => 100,
                'gambar' => 'uploads/images/lifebuoy.jpg',
            ],
            [
                'kategori_id' => 3,
                'name' => 'Teh Botol Sosro',
                'harga' => 5000,
                'stok' => 60,
                'berat' => 350,
                'gambar' => 'uploads/images/tehbotol.jpg',
            ],
            [
                'kategori_id' => 4,
                'name' => 'Kopi Kapal Api',
                'harga' => 2500,
                'stok' => 100,
                'berat' => 25,
                'gambar' => 'uploads/images/kapalapi.jpg',
            ],
            [
                'kategori_id' => 5,
                'name' => 'Beras Pandan Wangi 5kg',
                'harga' => 65000,
                'stok' => 30,
                'berat' => 5000,
                'gambar' => 'uploads/images/beras.jpg',
            ],
        ];

        foreach ($produkData as $data) {
            Produk::create($data);
        }

        foreach ($userData as $key => $val) {
            User::create($val);
        }

        foreach ($kategoriData as $data) {
            Kategori::create($data);
        }
    }
}
