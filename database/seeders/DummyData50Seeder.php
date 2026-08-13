<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyData50Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $lombaIds = \App\Models\Lomba::pluck('id')->toArray();
        
        if (empty($lombaIds)) {
            echo "Lomba kosong, harap isi lomba dulu.\n";
            return;
        }

        $statuses = ['Menunggu Verifikasi', 'Disetujui', 'Disetujui', 'Ditolak']; // Bobot disetujui lebih banyak

        for ($i = 0; $i < 50; $i++) {
            $status = $faker->randomElement($statuses);
            
            $p = \App\Models\Pendaftar::create([
                'nama' => $faker->name,
                'blok_rumah' => 'Blok ' . $faker->randomElement(['A', 'B', 'C', 'D', 'E']) . ' No. ' . $faker->numberBetween(1, 40),
                'rt' => 'RT ' . str_pad($faker->numberBetween(1, 10), 2, '0', STR_PAD_LEFT),
                'no_hp' => '08' . $faker->randomNumber(8, true) . $faker->randomNumber(2, true),
                'tahun_acara' => '2026',
                'status_verifikasi' => $status,
                'catatan_admin' => $status == 'Ditolak' ? $faker->sentence() : null,
                'created_at' => $faker->dateTimeBetween('-1 week', 'now'),
                'updated_at' => now(),
            ]);

            $numberOfLomba = $faker->numberBetween(1, 3);
            $selectedLombaIds = $faker->randomElements($lombaIds, $numberOfLomba);

            foreach ($selectedLombaIds as $lombaId) {
                \Illuminate\Support\Facades\DB::table('pendaftar_lomba')->insert([
                    'id_pendaftar' => $p->id,
                    'id_lomba' => $lombaId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
