# Undergraduate Informatics Website

Repository website Portal Informasi Sarjana Informatika Telkom University. Public site dan admin dibangun dengan Laravel Blade.

## Persyaratan

- PHP `^8.1` (disarankan 8.2+)
- Composer
- MySQL

## Setup Development

1. **Clone dan install dependensi**

    ```bash
    git clone https://github.com/hilmimusyafa/undergraduateinformatics-website && cd undergraduateinformatics-website
    composer install
    ```

2. **Konfigurasi environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    Sesuaikan koneksi MySQL di `.env` (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`).

3. **Migrasi dan seed**

    ```bash
    php artisan migrate --seed
    ```

4. **Jalankan server Laravel**

    ```bash
    php artisan serve
    ```

5. **Akses**

    - Public site: `http://localhost:8000`
    - Admin: `http://localhost:8000/admin`
    - Akun admin default dari `UserSeeder`: `bif@telkomuniversity.ac.id` / `akunadmin`

## Setup Production

1. **Install dependensi tanpa dev**

    ```bash
    composer install --no-dev --optimize-autoloader
    ```

2. **Konfigurasi environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    Pastikan `.env` memakai nilai produksi: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, dan konfigurasi MySQL yang benar.

3. **Migrasi**

    ```bash
    php artisan migrate --force
    ```

4. **Cache konfigurasi**

    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

5. **Scheduler (cron)**

    Definisikan form MS Forms di-warm tiap 5 menit agar user tidak pernah kena cold fetch ke Microsoft saat membuka halaman reservasi/feedback:

    ```bash
    * * * * * cd /path/proyek && php artisan schedule:run >> /dev/null 2>&1
    ```

6. **Izin direktori**

    Pastikan `storage/` dan `bootstrap/cache/` dapat ditulis oleh user web server:

    ```bash
    chmod -R 775 storage bootstrap/cache
    ```

7. **Web server**

    Arahkan document root ke folder `public/`. Jangan arahkan ke root proyek karena kode PHP tidak boleh diekspos.

    > Catatan: setelah deploy, jalankan `php artisan migrate --force` lagi saat ada migration baru.
