<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PasswordRecoverySeeder::class,
            FeedbackLinkSeeder::class,
            ReservationLinkSeeder::class,
            TagSeeder::class,
            ImportantSectionSeeder::class,
            ImportantLinkSeeder::class,
            PostSeeder::class,
            PostTagSeeder::class,
        ]);
    }
}