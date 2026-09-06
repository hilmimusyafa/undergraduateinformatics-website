<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class PostTagSeeder extends Seeder
{
    public function run(): void
    {
        $relations = [
            ['post' => 'Timeline Tugas Akhir', 'tags' => ['s1-informatika', 'tugas-akhir']],
            ['post' => 'Panduan Tugas Akhir', 'tags' => ['s1-informatika', 'tugas-akhir']],
            ['post' => 'Hasil Registrasi Bayangan Genap 2023/2024', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'KEBIJAKAN REGISTRASI GENAP TA 23/24', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'Perubahan Masa Studi (PRS) Genap TA 2023/2024', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'Plotting Dosen Pembimbing Akademik MK IuM', 'tags' => ['s1-informatika', 'ium']],
            ['post' => 'Sidang Tugas Akhir S1 IF', 'tags' => ['s1-informatika', 'tugas-akhir', 'sidang']],
            ['post' => 'Predikat Kelulusan Sarjana', 'tags' => ['s1-informatika', 'tugas-akhir', 'sidang']],
            ['post' => 'PENGUMPULAN LAPORAN AKHIR IUM', 'tags' => ['s1-informatika', 'ium']],
            ['post' => 'REGISTRASI BAYANGAN MK PROPOSAL S1 IF GANJIL TA 2024/2025', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'HASIL REGISTRASI BAYANGAN MK PROPOSAL S1 IF GANJIL TA 2024/2025', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'Informasi PRS Ganjil 2024/25', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'REGISTRASI BAYANGAN MK PILIHAN PRODI S1 INFORMATIKA', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'HASIL REGISTRASI BAYANGAN MK PILIHAN S1 INFORMATIKA', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'Topic Shopping Semester Genap TA 2024/25', 'tags' => ['s1-informatika', 'tugas-akhir', 'registrasi-semester']],
            ['post' => 'PENGUMPULAN LAPORAN AKHIR IUM PRODI S-1 IF', 'tags' => ['s1-informatika', 'ium']],
            ['post' => 'Update Informasi Registrasi S-1 IF Ganjil TA 2025/26', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'Informasi Pemindahan Ruang Kelas Kuliah Prodi S-1 IF (Sementara)', 'tags' => ['s1-informatika', 'perkuliahan']],
            ['post' => 'Registrasi Bayangan MK Pilihan S-1 IF', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'Informasi Registrasi, Pembayaran, Undur Diri, Cuti, Aktivasi Mahasiswa Semester Genap 2526', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => '[Perpanjangan] Pengajuan Penundaan Pembayaran TEL-U CARE', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'Informasi Jadwal Pembayaran dan Tata Cara Pembayaran pada Sistem Baru', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'Update Informasi Registrasi S-1 IF Genap TA 2025/26', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'Informasi Umum Kerja Praktik S-1 Informatika', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'Informasi Final Registrasi & Perubahan Rencana Studi', 'tags' => ['s1-informatika', 'registrasi-semester']],
            ['post' => 'Pengumuman Informatika untuk Masyarakat', 'tags' => ['s1-informatika', 'ium', 'perkuliahan']],
            ['post' => 'Magang Mandiri Fakultas Informatika', 'tags' => ['s1-informatika', 'perkuliahan']],
            ['post' => 'Timeline Sidang TA dan Yudisium Genap 2526 Prodi S-1 Informatika', 'tags' => ['s1-informatika', 'tugas-akhir', 'sidang']],
            ['post' => 'Kerja Praktik 2026', 'tags' => ['s1-informatika', 'perkuliahan']],
            ['post' => 'Panduan Teknis Seminar Internal Onsite Mahasiswa Prodi S-1 Informatika', 'tags' => ['s1-informatika', 'tugas-akhir']],
        ];

        foreach ($relations as $relation) {
            $post = Post::where('title', $relation['post'])->first();

            if ($post === null) {
                continue;
            }

            $tagIds = Tag::whereIn('slug', $relation['tags'])->pluck('id')->all();

            foreach ($tagIds as $tagId) {
                PostTag::firstOrCreate(
                    ['post_id' => $post->id, 'tag_id' => $tagId]
                );
            }
        }
    }
}
