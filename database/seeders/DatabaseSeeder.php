<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Languages
        Language::create([
            'code' => 'id',
            'name' => 'Indonesian',
            'native_name' => 'Bahasa Indonesia',
            'flag' => '🇮🇩',
            'is_default' => true,
            'is_active' => true,
            'order' => 1,
        ]);

        Language::create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag' => '🇺🇸',
            'is_default' => false,
            'is_active' => true,
            'order' => 2,
        ]);

        // Create Permissions
        $permissions = [
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            'pages.view', 'pages.create', 'pages.edit', 'pages.delete',
            'news.view', 'news.create', 'news.edit', 'news.delete',
            'research.view', 'research.create', 'research.edit', 'research.delete',
            'pengabdian.view', 'pengabdian.create', 'pengabdian.edit', 'pengabdian.delete',
            'publications.view', 'publications.create', 'publications.edit', 'publications.delete',
            'cooperation.view', 'cooperation.create', 'cooperation.edit', 'cooperation.delete',
            'grants.view', 'grants.create', 'grants.edit', 'grants.delete',
            'downloads.view', 'downloads.create', 'downloads.edit', 'downloads.delete',
            'gallery.view', 'gallery.create', 'gallery.edit', 'gallery.delete',
            'settings.view', 'settings.edit',
            'menus.view', 'menus.edit',
            'sliders.view', 'sliders.create', 'sliders.edit', 'sliders.delete',
            'languages.view', 'languages.create', 'languages.edit', 'languages.delete',
            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
            'contacts.view', 'contacts.reply', 'contacts.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles
        $superAdmin = Role::create(['name' => 'super-admin']);
        $admin = Role::create(['name' => 'admin']);
        $editor = Role::create(['name' => 'editor']);
        $researcher = Role::create(['name' => 'researcher']);

        $superAdmin->givePermissionTo(Permission::all());
        $admin->givePermissionTo(Permission::whereNotIn('name', ['roles.create', 'roles.edit', 'roles.delete', 'users.delete'])->get());
        $editor->givePermissionTo(['pages.view', 'pages.create', 'pages.edit', 'news.view', 'news.create', 'news.edit', 'gallery.view', 'gallery.create', 'gallery.edit']);
        $researcher->givePermissionTo(['research.view', 'research.create', 'research.edit', 'pengabdian.view', 'pengabdian.create', 'pengabdian.edit', 'publications.view', 'publications.create', 'publications.edit']);

        // Create Super Admin User
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@lppm.ac.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('super-admin');

        // Create Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'LPPM Universitas', 'type' => 'text', 'group' => 'general', 'label' => 'Nama Situs', 'order' => 1],
            ['key' => 'site_tagline', 'value' => 'Lembaga Penelitian dan Pengabdian kepada Masyarakat', 'type' => 'text', 'group' => 'general', 'label' => 'Tagline', 'order' => 2],
            ['key' => 'site_description', 'value' => 'LPPM adalah lembaga yang mengelola kegiatan penelitian dan pengabdian kepada masyarakat.', 'type' => 'textarea', 'group' => 'general', 'label' => 'Deskripsi', 'order' => 3],
            ['key' => 'contact_address', 'value' => 'Jl. Universitas No. 1, Kota, Indonesia', 'type' => 'textarea', 'group' => 'contact', 'label' => 'Alamat', 'order' => 1],
            ['key' => 'contact_phone', 'value' => '+62 21 1234567', 'type' => 'text', 'group' => 'contact', 'label' => 'Telepon', 'order' => 2],
            ['key' => 'contact_email', 'value' => 'lppm@universitas.ac.id', 'type' => 'text', 'group' => 'contact', 'label' => 'Email', 'order' => 3],
            ['key' => 'site_logo', 'value' => '', 'type' => 'image', 'group' => 'appearance', 'label' => 'Logo', 'order' => 1],
            ['key' => 'site_favicon', 'value' => '', 'type' => 'image', 'group' => 'appearance', 'label' => 'Favicon', 'order' => 2],
            ['key' => 'primary_color', 'value' => '#1e40af', 'type' => 'color', 'group' => 'appearance', 'label' => 'Warna Utama', 'order' => 3],
            ['key' => 'secondary_color', 'value' => '#059669', 'type' => 'color', 'group' => 'appearance', 'label' => 'Warna Sekunder', 'order' => 4],
            ['key' => 'footer_text', 'value' => '© 2024 LPPM Universitas. All rights reserved.', 'type' => 'text', 'group' => 'appearance', 'label' => 'Footer Text', 'order' => 5],
            ['key' => 'social_facebook', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Facebook', 'order' => 1],
            ['key' => 'social_instagram', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Instagram', 'order' => 2],
            ['key' => 'social_youtube', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'YouTube', 'order' => 3],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        // Create Main Menu
        $mainMenu = Menu::create(['name' => 'Main Menu', 'location' => 'main']);
        
        $menuItems = [
            ['title' => 'Beranda', 'url' => '/', 'order' => 1],
            ['title' => 'Profil', 'url' => '#', 'order' => 2, 'children' => [
                ['title' => 'Sejarah', 'url' => '/profil/sejarah'],
                ['title' => 'Visi Misi', 'url' => '/profil/visi-misi'],
                ['title' => 'Struktur Organisasi', 'url' => '/profil/struktur-organisasi'],
                ['title' => 'Program Kerja', 'url' => '/profil/program-kerja'],
            ]],
            ['title' => 'Penelitian', 'url' => '#', 'order' => 3, 'children' => [
                ['title' => 'Roadmap', 'url' => '/penelitian/roadmap'],
                ['title' => 'Skema Hibah', 'url' => '/penelitian/skema-hibah'],
                ['title' => 'Panduan', 'url' => '/penelitian/panduan'],
                ['title' => 'Hasil Penelitian', 'url' => '/penelitian/hasil'],
            ]],
            ['title' => 'Pengabdian', 'url' => '#', 'order' => 4, 'children' => [
                ['title' => 'Program PkM', 'url' => '/pengabdian/program'],
                ['title' => 'Panduan', 'url' => '/pengabdian/panduan'],
                ['title' => 'Laporan', 'url' => '/pengabdian/laporan'],
            ]],
            ['title' => 'Publikasi', 'url' => '#', 'order' => 5, 'children' => [
                ['title' => 'Jurnal', 'url' => '/publikasi/jurnal'],
                ['title' => 'Prosiding', 'url' => '/publikasi/prosiding'],
                ['title' => 'HKI', 'url' => '/publikasi/hki'],
                ['title' => 'Repository', 'url' => '/publikasi/repository'],
            ]],
            ['title' => 'Kerjasama', 'url' => '/kerjasama', 'order' => 6],
            ['title' => 'Hibah', 'url' => '/hibah', 'order' => 7],
            ['title' => 'Download', 'url' => '/download', 'order' => 8],
            ['title' => 'Berita', 'url' => '/berita', 'order' => 9],
            ['title' => 'Galeri', 'url' => '/galeri', 'order' => 10],
            ['title' => 'Kontak', 'url' => '/kontak', 'order' => 11],
        ];

        foreach ($menuItems as $index => $item) {
            $menuItem = MenuItem::create([
                'menu_id' => $mainMenu->id,
                'title' => $item['title'],
                'url' => $item['url'],
                'order' => $item['order'] ?? $index,
                'is_active' => true,
            ]);

            if (isset($item['children'])) {
                foreach ($item['children'] as $childIndex => $child) {
                    MenuItem::create([
                        'menu_id' => $mainMenu->id,
                        'parent_id' => $menuItem->id,
                        'title' => $child['title'],
                        'url' => $child['url'],
                        'order' => $childIndex,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
