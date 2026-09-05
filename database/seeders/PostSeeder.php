<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [
            'Panduan Teknis Seminar Internal Onsite Mahasiswa Prodi S-1 Informatika',
            'Kerja Praktik 2026',
            'Timeline Sidang TA dan Yudisium Genap 2526 Prodi S-1 Informatika',
            'Magang Mandiri Fakultas Informatika',
            'Pengumuman Informatika untuk Masyarakat',
            'Informasi Final Registrasi & Perubahan Rencana Studi',
            'Update Informasi Registrasi S-1 IF Genap TA 2025/26',
            'Informasi Umum Kerja Praktik S-1 Informatika',
            '[Perpanjangan] Pengajuan Penundaan Pembayaran TEL-U CARE',
            'Informasi Registrasi, Pembayaran, Undur Diri, Cuti, Aktivasi Mahasiswa Semester Genap 2526',
            'Informasi Jadwal Pembayaran dan Tata Cara Pembayaran pada Sistem Baru',
            'Registrasi Bayangan MK Pilihan S-1 IF Semester Genap TA 2526',
            'Informasi Pemindahan Ruang Kelas Kuliah Prodi S-1 IF (Sementara)',
            'Update Informasi Registrasi S-1 IF Ganjil TA 2025/26',
            'PENGUMPULAN LAPORAN AKHIR IUM PRODI S-1 IF',
            'Topic Shopping Semester Genap TA 2024/25',
            'HASIL REGISTRASI BAYANGAN MK PILIHAN S1 INFORMATIKA SEMESTER GENAP 2024/25',
            'REGISTRASI BAYANGAN MK PILIHAN PRODI S1 INFORMATIKA',
            'Informasi PRS Ganjil 2024/25',
            'HASIL REGISTRASI BAYANGAN MK PROPOSAL S1 IF GANJIL TA 2024/2025',
            'REGISTRASI BAYANGAN MK PROPOSAL S1 IF GANJIL TA 2024/2025',
            'PENGUMPULAN LAPORAN AKHIR IUM',
            'Predikat Kelulusan Sarjana',
            'Plotting Dosen Pembimbing Akademik MK IuM',
            'Sidang Tugas Akhir S1 IF',
            'Perubahan Masa Studi (PRS) Genap TA 2023/2024',
            'KEBIJAKAN REGISTRASI GENAP TA 23/24',
            'Hasil Registrasi Bayangan Genap 2023/2024',
            'Panduan Tugas Akhir',
            'Timeline Tugas Akhir',
        ];

        foreach ($titles as $index => $title) {
            Post::updateOrCreate(
                ['title' => $title],
                [
                    'subtitle' => $title,
                    'body' => "<p>{$title}</p>",
                    'image' => $index % 2 === 0 ? 'images/placeholder.png' : null,
                ]
            );
        }
    }
}