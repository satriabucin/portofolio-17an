
use App\Models\Lomba;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;

$lomba1 = Lomba::create(['nama_lomba' => 'Balap Karung', 'kategori_usia' => 'Anak-anak & Dewasa', 'deskripsi' => 'Adu cepat menggunakan karung. Hadiah menarik menanti!']);
$lomba2 = Lomba::create(['nama_lomba' => 'Makan Kerupuk', 'kategori_usia' => 'Anak-anak', 'deskripsi' => 'Siapa yang paling cepat menghabiskan kerupuk tanpa tangan?']);
$lomba3 = Lomba::create(['nama_lomba' => 'Tarik Tambang', 'kategori_usia' => 'Dewasa (Bapak-bapak)', 'deskripsi' => 'Adu kekuatan antar RT, buktikan RT kalian paling kuat!']);
$lomba4 = Lomba::create(['nama_lomba' => 'Panjat Pinang', 'kategori_usia' => 'Dewasa', 'deskripsi' => 'Gotong royong mencapai puncak untuk memperebutkan hadiah utama.']);

$p1 = Pendaftar::create(['nama' => 'Budi Santoso', 'blok_rumah' => 'Blok A No. 12', 'rt' => 'RT 01', 'no_hp' => '081234567890', 'tahun_acara' => '2026', 'status_verifikasi' => 'Disetujui', 'catatan_admin' => 'Data valid']);
$p2 = Pendaftar::create(['nama' => 'Siti Aminah', 'blok_rumah' => 'Blok B No. 5', 'rt' => 'RT 02', 'no_hp' => '089876543210', 'tahun_acara' => '2026', 'status_verifikasi' => 'Menunggu Verifikasi', 'catatan_admin' => null]);
$p3 = Pendaftar::create(['nama' => 'Agus Yudhoyono', 'blok_rumah' => 'Blok C No. 9', 'rt' => 'RT 03', 'no_hp' => '085612345678', 'tahun_acara' => '2026', 'status_verifikasi' => 'Disetujui', 'catatan_admin' => 'Telah dikonfirmasi via WA']);

DB::table('pendaftar_lomba')->insert([
    ['id_pendaftar' => $p1->id, 'id_lomba' => $lomba1->id, 'created_at' => now(), 'updated_at' => now()],
    ['id_pendaftar' => $p1->id, 'id_lomba' => $lomba3->id, 'created_at' => now(), 'updated_at' => now()],
    ['id_pendaftar' => $p2->id, 'id_lomba' => $lomba2->id, 'created_at' => now(), 'updated_at' => now()],
    ['id_pendaftar' => $p3->id, 'id_lomba' => $lomba3->id, 'created_at' => now(), 'updated_at' => now()],
    ['id_pendaftar' => $p3->id, 'id_lomba' => $lomba4->id, 'created_at' => now(), 'updated_at' => now()],
]);
echo "Dummy data added.\n";
