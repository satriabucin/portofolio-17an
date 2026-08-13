<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyData100Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $lombas = \App\Models\Lomba::all();

        if ($lombas->isEmpty()) {
            $this->command->error("Tidak ada data Lomba! Buat lomba terlebih dahulu.");
            return;
        }

        $this->command->info("Membuat 100 data pendaftar (Disetujui)...");

        for ($i = 0; $i < 100; $i++) {
            $pendaftar = \App\Models\Pendaftar::create([
                'nama' => $faker->name,
                'blok_rumah' => 'Blok ' . $faker->randomElement(['A', 'B', 'C', 'D', 'E']) . '-' . rand(1, 20),
                'rt' => '0' . rand(1, 5),
                'no_hp' => $faker->phoneNumber,
                'tahun_acara' => date('Y'),
                'status_verifikasi' => 'Disetujui',
                'catatan_admin' => 'Generate otomatis dari Seeder'
            ]);

            // Assign ke 1-3 lomba secara acak
            $randomLombas = $lombas->random(rand(1, 3))->pluck('id');
            $pendaftar->lombas()->attach($randomLombas);
        }

        $this->command->info("100 pendaftar berhasil ditambahkan ke database!");
    }
}
