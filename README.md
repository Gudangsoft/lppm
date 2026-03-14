# Sistem LPPM (Lembaga Penelitian dan Pengabdian kepada Masyarakat)

Sistem manajemen LPPM berbasis Laravel dengan tema modern responsive, panel admin lengkap, pengaturan template seperti WordPress, manajemen role, dan dukungan multi bahasa.

## Fitur Utama

### Frontend
- **Beranda**: Hero slider, statistik, penelitian terbaru, berita terbaru, agenda
- **Profil**: Sejarah, Visi Misi, Struktur Organisasi, Program Kerja
- **Penelitian**: Roadmap, Skema Hibah, Panduan, Hasil Penelitian
- **Pengabdian**: Program PKM, Panduan, Laporan Pengabdian
- **Publikasi**: Jurnal, Prosiding, Artikel, HKI, Repository
- **Kerjasama**: Daftar mitra kerjasama
- **Hibah**: Informasi hibah penelitian/pengabdian
- **Download**: File dan dokumen
- **Berita**: Berita dan artikel
- **Galeri**: Album foto kegiatan
- **Agenda**: Event dan kegiatan mendatang
- **Kontak**: Form kontak dan informasi

### Admin Panel
- **Dashboard**: Statistik dan ringkasan aktivitas
- **Pengaturan Website**: 
  - Umum (nama, deskripsi, bahasa default)
  - Tampilan (logo, favicon, warna tema, header style)
  - Media Sosial
  - Kontak
  - SEO & Analytics
  - Footer
- **Manajemen Pengguna**: CRUD pengguna dengan role
- **Role & Permissions**: Manajemen hak akses
- **Bahasa**: Multi bahasa (ID & EN)
- **Halaman**: Halaman statis dengan template
- **Kategori**: Kategori berita/penelitian/pengabdian
- **Berita**: CRUD berita multi bahasa
- **Penelitian**: CRUD hasil penelitian
- **Menu**: Manajemen menu navigasi
- **Slider**: Hero slider homepage

## Teknologi

- **Framework**: Laravel 12
- **Database**: MySQL
- **CSS**: Tailwind CSS (via CDN)
- **JS**: Alpine.js
- **Icons**: Font Awesome 6
- **Editor**: CKEditor 5
- **Auth**: Laravel UI + Bootstrap
- **Permissions**: Spatie Laravel Permission

## Instalasi

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL >= 5.7
- Node.js >= 18 (untuk compile assets)

### Langkah Instalasi

1. **Clone/Download project**

2. **Install dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Konfigurasi environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setting database di .env**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lppm
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Jalankan migrasi dan seeder**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Buat symbolic link storage**
   ```bash
   php artisan storage:link
   ```

7. **Jalankan server**
   ```bash
   php artisan serve
   ```

8. **Akses aplikasi**
   - Frontend: http://localhost:8000
   - Admin: http://localhost:8000/admin

## Kredensial Default

### Super Admin
- Email: admin@lppm.ac.id
- Password: password

### Editor
- Email: editor@lppm.ac.id
- Password: password

## Struktur Direktori

```
app/
├── Http/Controllers/
│   ├── Admin/              # Controller admin panel
│   └── FrontendController  # Controller frontend
├── Models/                 # Eloquent models
├── Providers/              # Service providers
└── Traits/                 # Translatable trait

database/
├── migrations/             # Database migrations
└── seeders/               # Database seeders

resources/
├── views/
│   ├── admin/             # Admin panel views
│   │   ├── layouts/       # Admin layout
│   │   ├── categories/    # CRUD kategori
│   │   ├── languages/     # CRUD bahasa
│   │   ├── menus/         # Manajemen menu
│   │   ├── news/          # CRUD berita
│   │   ├── pages/         # CRUD halaman
│   │   ├── roles/         # CRUD role
│   │   ├── settings/      # Pengaturan website
│   │   ├── sliders/       # CRUD slider
│   │   └── users/         # CRUD pengguna
│   └── frontend/          # Frontend views
│       ├── layouts/       # Frontend layout
│       ├── news/          # Berita
│       └── research/      # Penelitian
└── lang/                  # File bahasa
    ├── id/               # Bahasa Indonesia
    └── en/               # English
```

## Multi Bahasa

Sistem mendukung multi bahasa dengan fitur:
- Konten dapat diterjemahkan ke berbagai bahasa
- Switcher bahasa di frontend
- Default bahasa: Indonesia (id)
- Bahasa tersedia: Indonesia, English

## Custom Theme

Warna theme dapat dikustomisasi melalui:
- Admin > Pengaturan > Tampilan > Warna Tema Utama

## Roles & Permissions

### Roles
- **super-admin**: Akses penuh ke seluruh sistem
- **admin**: Manajemen konten dan pengguna
- **editor**: Manajemen konten (berita, penelitian, dll)
- **author**: Membuat dan mengedit konten sendiri
- **user**: Pengguna terdaftar

### Permissions
- news-*, page-*, category-*, research-*, pengabdian-*
- publication-*, cooperation-*, grant-*, download-*
- gallery-*, event-*, slider-*, menu-*, setting-*
- user-*, role-*, language-*

## Screenshot

### Frontend
- Homepage dengan hero slider modern
- Halaman berita dengan sidebar kategori
- Halaman penelitian dengan filter pencarian
- Form kontak responsive

### Admin Panel
- Dashboard dengan statistik
- Settings WordPress-like dengan tabs
- CRUD table dengan pagination
- Form multi-bahasa dengan tab switching

## License

MIT License

## Author

Generated with Laravel and love.
