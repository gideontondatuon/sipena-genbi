<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Periode;
use App\Models\AkunInstagram;
use App\Models\TargetHarian;
use App\Models\Laporan;
use App\Models\Notifikasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $admin = User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@genbi.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $user1 = User::create([
            'name' => 'Gideon Tondatuon',
            'email' => 'gideon@email.com',
            'password' => Hash::make('password'),
            'role' => 'anggota',
            'status' => 'aktif',
            'phone' => '081234567890',
            'nim' => '21021101',
        ]);

        $user2 = User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi@email.com',
            'password' => Hash::make('password'),
            'role' => 'anggota',
            'status' => 'aktif',
            'phone' => '081234567891',
            'nim' => '21021102',
        ]);

        $user3 = User::create([
            'name' => 'Sari Wulandari',
            'email' => 'sari@email.com',
            'password' => Hash::make('password'),
            'role' => 'anggota',
            'status' => 'aktif',
            'phone' => '081234567892',
            'nim' => '21021103',
        ]);

        // 2. Periodes
        $periodeAktif = Periode::create([
            'nama_periode' => 'Minggu 4 Juni 2026',
            'tanggal_mulai' => '2026-06-23',
            'tanggal_selesai' => '2026-06-29',
            'status' => 'aktif',
        ]);

        $periodeArsip = Periode::create([
            'nama_periode' => 'Minggu 3 Juni 2026',
            'tanggal_mulai' => '2026-06-16',
            'tanggal_selesai' => '2026-06-22',
            'status' => 'arsip',
        ]);

        // 3. Akun Instagram
        $ig1 = AkunInstagram::create([
            'nama_akun' => 'BI Sulut',
            'username' => '@bi_sulut',
            'keterangan' => 'Akun resmi Bank Indonesia Sulawesi Utara',
            'status' => 'aktif',
        ]);

        $ig2 = AkunInstagram::create([
            'nama_akun' => 'GenBI Indonesia',
            'username' => '@genbi_indonesia',
            'keterangan' => 'Akun pusat Generasi Baru Indonesia',
            'status' => 'aktif',
        ]);

        $ig3 = AkunInstagram::create([
            'nama_akun' => 'QRIS Indonesia',
            'username' => '@qris.id',
            'keterangan' => 'Edukasi dan sosialisasi QRIS',
            'status' => 'aktif',
        ]);

        $ig4 = AkunInstagram::create([
            'nama_akun' => 'Bank Indonesia',
            'username' => '@bank_indonesia',
            'keterangan' => 'Akun utama Bank Indonesia',
            'status' => 'aktif',
        ]);

        $ig5 = AkunInstagram::create([
            'nama_akun' => 'GenBI Sulut',
            'username' => '@genbi_sulut',
            'keterangan' => 'Akun GenBI Wilayah Sulut',
            'status' => 'aktif',
        ]);

        // 4. Target Harian
        $t1 = TargetHarian::create([
            'akun_instagram_id' => $ig1->id,
            'periode_id' => $periodeAktif->id,
            'tanggal' => '2026-06-26',
            'jumlah_target' => 5,
            'deadline' => '2026-06-28 23:59:00',
            'keterangan' => 'Wajib like, komen, dan share 5 postingan terbaru',
        ]);

        $t2 = TargetHarian::create([
            'akun_instagram_id' => $ig2->id,
            'periode_id' => $periodeAktif->id,
            'tanggal' => '2026-06-26',
            'jumlah_target' => 3,
            'deadline' => '2026-06-28 23:59:00',
            'keterangan' => 'Wajib like, komen, dan share 3 postingan tentang beasiswa',
        ]);

        $t3 = TargetHarian::create([
            'akun_instagram_id' => $ig3->id,
            'periode_id' => $periodeAktif->id,
            'tanggal' => '2026-06-27',
            'jumlah_target' => 4,
            'deadline' => '2026-06-29 23:59:00',
            'keterangan' => 'Wajib like, komen, dan share postingan Pekan QRIS',
        ]);

        // 5. Laporans
        Laporan::create([
            'user_id' => $user1->id,
            'akun_instagram_id' => $ig1->id,
            'target_harian_id' => $t1->id,
            'tanggal_postingan' => '2026-06-26',
            'bukti_like' => null,
            'bukti_komen' => null,
            'bukti_share' => null,
            'keterangan' => 'Sudah like, komen, dan share sesuai instruksi.',
            'status' => 'valid',
            'catatan_admin' => 'Laporan lengkap dan valid.',
        ]);

        Laporan::create([
            'user_id' => $user2->id,
            'akun_instagram_id' => $ig2->id,
            'target_harian_id' => $t2->id,
            'tanggal_postingan' => '2026-06-26',
            'bukti_like' => null,
            'bukti_komen' => null,
            'bukti_share' => null,
            'keterangan' => 'Sudah upload screenshot.',
            'status' => 'menunggu',
            'catatan_admin' => null,
        ]);

        Laporan::create([
            'user_id' => $user3->id,
            'akun_instagram_id' => $ig3->id,
            'target_harian_id' => $t3->id,
            'tanggal_postingan' => '2026-06-27',
            'bukti_like' => null,
            'bukti_komen' => null,
            'bukti_share' => null,
            'keterangan' => 'Laporan pertama.',
            'status' => 'ditolak',
            'catatan_admin' => 'Screenshot share belum sesuai.',
        ]);

        // 6. Notifikasi
        Notifikasi::create([
            'user_id' => null, // Global
            'judul' => 'Target baru ditambahkan',
            'pesan' => 'Admin menambahkan target untuk akun BI Sulut tanggal 27 Juni 2026 sebanyak 5 postingan.',
            'tipe' => 'target',
            'is_read' => false,
        ]);

        Notifikasi::create([
            'user_id' => $user2->id,
            'judul' => 'Laporan belum lengkap',
            'pesan' => 'Laporan GenBI Indonesia tanggal 26 Juni 2026 masih kurang 1 laporan.',
            'tipe' => 'warning',
            'is_read' => false,
        ]);

        Notifikasi::create([
            'user_id' => $user3->id,
            'judul' => 'Laporan ditolak',
            'pesan' => 'Laporan QRIS Indonesia ditolak karena screenshot share belum sesuai.',
            'tipe' => 'ditolak',
            'is_read' => false,
        ]);

        Notifikasi::create([
            'user_id' => $user1->id,
            'judul' => 'Laporan divalidasi',
            'pesan' => 'Laporan BI Sulut tanggal 26 Juni 2026 telah divalidasi oleh admin.',
            'tipe' => 'valid',
            'is_read' => true,
        ]);
    }
}
