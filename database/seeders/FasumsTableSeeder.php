<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FasumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('fasums')->insert([
            [
                'nama' => 'Taman Kota Bobi',
                'luas' => 5000, // In square meters
                'kondisi' => 'Baik',
                'dinas_terkait' => 44,
                'asal_fasilitas' => 'Swasta',
                'lat' => 598,
                'long' => 126,
                'image_path' => 'https://asset.kompas.com/crops/UwZ4WmjzrQAcPiYq4FAbSJPyjHA=/14x0:870x571/1200x800/data/photo/2021/12/03/61a9a2da0590b.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Lapangan Olahraga Ihiy',
                'luas' => 3000, // In square meters
                'kondisi' => 'Baik',
                'dinas_terkait' => 44,
                'asal_fasilitas' => 'Swasta',
                'lat' => 897,
                'long' => 109,
                'image_path' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRjNJKZ3zSfRr7nCPbeDbhLrims_EHyoKHTqA&s',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Bus Stop Halte A',
                'luas' => 8000, // In square meters
                'kondisi' => 'Baik',
                'dinas_terkait' => 44,
                'asal_fasilitas' => 'APBN',
                'lat' => 123,
                'long' => 106,
                'image_path' => 'https://www.samtrans.com/files/styles/small_100_1x/public/images/2023-11/edited%20bus%20stop%20pic.jpg?itok=0WLu-L73',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
