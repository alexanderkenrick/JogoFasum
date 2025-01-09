<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $table = 'kategoris';
        $data = [
            ['nama' => 'Sekolah'],
            ['nama' => 'Rumah Sakit'],
            ['nama' => 'Perpustakaan'],
            ['nama' => 'Pasar'],
            ['nama' => 'Stasiun'],
            ['nama' => 'Terminal'],
            ['nama' => 'Lapangan Olahraga'],
            ['nama' => 'Pantai'],
            ['nama' => 'Hutan Kota'],
            ['nama' => 'Museum'],
            ['nama' => 'Mall'],
            ['nama' => 'Kantor Pemerintah'],
            ['nama' => 'Tempat Ibadah'],
            ['nama' => 'Jembatan'],
            ['nama' => 'Sungai'],
            ['nama' => 'Jalan']
        ];

        DB::table($table)->insert($data);
    }
}
