<?php

namespace Database\Seeders;

use App\Models\FeedbackLink;
use App\Models\ImportantLink;
use App\Models\ImportantSection;
use App\Models\PasswordRecovery;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        $user = User::firstOrCreate(
            ['email' => 'bif@telkomuniversity.ac.id'],
            [
                'password_recovery_id' => 1,
                'password' => bcrypt('akunadmin')
            ]
        );

        // Password Recovery
        PasswordRecovery::firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_question' => "Pertanyaan pertama adalah?",
                'second_question' => "Pertanyaan kedua adalah?",
                'first_answer' => "jawaban satu",
                'second_answer' => "jawaban dua"
            ]
        );

        // Feedback Link
        FeedbackLink::firstOrCreate(
            ['id' => 1],
            [
                'link' => 'https://forms.office.com/pages/responsepage.aspx?id=D_6vkKPCCEG7mGzrTpTvFc9ujqZdH91MtXpfw-rWy2hUNFA5NUhUMlYwNU5RSE5TVDlWUzI1WUZTRi4u'
            ]
        );

        // Default Tags
        $tagDefault = Tag::firstOrCreate(
            ['name' => 'S1 Informatika'],
            ['description' => 'Tag utama untuk seluruh informasi resmi Program Studi S1 Informatika Telkom University.']
        );

        $tagAkademik = Tag::firstOrCreate(
            ['name' => 'Akademik'],
            ['description' => 'Informasi seputar kalender akademik, registrasi mata kuliah, jadwal ujian, dan perkuliahan.']
        );

        $tagMbkm = Tag::firstOrCreate(
            ['name' => 'MBKM'],
            ['description' => 'Program Merdeka Belajar Kampus Merdeka, magang bersertifikat, studi independen, dan pertukaran mahasiswa.']
        );

        $tagTa = Tag::firstOrCreate(
            ['name' => 'Tugas Akhir'],
            ['description' => 'Panduan pengajuan proposal, bimbingan, seminar proposal, dan sidang yudisium Tugas Akhir.']
        );

        $tagKemahasiswaan = Tag::firstOrCreate(
            ['name' => 'Kemahasiswaan'],
            ['description' => 'Kegiatan himpunan, organisasi mahasiswa, beasiswa, dan pengembangan soft skills.']
        );

        $tagLomba = Tag::firstOrCreate(
            ['name' => 'Kompetisi & Prestasi'],
            ['description' => 'Informasi lomba tingkat nasional & internasional, hackathon, dan pendanaan karya inovasi.']
        );

        // Sample Posts if none exist
        if (Post::count() == 0) {
            $post1 = Post::create([
                'title' => 'Panduan Registrasi Mata Kuliah dan Pengisian FRS Semester Ganjil',
                'subtitle' => 'Petunjuk teknis pengisian FRS, jadwal pembimbingan akademik, dan batas akhir persetujuan dosen wali.',
                'body' => '<p>Diberitahukan kepada seluruh mahasiswa Program Studi Sarjana Informatika bahwa periode registrasi mata kuliah dan pengisian Formulir Rencana Studi (FRS) telah dibuka. Mahasiswa diwajibkan untuk memperhatikan alur dan syarat berikut:</p><ul><li>Melakukan konsultasi dengan dosen wali masing-masing sebelum memilih mata kuliah pilihan.</li><li>Memastikan tidak ada prasyarat mata kuliah yang belum terpenuhi.</li><li>Menyelesaikan pembayaran BPP tepat waktu sesuai batas yang ditentukan di portal akademik.</li></ul><p>Informasi lebih lanjut dapat menghubungi bagian layanan akademik Program Studi.</p>',
                'image' => 'images/DummyImage.png'
            ]);
            PostTag::create(['post_id' => $post1->id, 'tag_id' => $tagDefault->id]);
            PostTag::create(['post_id' => $post1->id, 'tag_id' => $tagAkademik->id]);

            $post2 = Post::create([
                'title' => 'Sosialisasi Program Magang & Studi Independen Bersertifikat (MSIB)',
                'subtitle' => 'Peluang konversi 20 SKS untuk program magang industri dan studi independen di perusahaan mitra.',
                'body' => '<p>Program Studi S1 Informatika memfasilitasi mahasiswa yang ingin mengikuti program MSIB Kemendikbudristek. Mahasiswa minimal semester 5 dengan IPK di atas 3.00 berkesempatan magang di berbagai perusahaan teknologi terkemuka.</p><p>Pendaftaran dan surat rekomendasi prodi dapat diakses melalui portal layanan terpadu sebelum batas akhir pengumpulan berkas.</p>',
                'image' => 'images/DummyImage.png'
            ]);
            PostTag::create(['post_id' => $post2->id, 'tag_id' => $tagDefault->id]);
            PostTag::create(['post_id' => $post2->id, 'tag_id' => $tagMbkm->id]);

            $post3 = Post::create([
                'title' => 'Jadwal Pendaftaran Seminar Proposal dan Sidang Tugas Akhir Periode Terbaru',
                'subtitle' => 'Ketentuan berkas kelengkapan, format penulisan dokumen, dan mekanisme pelaksanaan sidang.',
                'body' => '<p>Pendaftaran sidang tugas akhir dan seminar proposal telah dibuka. Mahasiswa yang telah memenuhi syarat bimbingan minimum dan disetujui oleh kedua pembimbing diharapkan segera mengunggah naskah dan syarat administrasi pada sistem informasi tugas akhir.</p><p>Pastikan cek plagiarisme dengan Turnitin di bawah batas toleransi 20%.</p>',
                'image' => 'images/DummyImage.png'
            ]);
            PostTag::create(['post_id' => $post3->id, 'tag_id' => $tagDefault->id]);
            PostTag::create(['post_id' => $post3->id, 'tag_id' => $tagTa->id]);

            $post4 = Post::create([
                'title' => 'Open Call: Kompetisi Inovasi Teknologi dan Hackathon Nasional',
                'subtitle' => 'Dukungan pendanaan dan bimbingan dosen untuk tim mahasiswa yang mengikuti kompetisi bergengsi.',
                'body' => '<p>Prodi mendukung penuh mahasiswa S1 Informatika yang ingin berkompetisi di tingkat nasional maupun internasional dalam bidang Artificial Intelligence, Cyber Security, Software Engineering, dan Data Science. Fasilitas bimbingan intensif dan insentif prestasi telah disiapkan.</p>',
                'image' => 'images/DummyImage.png'
            ]);
            PostTag::create(['post_id' => $post4->id, 'tag_id' => $tagDefault->id]);
            PostTag::create(['post_id' => $post4->id, 'tag_id' => $tagLomba->id]);
            PostTag::create(['post_id' => $post4->id, 'tag_id' => $tagKemahasiswaan->id]);
        }

        // Sample Important Sections and Links if none exist
        if (ImportantSection::count() == 0) {
            $secMbkm = ImportantSection::create([
                'name' => 'Kumpulan Link MBKM',
                'order_number' => 1
            ]);
            ImportantLink::create([
                'important_section_id' => $secMbkm->id,
                'name' => 'Portal Resmi Kampus Merdeka Kemendikbud',
                'link' => 'https://kampusmerdeka.kemdikbud.go.id'
            ]);
            ImportantLink::create([
                'important_section_id' => $secMbkm->id,
                'name' => 'Form Pengajuan Surat Rekomendasi MBKM Prodi IF',
                'link' => 'https://bit.ly/RekomendasiMBKM-IF'
            ]);
            ImportantLink::create([
                'important_section_id' => $secMbkm->id,
                'name' => 'Panduan Konversi SKS Magang & Studi Independen',
                'link' => 'https://bit.ly/PanduanKonversiMBKM-IF'
            ]);

            $secKuliah = ImportantSection::create([
                'name' => 'Kumpulan Link Kelas Perkuliahan & LMS',
                'order_number' => 2
            ]);
            ImportantLink::create([
                'important_section_id' => $secKuliah->id,
                'name' => 'CeLOE Learning Management System (LMS Tel-U)',
                'link' => 'https://celoe.telkomuniversity.ac.id'
            ]);
            ImportantLink::create([
                'important_section_id' => $secKuliah->id,
                'name' => 'i-Gracias Telkom University Portal',
                'link' => 'https://igracias.telkomuniversity.ac.id'
            ]);
            ImportantLink::create([
                'important_section_id' => $secKuliah->id,
                'name' => 'Silabus dan Rencana Pembelajaran Semester (RPS)',
                'link' => 'https://bit.ly/RPS-S1Informatika'
            ]);

            $secTa = ImportantSection::create([
                'name' => 'Kumpulan Link Tugas Akhir & Proposal',
                'order_number' => 3
            ]);
            ImportantLink::create([
                'important_section_id' => $secTa->id,
                'name' => 'Sistem Pendaftaran & Monitoring Tugas Akhir',
                'link' => 'https://sit-v2.telkomuniversity.ac.id'
            ]);
            ImportantLink::create([
                'important_section_id' => $secTa->id,
                'name' => 'Template Naskah & Buku Panduan Tugas Akhir IF',
                'link' => 'https://bit.ly/TemplateTA-Informatika'
            ]);
            ImportantLink::create([
                'important_section_id' => $secTa->id,
                'name' => 'Jadwal Pendaftaran Sidang Periode Berjalan',
                'link' => 'https://bit.ly/JadwalSidangTA-IF'
            ]);

            $secLayanan = ImportantSection::create([
                'name' => 'Layanan & Dokumen Akademik',
                'order_number' => 4
            ]);
            ImportantLink::create([
                'important_section_id' => $secLayanan->id,
                'name' => 'Layanan Surat Keterangan Aktif Kuliah',
                'link' => 'https://igracias.telkomuniversity.ac.id'
            ]);
            ImportantLink::create([
                'important_section_id' => $secLayanan->id,
                'name' => 'Helpdesk Layanan Terpadu Fakultas Informatika',
                'link' => 'https://fif.telkomuniversity.ac.id/helpdesk'
            ]);
        }
    }
}
