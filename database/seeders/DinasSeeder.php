<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $table = 'dinas';
        $data = [
            ['nama' => 'Dinas Kota Surabaya'],
            ['nama' => 'Dinas Kabupaten Sidoarjo'],
            ['nama' => 'Dinas Kota Sidoarjo'],
            ['nama' => 'Dinas Kabupaten Gresik'],
            ['nama' => 'Dinas Kabupaten Malang'],
            ['nama' => 'Dinas Kota Malang'],
            ['nama' => 'Dinas Kota Jember'],
            ['nama' => 'Dinas Kabupaten Jember'],
            ['nama' => 'Dinas Kota Kediri'],
            ['nama' => 'Dinas Kabupaten Kediri'],
            ['nama' => 'Dinas Kota Mojokerto'],
            ['nama' => 'Dinas Kabupaten Mojokerto'],
            ['nama' => 'Dinas Kota Pasuruan'],
            ['nama' => 'Dinas Kabupaten Pasuruan'],
            ['nama' => 'Dinas Kota Probolinggo'],
            ['nama' => 'Dinas Kabupaten Probolinggo'],
            ['nama' => 'Dinas Kota Batu'],
            ['nama' => 'Dinas Kabupaten Batu'],
            ['nama' => 'Dinas Kota Blitar'],
            ['nama' => 'Dinas Kabupaten Blitar'],
            ['nama' => 'Dinas Kota Madiun'],
            ['nama' => 'Dinas Kabupaten Madiun'],
            ['nama' => 'Dinas Kota Lumajang'],
            ['nama' => 'Dinas Kabupaten Lumajang'],
            ['nama' => 'Dinas Kota Ngawi'],
            ['nama' => 'Dinas Kabupaten Ngawi'],
            ['nama' => 'Dinas Kota Pamekasan'],
            ['nama' => 'Dinas Kabupaten Pamekasan'],
            ['nama' => 'Dinas Kota Ponorogo'],
            ['nama' => 'Dinas Kabupaten Ponorogo'],
            ['nama' => 'Dinas Kota Sampang'],
            ['nama' => 'Dinas Kabupaten Sampang'],
            ['nama' => 'Dinas Kota Sumenep'],
            ['nama' => 'Dinas Kabupaten Sumenep'],
            ['nama' => 'Dinas Kota Bangkalan'],
            ['nama' => 'Dinas Kabupaten Bangkalan'],
            ['nama' => 'Dinas Kota Banyuwangi'],
            ['nama' => 'Dinas Kabupaten Banyuwangi'],
            ['nama' => 'Dinas Kota Bondowoso'],
            ['nama' => 'Dinas Kabupaten Bondowoso'],
            ['nama' => 'Dinas Kota Situbondo'],
            ['nama' => 'Dinas Kabupaten Situbondo'],
            ['nama' => 'Dinas Kota Tuban'],
            ['nama' => 'Dinas Kabupaten Tuban'],
        ];

        DB::table($table)->insert($data);
    }
}
