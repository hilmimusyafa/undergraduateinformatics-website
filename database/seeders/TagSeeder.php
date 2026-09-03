<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'S1 Informatika' => 'Informasi resmi Program Studi Sarjana Informatika Telkom University',
            'Beasiswa' => 'Informasi beasiswa dalam dan luar negeri untuk mahasiswa',
            'Akademik' => 'Pengumuman akademik dan jadwal perkuliahan',
            'MBKM' => 'Informasi program Merdeka Belajar Kampus Merdeka',
            'Kemahasiswaan' => 'Kegiatan kemahasiswaan dan organisasi mahasiswa',
            'Tugas Akhir' => 'Informasi terkait tugas akhir mahasiswa',
            'Pertukaran Mahasiswa' => 'Informasi program pertukaran mahasiswa',
        ];

        $tagIds = [];
        foreach ($tags as $name => $description) {
            $tag = Tag::firstOrCreate(['name' => $name], ['description' => $description]);
            $tagIds[$name] = $tag->id;
        }

        $posts = [
            [
                'title' => 'Pendaftaran Beasiswa Telkom University 2026',
                'subtitle' => 'Beasiswa prestasi dan beasiswa kebutuhan untuk mahasiswa aktif',
                'body' => '<p>Pendaftaran beasiswa Telkom University periode 2026 dibuka mulai 1 September 2026. Beasiswa mencakup beasiswa prestasi akademik dan beasiswa kebutuhan.</p><p>Mahasiswa dapat mengajukan melalui formulir yang tersedia di laman resmi kemahasiswaan.</p>',
                'tags' => ['Beasiswa', 'S1 Informatika'],
            ],
            [
                'title' => 'Jadwal Perkuliahan Semester Ganjil 2026/2027',
                'subtitle' => 'Jadwal resmi perkuliahan semester ganjil tahun akademik 2026/2027',
                'body' => '<p>Jadwal perkuliahan semester ganjil tahun akademik 2026/2027 telah diterbitkan. Mahasiswa dapat mengunduh jadwal melalui sistem informasi akademik.</p><p>Perkuliahan dimulai pada minggu kedua bulan September 2026.</p>',
                'tags' => ['Akademik', 'S1 Informatika'],
            ],
            [
                'title' => 'Pendaftaran Program MBKM Batch 5',
                'subtitle' => 'Program Merdeka Belajar Kampus Merdeka batch kelima dibuka',
                'body' => '<p>Program Merdeka Belajar Kampus Merdeka batch 5 resmi dibuka untuk mahasiswa semester lima ke atas.</p><p>Pendaftaran dilakukan melalui portal MBKM nasional sebelum tenggat yang tertera.</p>',
                'tags' => ['MBKM', 'S1 Informatika'],
            ],
            [
                'title' => 'Webinar Persiapan Tugas Akhir',
                'subtitle' => 'Webinar tata cara dan alur pelaksanaan tugas akhir',
                'body' => '<p>Webinar persiapan tugas akhir akan dilaksanakan secara daring pada akhir September 2026.</p><p>Materi mencakup alur pengajuan judul, pembimbingan, hingga sidang tugas akhir.</p>',
                'tags' => ['Tugas Akhir', 'S1 Informatika'],
            ],
            [
                'title' => 'Rekrutmen Panitia Kegiatan PKKMB 2026',
                'subtitle' => 'Pendaftaran panitia Pengenalan Kehidupan Kampus Mahasiswa Baru',
                'body' => '<p>Rekrutmen panitia PKKMB 2026 dibuka untuk mahasiswa aktif seluruh angkatan.</p><p>Pendaftaran dapat dilakukan melalui formulir yang dibagikan oleh himpunan mahasiswa.</p>',
                'tags' => ['Kemahasiswaan', 'S1 Informatika'],
            ],
            [
                'title' => 'Pedoman Akademik Sarjana Informatika',
                'subtitle' => 'Dokumen pedoman akademik program studi Sarjana Informatika',
                'body' => '<p>Pedoman akademik program studi Sarjana Informatika telah diperbarui untuk tahun akademik 2026/2027.</p><p>Pedoman memuat kurikulum, aturan perkuliahan, dan ketentuan kelulusan.</p>',
                'tags' => ['Akademik', 'S1 Informatika'],
            ],
        ];

        foreach ($posts as $postData) {
            $post = Post::firstOrCreate(
                ['title' => $postData['title']],
                [
                    'subtitle' => $postData['subtitle'],
                    'body' => $postData['body'],
                    'image' => 'images/placeholder.png',
                ]
            );
            $post->tags()->syncWithoutDetaching(
                array_map(fn (string $name) => $tagIds[$name], $postData['tags'])
            );
        }
    }
}
