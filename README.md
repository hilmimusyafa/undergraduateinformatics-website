# Undergraduate Informatics Website

Repository of Telkom University Informatics Undergraduate Website.

## Menyiapkan database

Skema database sudah tersedia sebagai Laravel migrations di
`database/migrations`. Database yang dipakai secara default bernama
`websiteprodi` (lihat `.env.example`).

Untuk pengembangan lokal tanpa server MySQL, gunakan SQLite:

```bash
touch database/database.sqlite
```

Lalu ubah koneksi di `.env` menjadi:

```dotenv
DB_CONNECTION=sqlite
```

Apabila driver PHP tersedia tetapi belum aktif, tambahkan
`-d extension=pdo_sqlite` pada perintah Artisan, misalnya:

```bash
php -d extension=pdo_sqlite artisan migrate --seed
php -d extension=pdo_sqlite artisan serve
```

Alternatifnya, gunakan MySQL seperti konfigurasi bawaan berikut.

Pastikan PHP memiliki ekstensi `pdo_mysql`, lalu buat database kosong:

```sql
CREATE DATABASE websiteprodi
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

Salin konfigurasi lokal dan buat application key jika belum tersedia:

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan
`DB_PASSWORD` di `.env`, kemudian buat seluruh tabel sekaligus data awal:

```bash
php artisan migrate --seed
```

Perintah tersebut membuat tabel:

- `users`
- `personal_access_tokens`
- `posts`
- `post_tags`
- `tags`
- `important_sections`
- `important_links`
- `password_recoveries`
- `feedback_links`

Seeder membuat akun admin awal dan tag bawaan. Migration `feedback_links`
juga langsung memasukkan URL formulir feedback bawaan.

Setelah migrasi berhasil, jalankan aplikasi:

```bash
php artisan serve
```
