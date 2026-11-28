<?php

namespace Database\Seeders;

use App\Models\Bid;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $pw = [
            'admin' => Hash::make('admin123'),
            'klien' => Hash::make('klien123'),
            'developer' => Hash::make('dev123'),
        ];

        $users = [
            [1, 'Admin Garuda', 'admin@example.com', 'admin'],
            [2, 'Budi Santoso', 'budi@example.com', 'klien'],
            [3, 'Siti Rahayu', 'siti@example.com', 'klien'],
            [4, 'Agus Wijaya', 'agus@example.com', 'klien'],
            [5, 'Dewi Lestari', 'dewi@example.com', 'developer'],
            [6, 'Eko Prasetyo', 'eko@example.com', 'developer'],
            [7, 'Fitri Handayani', 'fitri@example.com', 'developer'],
            [8, 'Gilang Ramadhan', 'gilang@example.com', 'developer'],
        ];
        foreach ($users as [$id, $nama, $email, $role]) {
            User::forceCreate(['id' => $id, 'nama' => $nama, 'email' => $email, 'password' => $pw[$role], 'role' => $role]);
        }

        $projects = [
            [1, 2, 'Marketplace Hasil Tani', 'Petani bisa menjual sayur dan buah langsung ke pembeli, lengkap dengan ongkir per kecamatan.', 'Web', 8000000, 16000000, '2027-03-15', 'terbuka'],
            [2, 3, 'Aplikasi Tabungan Siswa', 'Aplikasi pencatatan tabungan siswa untuk wali kelas, dengan notifikasi saldo ke orang tua.', 'Mobile', 6000000, 12000000, '2027-02-20', 'terbuka'],
            [3, 4, 'Ilustrasi Buku Cerita Anak', 'Dua belas ilustrasi berwarna untuk buku cerita rakyat, format siap cetak A5.', 'Desain', 2500000, 5000000, '2026-10-01', 'ditutup'],
            [4, 2, 'Analisis Ulasan Pelanggan', 'Mengolah ribuan ulasan marketplace menjadi ringkasan sentimen dan kata kunci keluhan.', 'Data', 5000000, 10000000, '2027-04-10', 'terbuka'],
            [5, 3, 'Deteksi Penyakit Daun Padi', 'Model klasifikasi gambar daun padi untuk membantu penyuluh pertanian di lapangan.', 'AI', 9000000, 18000000, '2027-01-25', 'terbuka'],
            [6, 4, 'Sistem Inventaris Lab Komputer', 'Pencatatan peminjaman alat lab, status perbaikan, dan laporan aset per semester.', 'Web', 3500000, 7000000, '2026-09-15', 'ditutup'],
            [7, 2, 'Aplikasi Pemesanan Katering', 'Pemesanan katering harian dengan pilihan menu mingguan dan pembayaran transfer.', 'Mobile', 7000000, 14000000, '2026-08-20', 'ditutup'],
            [8, 3, 'Website Undangan Pernikahan', 'Undangan digital dengan RSVP, peta lokasi, dan galeri foto prewedding.', 'Web', 800000, 2000000, '2027-05-30', 'terbuka'],
        ];
        foreach ($projects as [$id, $client, $judul, $desk, $kat, $min, $max, $batas, $status]) {
            Project::forceCreate([
                'id' => $id, 'client_id' => $client, 'judul' => $judul, 'deskripsi' => $desk, 'kategori' => $kat,
                'budget_min' => $min, 'budget_max' => $max, 'batas_lelang' => $batas, 'status' => $status,
            ]);
        }

        $bids = [
            [1, 1, 5, 12000000, 45, 'Laravel dan MySQL, termasuk panel petani dan verifikasi pesanan.', false],
            [2, 1, 6, 10000000, 50, 'Pernah membuat toko online UMKM, bisa integrasi ongkir otomatis.', false],
            [3, 1, 8, 14500000, 40, 'Termasuk fitur chat penjual dan pembeli.', false],
            [4, 2, 7, 9000000, 35, 'Flutter dengan notifikasi WhatsApp ke orang tua siswa.', false],
            [5, 2, 5, 11000000, 30, 'Kotlin native, data tersimpan aman di server sekolah.', false],
            [6, 3, 7, 4000000, 21, 'Gaya ilustrasi cat air, sketsa dikirim tiap minggu untuk direview.', true],
            [7, 3, 6, 3200000, 28, 'Ilustrasi digital vektor, mudah diubah ukurannya.', false],
            [8, 4, 8, 7500000, 25, 'Python dan NLP bahasa Indonesia, hasil berupa dashboard interaktif.', false],
            [9, 4, 6, 6000000, 30, 'Termasuk laporan PDF ringkasan keluhan per bulan.', false],
            [10, 5, 8, 15000000, 60, 'CNN dengan dataset lapangan, bisa dipakai offline di ponsel.', false],
            [11, 6, 5, 5000000, 20, 'Laravel dengan QR code di setiap alat lab.', true],
            [12, 6, 8, 6500000, 18, 'Termasuk pengingat otomatis untuk peminjaman yang terlambat.', false],
            [13, 7, 6, 10500000, 40, 'Flutter dengan panel dapur untuk rekap pesanan harian.', true],
            [14, 7, 7, 12000000, 35, 'Termasuk fitur langganan menu bulanan.', false],
            [15, 8, 5, 1500000, 7, 'Template elegan, bisa dibagikan lewat link khusus tiap tamu.', false],
        ];
        foreach ($bids as [$id, $project, $dev, $harga, $durasi, $proposal, $win]) {
            Bid::forceCreate([
                'id' => $id, 'project_id' => $project, 'developer_id' => $dev, 'harga' => $harga,
                'durasi' => $durasi, 'proposal' => $proposal, 'is_winner' => $win,
            ]);
        }

        if (DB::getDriverName() === 'pgsql') {
            foreach (['users', 'projects', 'bids'] as $table) {
                DB::statement("SELECT setval(pg_get_serial_sequence('$table', 'id'), (SELECT MAX(id) FROM $table))");
            }
        }
    }
}
