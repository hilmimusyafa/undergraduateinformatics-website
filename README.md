# Undergraduate Informatics Website

Repository website Portal Informasi Sarjana Informatika Telkom University. Public site dibangun dengan React dan admin memakai Blade, keduanya dilayani Laravel.

## Persyaratan

- PHP `^8.1` (disarankan 8.2+)
- Composer
- Node.js 18+ dan npm
- MySQL (atau SQLite untuk pengembangan lokal)

## Setup Development

1. **Clone dan install dependensi**

   ```bash
   git clone https://github.com/hilmimusyafa/undergraduateinformatics-website && cd undergraduateinformatics-website
   composer install
   npm install
   ```

2. **Konfigurasi environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Sesuaikan koneksi database di `.env` (misal MySQL: `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`). Untuk pengembangan cepat bisa pakai SQLite: kosongkan `DB_*` lalu `touch database/database.sqlite`.

3. **Migrasi dan seed**

   ```bash
   php artisan migrate --seed
   ```

4. **Jalankan dev server** (dua terminal)

   ```bash
   php artisan serve        # terminal 1 — Laravel
   npm run dev              # terminal 2 — Vite (hot reload asset)
   ```

5. **Akses**

   - Public site: `http://localhost:8000`
   - Admin: `http://localhost:8000/admin`
   - Akun admin default dari `UserSeeder`: `bif@telkomuniversity.ac.id` / `akunadmin`

## Setup Production

1. **Install dependensi tanpa dev**

   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   ```

2. **Konfigurasi environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Pastikan `.env` memakai nilai produksi: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, dan konfigurasi database yang benar.

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

   > Catatan: setelah deploy, jalankan `php artisan migrate --force` lagi saat ada migration baru, dan ulangi `npm run build` + `config:cache` setelah ada perubahan aset/konfigurasi.
