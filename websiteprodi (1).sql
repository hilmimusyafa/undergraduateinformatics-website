-- Rekonstruksi database dari Laravel migrations dan DatabaseSeeder.
-- Target: MySQL 8 / MariaDB dengan charset utf8mb4.

CREATE DATABASE IF NOT EXISTS `websiteprodi`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `websiteprodi`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `personal_access_tokens`;
DROP TABLE IF EXISTS `post_tags`;
DROP TABLE IF EXISTS `important_links`;
DROP TABLE IF EXISTS `feedback_links`;
DROP TABLE IF EXISTS `password_recoveries`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `posts`;
DROP TABLE IF EXISTS `tags`;
DROP TABLE IF EXISTS `important_sections`;
DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `password_recovery_id` BIGINT UNSIGNED NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `personal_access_tokens` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tokenable_type` VARCHAR(255) NOT NULL,
    `tokenable_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `token` VARCHAR(64) NOT NULL,
    `abilities` TEXT NULL,
    `last_used_at` TIMESTAMP NULL DEFAULT NULL,
    `expires_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
    KEY `personal_access_tokens_tokenable_type_tokenable_id_index`
        (`tokenable_type`, `tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `posts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` TEXT NOT NULL,
    `subtitle` TEXT NOT NULL,
    `body` TEXT NOT NULL,
    `image` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `post_tags` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `post_id` BIGINT UNSIGNED NOT NULL,
    `tag_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tags` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `important_sections` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `order_number` INT NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `important_links` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `important_section_id` BIGINT UNSIGNED NOT NULL,
    `name` TEXT NOT NULL,
    `link` TEXT NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_recoveries` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `first_question` TEXT NOT NULL,
    `second_question` TEXT NOT NULL,
    `first_answer` TEXT NOT NULL,
    `second_answer` TEXT NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `feedback_links` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `link` TEXT NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data awal dari DatabaseSeeder dan migration feedback_links.
-- Password akun admin adalah: akunadmin
INSERT INTO `users`
    (`id`, `password_recovery_id`, `email`, `password`, `remember_token`, `created_at`, `updated_at`)
VALUES
    (1, 1, 'bif@telkomuniversity.ac.id',
     '$2y$12$vDHccFvAU2NK1Qv1rT4jIeUROqrfPMKiVqQO6zl3BFljS4vhYF/XS',
     NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO `password_recoveries`
    (`id`, `user_id`, `first_question`, `second_question`, `first_answer`, `second_answer`, `created_at`, `updated_at`)
VALUES
    (1, 1, 'Pertanyaan pertama adalah?', 'Pertanyaan kedua adalah?',
     'jawaban satu', 'jawaban dua', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO `tags`
    (`id`, `name`, `description`, `created_at`, `updated_at`)
VALUES
    (1, 'S1 Informatika', 'deskripsi tag S1 Informatika',
     CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO `feedback_links`
    (`id`, `link`, `created_at`, `updated_at`)
VALUES
    (1,
     'https://forms.office.com/pages/responsepage.aspx?id=D_6vkKPCCEG7mGzrTpTvFc9ujqZdH91MtXpfw-rWy2hUNFA5NUhUMlYwNU5RSE5TVDlWUzI1WUZTRi4u',
     CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO `migrations` (`migration`, `batch`) VALUES
    ('2014_10_12_000000_create_users_table', 1),
    ('2019_12_14_000001_create_personal_access_tokens_table', 1),
    ('2023_07_01_054757_create_posts_table', 1),
    ('2023_07_01_062820_create_post_tags_table', 1),
    ('2023_07_01_062946_create_tags_table', 1),
    ('2023_07_01_063351_create_important_sections_table', 1),
    ('2023_07_01_063356_create_important_links_table', 1),
    ('2023_07_20_075037_create_password_recoveries_table', 1),
    ('2023_07_28_080213_create_feedback_links_table', 1);

SET FOREIGN_KEY_CHECKS = 1;
