<?php

namespace Database\Seeders;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\User;
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
        // Create admin user
        User::factory()->create([
            'nama' => 'Admin User',
            'nis' => '0000000001',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Create petugas user
        User::factory()->create([
            'nama' => 'Petugas User',
            'nis' => '0000000002',
            'email' => 'petugas@example.com',
            'role' => 'petugas',
        ]);

        // Create peminjam users
        User::factory(5)->create([
            'role' => 'peminjam',
        ]);

        // Create categories
        $categories = [
            'Peralatan Olahraga',
            'Peralatan Lab',
            'Peralatan Musik',
            'Peralatan Komputer',
        ];

        foreach ($categories as $category) {
            Kategori::create(['nama_kategori' => $category]);
        }

        // Create alats
        $alats = [
            ['nama_alat' => 'Bola Basket', 'kondisi' => 'baik', 'stok' => 10, 'kategori_id' => 1],
            ['nama_alat' => 'Bola Voli', 'kondisi' => 'baik', 'stok' => 8, 'kategori_id' => 1],
            ['nama_alat' => 'Mikroskop', 'kondisi' => 'baik', 'stok' => 5, 'kategori_id' => 2],
            ['nama_alat' => 'Gitar', 'kondisi' => 'baik', 'stok' => 3, 'kategori_id' => 3],
            ['nama_alat' => 'Laptop', 'kondisi' => 'baik', 'stok' => 15, 'kategori_id' => 4],
        ];

        foreach ($alats as $alat) {
            Alat::create($alat);
        }
    }
}
