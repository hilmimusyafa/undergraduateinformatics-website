<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\FeedbackLink;
use App\Models\ImportantLink;
use App\Models\ImportantSection;
use App\Models\PasswordRecovery;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'email' => 'bif@telkomuniversity.ac.id',
            'password_recovery_id' => '1',
            'password' => bcrypt('akunadmin')
        ]);

        PasswordRecovery::create([
            'user_id' => '1',
            'first_question' => "Pertanyaan pertama adalah?",
            'second_question' => "Pertanyaan kedua adalah?",
            'first_answer' => "jawaban satu",
            'second_answer' => "jawaban dua"
        ]);

        // User::create([
        //     'email' => 'admin2@gmail.com',
        //     'password_recovery_id' => '2',
        //     'password' => bcrypt('akundummy')
        // ]);

        // PasswordRecovery::create([
        //     'user_id' => '2',
        //     'first_question' => "pertanyaan 1",
        //     'second_question' => "pertanyaan 2",
        //     'first_answer' => "jawaban 1",
        //     'second_answer' => "jawaban 2"
        // ]);

        // FeedbackLink::create([
        //     'link' => 'https://forms.office.com/pages/responsepage.aspx?id=D_6vkKPCCEG7mGzrTpTvFc9ujqZdH91MtXpfw-rWy2hUNFA5NUhUMlYwNU5RSE5TVDlWUzI1WUZTRi4u'
        // ]);

        $this->call(TagSeeder::class);

        // Post::create([
        //     'title' => 'Dummy Judul Artikel Proposal 1',
        //     'subtitle' => 'Dummy subtitle artikel proposal 1',
        //     'body' => 'Nullam nec tincidunt massa, sit amet dapibus diam. Etiam dictum elit mi, et condimentum metus luctus quis. Duis a ultrices orci. Nullam a odio condimentum, laoreet nunc vel, maximus turpis. Morbi convallis, eros placerat accumsan venenatis, mauris tellus luctus tellus, eu ultrices purus orci et eros. Suspendisse pharetra, ligula in consectetur luctus, libero neque viverra neque, aliquet tincidunt lectus ligula tristique felis. Integer mattis ultricies velit id tempus. Aenean fringilla libero sed lorem tempus bibendum. Aenean in dui ac orci auctor pharetra. Quisque faucibus lorem eget ante posuere placerat. Morbi et accumsan tortor.',
        //     'image' => 'images/placeholder.png'
        // ]);

        // PostTag::create([
        //     'post_id' => '1',
        //     'tag_id' => '1',
        // ]);

        // PostTag::create([
        //     'post_id' => '1',
        //     'tag_id' => '2',
        // ]);

        // PostTag::create([
        //     'post_id' => '1',
        //     'tag_id' => '3',
        // ]);

        // Post::create([
        //     'title' => 'Dummy Judul Artikel Proposal 2',
        //     'subtitle' => 'Dummy subtitle artikel proposal 2',
        //     'body' => 'Nullam nec tincidunt massa, sit amet dapibus diam. Etiam dictum elit mi, et condimentum metus luctus quis. Duis a ultrices orci. Nullam a odio condimentum, laoreet nunc vel, maximus turpis. Morbi convallis, eros placerat accumsan venenatis, mauris tellus luctus tellus, eu ultrices purus orci et eros. Suspendisse pharetra, ligula in consectetur luctus, libero neque viverra neque, aliquet tincidunt lectus ligula tristique felis. Integer mattis ultricies velit id tempus. Aenean fringilla libero sed lorem tempus bibendum. Aenean in dui ac orci auctor pharetra. Quisque faucibus lorem eget ante posuere placerat. Morbi et accumsan tortor.',
        //     'image' => 'images/placeholder.png'
        // ]);

        // PostTag::create([
        //     'post_id' => '2',
        //     'tag_id' => '1',
        // ]);

        // PostTag::create([
        //     'post_id' => '2',
        //     'tag_id' => '2',
        // ]);

        // PostTag::create([
        //     'post_id' => '2',
        //     'tag_id' => '3',
        // ]);

        // Post::create([
        //     'title' => 'Dummy Judul Artikel Proposal 3',
        //     'subtitle' => 'Dummy subtitle artikel proposal 3',
        //     'body' => 'Nullam nec tincidunt massa, sit amet dapibus diam. Etiam dictum elit mi, et condimentum metus luctus quis. Duis a ultrices orci. Nullam a odio condimentum, laoreet nunc vel, maximus turpis. Morbi convallis, eros placerat accumsan venenatis, mauris tellus luctus tellus, eu ultrices purus orci et eros. Suspendisse pharetra, ligula in consectetur luctus, libero neque viverra neque, aliquet tincidunt lectus ligula tristique felis. Integer mattis ultricies velit id tempus. Aenean fringilla libero sed lorem tempus bibendum. Aenean in dui ac orci auctor pharetra. Quisque faucibus lorem eget ante posuere placerat. Morbi et accumsan tortor.',
        //     'image' => 'images/placeholder.png'
        // ]);

        // PostTag::create([
        //     'post_id' => '3',
        //     'tag_id' => '1',
        // ]);

        // PostTag::create([
        //     'post_id' => '3',
        //     'tag_id' => '2',
        // ]);

        // PostTag::create([
        //     'post_id' => '3',
        //     'tag_id' => '3',
        // ]);
        
        // Post::create([
        //     'title' => 'Dummy Judul Artikel Proposal 4',
        //     'subtitle' => 'Dummy subtitle artikel proposal 4',
        //     'body' => 'Nullam nec tincidunt massa, sit amet dapibus diam. Etiam dictum elit mi, et condimentum metus luctus quis. Duis a ultrices orci. Nullam a odio condimentum, laoreet nunc vel, maximus turpis. Morbi convallis, eros placerat accumsan venenatis, mauris tellus luctus tellus, eu ultrices purus orci et eros. Suspendisse pharetra, ligula in consectetur luctus, libero neque viverra neque, aliquet tincidunt lectus ligula tristique felis. Integer mattis ultricies velit id tempus. Aenean fringilla libero sed lorem tempus bibendum. Aenean in dui ac orci auctor pharetra. Quisque faucibus lorem eget ante posuere placerat. Morbi et accumsan tortor.',
        //     'image' => 'images/placeholder.png'
        // ]);

        // PostTag::create([
        //     'post_id' => '4',
        //     'tag_id' => '1',
        // ]);

        // PostTag::create([
        //     'post_id' => '4',
        //     'tag_id' => '2',
        // ]);

        // PostTag::create([
        //     'post_id' => '4',
        //     'tag_id' => '3',
        // ]);
        
        // Post::create([
        //     'title' => 'Dummy Judul Artikel Proposal 5',
        //     'subtitle' => 'Dummy subtitle artikel proposal 5',
        //     'body' => 'Nullam nec tincidunt massa, sit amet dapibus diam. Etiam dictum elit mi, et condimentum metus luctus quis. Duis a ultrices orci. Nullam a odio condimentum, laoreet nunc vel, maximus turpis. Morbi convallis, eros placerat accumsan venenatis, mauris tellus luctus tellus, eu ultrices purus orci et eros. Suspendisse pharetra, ligula in consectetur luctus, libero neque viverra neque, aliquet tincidunt lectus ligula tristique felis. Integer mattis ultricies velit id tempus. Aenean fringilla libero sed lorem tempus bibendum. Aenean in dui ac orci auctor pharetra. Quisque faucibus lorem eget ante posuere placerat. Morbi et accumsan tortor.',
        //     'image' => 'images/placeholder.png'
        // ]);

        // PostTag::create([
        //     'post_id' => '5',
        //     'tag_id' => '1',
        // ]);

        // PostTag::create([
        //     'post_id' => '5',
        //     'tag_id' => '2',
        // ]);

        // PostTag::create([
        //     'post_id' => '5',
        //     'tag_id' => '3',
        // ]);

        // Post::create([
        //     'title' => 'Dummy Judul Artikel MBKM 1',
        //     'subtitle' => 'Dummy subtitle artikel MBKM 1',
        //     'body' => 'Nullam nec tincidunt massa, sit amet dapibus diam. Etiam dictum elit mi, et condimentum metus luctus quis. Duis a ultrices orci. Nullam a odio condimentum, laoreet nunc vel, maximus turpis. Morbi convallis, eros placerat accumsan venenatis, mauris tellus luctus tellus, eu ultrices purus orci et eros. Suspendisse pharetra, ligula in consectetur luctus, libero neque viverra neque, aliquet tincidunt lectus ligula tristique felis. Integer mattis ultricies velit id tempus. Aenean fringilla libero sed lorem tempus bibendum. Aenean in dui ac orci auctor pharetra. Quisque faucibus lorem eget ante posuere placerat. Morbi et accumsan tortor.',
        //     'image' => 'images/placeholder.png'
        // ]);

        // PostTag::create([
        //     'post_id' => '6',
        //     'tag_id' => '1',
        // ]);

        // PostTag::create([
        //     'post_id' => '6',
        //     'tag_id' => '2',
        // ]);

        // PostTag::create([
        //     'post_id' => '6',
        //     'tag_id' => '4',
        // ]);

        // Post::create([
        //     'title' => 'Dummy Judul Artikel MBKM 2',
        //     'subtitle' => 'Dummy subtitle artikel MBKM 2',
        //     'body' => 'Nullam nec tincidunt massa, sit amet dapibus diam. Etiam dictum elit mi, et condimentum metus luctus quis. Duis a ultrices orci. Nullam a odio condimentum, laoreet nunc vel, maximus turpis. Morbi convallis, eros placerat accumsan venenatis, mauris tellus luctus tellus, eu ultrices purus orci et eros. Suspendisse pharetra, ligula in consectetur luctus, libero neque viverra neque, aliquet tincidunt lectus ligula tristique felis. Integer mattis ultricies velit id tempus. Aenean fringilla libero sed lorem tempus bibendum. Aenean in dui ac orci auctor pharetra. Quisque faucibus lorem eget ante posuere placerat. Morbi et accumsan tortor.',
        //     'image' => 'images/placeholder.png'
        // ]);

        // PostTag::create([
        //     'post_id' => '7',
        //     'tag_id' => '1',
        // ]);

        // PostTag::create([
        //     'post_id' => '7',
        //     'tag_id' => '2',
        // ]);

        // PostTag::create([
        //     'post_id' => '7',
        //     'tag_id' => '4',
        // ]);

        // Post::create([
        //     'title' => 'Dummy Judul Artikel MBKM 3',
        //     'subtitle' => 'Dummy subtitle artikel MBKM 3',
        //     'body' => 'Nullam nec tincidunt massa, sit amet dapibus diam. Etiam dictum elit mi, et condimentum metus luctus quis. Duis a ultrices orci. Nullam a odio condimentum, laoreet nunc vel, maximus turpis. Morbi convallis, eros placerat accumsan venenatis, mauris tellus luctus tellus, eu ultrices purus orci et eros. Suspendisse pharetra, ligula in consectetur luctus, libero neque viverra neque, aliquet tincidunt lectus ligula tristique felis. Integer mattis ultricies velit id tempus. Aenean fringilla libero sed lorem tempus bibendum. Aenean in dui ac orci auctor pharetra. Quisque faucibus lorem eget ante posuere placerat. Morbi et accumsan tortor.',
        //     'image' => 'images/placeholder.png'
        // ]);

        // PostTag::create([
        //     'post_id' => '8',
        //     'tag_id' => '1',
        // ]);

        // PostTag::create([
        //     'post_id' => '8',
        //     'tag_id' => '2',
        // ]);

        // PostTag::create([
        //     'post_id' => '8',
        //     'tag_id' => '4',
        // ]);

        // Post::create([
        //     'title' => 'Dummy Judul Artikel MBKM 4',
        //     'subtitle' => 'Dummy subtitle artikel MBKM 4',
        //     'body' => 'Nullam nec tincidunt massa, sit amet dapibus diam. Etiam dictum elit mi, et condimentum metus luctus quis. Duis a ultrices orci. Nullam a odio condimentum, laoreet nunc vel, maximus turpis. Morbi convallis, eros placerat accumsan venenatis, mauris tellus luctus tellus, eu ultrices purus orci et eros. Suspendisse pharetra, ligula in consectetur luctus, libero neque viverra neque, aliquet tincidunt lectus ligula tristique felis. Integer mattis ultricies velit id tempus. Aenean fringilla libero sed lorem tempus bibendum. Aenean in dui ac orci auctor pharetra. Quisque faucibus lorem eget ante posuere placerat. Morbi et accumsan tortor.',
        //     'image' => 'images/placeholder.png'
        // ]);

        // PostTag::create([
        //     'post_id' => '9',
        //     'tag_id' => '1',
        // ]);

        // PostTag::create([
        //     'post_id' => '9',
        //     'tag_id' => '2',
        // ]);

        // PostTag::create([
        //     'post_id' => '9',
        //     'tag_id' => '4',
        // ]);

        // Post::create([
        //     'title' => 'Dummy Judul Artikel MBKM 5',
        //     'subtitle' => 'Dummy subtitle artikel MBKM 5',
        //     'body' => 'Nullam nec tincidunt massa, sit amet dapibus diam. Etiam dictum elit mi, et condimentum metus luctus quis. Duis a ultrices orci. Nullam a odio condimentum, laoreet nunc vel, maximus turpis. Morbi convallis, eros placerat accumsan venenatis, mauris tellus luctus tellus, eu ultrices purus orci et eros. Suspendisse pharetra, ligula in consectetur luctus, libero neque viverra neque, aliquet tincidunt lectus ligula tristique felis. Integer mattis ultricies velit id tempus. Aenean fringilla libero sed lorem tempus bibendum. Aenean in dui ac orci auctor pharetra. Quisque faucibus lorem eget ante posuere placerat. Morbi et accumsan tortor.',
        //     'image' => 'images/placeholder.png'
        // ]);

        // PostTag::create([
        //     'post_id' => '10',
        //     'tag_id' => '1',
        // ]);

        // PostTag::create([
        //     'post_id' => '10',
        //     'tag_id' => '2',
        // ]);

        // PostTag::create([
        //     'post_id' => '10',
        //     'tag_id' => '4',
        // ]);

        // ImportantSection::create([
        //     'name' => 'Kumpulan Link MBKM'
        // ]);

        // ImportantLink::create([
        //     'important_section_id' => '1',
        //     'name' => 'Angkatan 2018',
        //     'link' => 'http://bit.ly/MBKM2018'
        // ]);

        // ImportantLink::create([
        //     'important_section_id' => '1',
        //     'name' => 'Angkatan 2019',
        //     'link' => 'http://bit.ly/MBKM2019'
        // ]);

        // ImportantLink::create([
        //     'important_section_id' => '1',
        //     'name' => 'Angkatan 2020',
        //     'link' => 'http://bit.ly/MBKM2020'
        // ]);

        // ImportantSection::create([
        //     'name' => 'Kumpulan Link Kelas Mata Kuliah'
        // ]);

        // ImportantLink::create([
        //     'important_section_id' => '2',
        //     'name' => 'Angkatan 2018',
        //     'link' => 'http://bit.ly/KelasReguler2018'
        // ]);

        // ImportantLink::create([
        //     'important_section_id' => '2',
        //     'name' => 'Angkatan 2019',
        //     'link' => 'http://bit.ly/KelasReguler2019'
        // ]);

        // ImportantLink::create([
        //     'important_section_id' => '2',
        //     'name' => 'Angkatan 2020',
        //     'link' => 'http://bit.ly/KelasReguler2020'
        // ]);

        // ImportantSection::create([
        //     'name' => 'Kumpulan Link Kelas Proposal'
        // ]);

        // ImportantLink::create([
        //     'important_section_id' => '3',
        //     'name' => 'Angkatan 2018',
        //     'link' => 'http://bit.ly/Proposal2018'
        // ]);

        // ImportantLink::create([
        //     'important_section_id' => '3',
        //     'name' => 'Angkatan 2019',
        //     'link' => 'http://bit.ly/Proposal2019'
        // ]);

        // ImportantLink::create([
        //     'important_section_id' => '3',
        //     'name' => 'Angkatan 2020',
        //     'link' => 'http://bit.ly/Proposal2020'
        // ]);


        // ImportantSection::create([
        //     'name' => 'Kumpulan Link Tugas Akhir'
        // ]);

        // ImportantLink::create([
        //     'important_section_id' => '4',
        //     'name' => 'Angkatan 2018',
        //     'link' => 'http://bit.ly/TA2018'
        // ]);

        // ImportantLink::create([
        //     'important_section_id' => '4',
        //     'name' => 'Angkatan 2019',
        //     'link' => 'http://bit.ly/TA2019'
        // ]);

        // ImportantLink::create([
        //     'important_section_id' => '4',
        //     'name' => 'Angkatan 2020',
        //     'link' => 'http://bit.ly/TA2020'
        // ]);
    }
}
