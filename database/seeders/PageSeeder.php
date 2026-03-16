<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'sejarah',
                'template' => 'sidebar',
                'is_published' => true,
                'order' => 1,
                'title' => 'Sejarah LPPM',
                'content' => '<h2>Sejarah Lembaga Penelitian dan Pengabdian kepada Masyarakat</h2>
<p>Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM) didirikan dengan tujuan untuk mengkoordinasikan, memfasilitasi, dan meningkatkan kualitas penelitian dan pengabdian kepada masyarakat di lingkungan universitas.</p>

<h3>Awal Berdiri</h3>
<p>LPPM berdiri sejak tahun 2000 sebagai lembaga yang bertugas mengelola kegiatan penelitian dan pengabdian kepada masyarakat. Sejak awal berdirinya, LPPM telah berkomitmen untuk mendorong inovasi dan pengembangan ilmu pengetahuan yang bermanfaat bagi masyarakat luas.</p>

<h3>Perkembangan</h3>
<p>Seiring berjalannya waktu, LPPM terus berkembang dan memperluas jangkauan programnya. Berbagai skema penelitian dan pengabdian telah berhasil dilaksanakan dengan melibatkan dosen dan mahasiswa dari berbagai program studi.</p>

<h3>Capaian</h3>
<p>Hingga saat ini, LPPM telah berhasil mengelola ratusan proyek penelitian dengan pendanaan dari berbagai sumber, baik internal universitas maupun eksternal dari pemerintah dan sektor swasta. LPPM juga aktif menjalin kerjasama dengan berbagai institusi dalam dan luar negeri untuk pengembangan riset dan pengabdian yang lebih berkualitas.</p>',
            ],
            [
                'slug' => 'visi-misi',
                'template' => 'full-width',
                'is_published' => true,
                'order' => 2,
                'title' => 'Visi & Misi',
                'content' => '<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
<div class="bg-blue-50 border-l-4 border-blue-600 p-6 rounded-r-xl">
<h2 class="text-2xl font-bold text-blue-800 mb-4">🎯 Visi</h2>
<p class="text-gray-700 leading-relaxed">Menjadi lembaga penelitian dan pengabdian kepada masyarakat yang unggul, inovatif, dan berdaya saing nasional maupun internasional dalam menghasilkan riset berkualitas tinggi yang bermanfaat bagi masyarakat dan bangsa Indonesia.</p>
</div>
<div class="bg-green-50 border-l-4 border-green-600 p-6 rounded-r-xl">
<h2 class="text-2xl font-bold text-green-800 mb-4">🚀 Misi</h2>
<ul class="list-disc list-inside text-gray-700 space-y-2">
<li>Menyelenggarakan penelitian yang berkualitas dan relevan</li>
<li>Mendorong pengabdian kepada masyarakat yang efektif</li>
<li>Mengembangkan kemitraan strategis dengan berbagai pihak</li>
<li>Meningkatkan publikasi ilmiah di tingkat nasional dan internasional</li>
<li>Mengelola kekayaan intelektual hasil penelitian</li>
</ul>
</div>
</div>

<h3 class="text-xl font-bold text-gray-800 mb-4">Tujuan Strategis</h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="text-center p-6 bg-gray-50 rounded-xl">
<div class="text-4xl mb-3">🔬</div>
<h4 class="font-semibold text-gray-800 mb-2">Penelitian Unggulan</h4>
<p class="text-sm text-gray-600">Menghasilkan penelitian yang inovatif dan berdampak</p>
</div>
<div class="text-center p-6 bg-gray-50 rounded-xl">
<div class="text-4xl mb-3">🤝</div>
<h4 class="font-semibold text-gray-800 mb-2">Pengabdian Masyarakat</h4>
<p class="text-sm text-gray-600">Memberikan kontribusi nyata bagi masyarakat</p>
</div>
<div class="text-center p-6 bg-gray-50 rounded-xl">
<div class="text-4xl mb-3">🌐</div>
<h4 class="font-semibold text-gray-800 mb-2">Kerjasama Global</h4>
<p class="text-sm text-gray-600">Menjalin kemitraan dengan institusi terkemuka</p>
</div>
</div>',
            ],
            [
                'slug' => 'program-kerja',
                'template' => 'sidebar',
                'is_published' => true,
                'order' => 4,
                'title' => 'Program Kerja',
                'content' => '<h2>Program Kerja LPPM</h2>
<p>LPPM memiliki berbagai program kerja strategis yang dirancang untuk mendukung kegiatan penelitian dan pengabdian kepada masyarakat di lingkungan universitas.</p>

<h3>Program Penelitian</h3>
<ul>
<li>Hibah Penelitian Internal Universitas</li>
<li>Pendampingan proposal penelitian eksternal</li>
<li>Workshop metodologi penelitian</li>
<li>Pengelolaan jurnal ilmiah</li>
</ul>

<h3>Program Pengabdian Masyarakat</h3>
<ul>
<li>Program Kuliah Kerja Nyata (KKN)</li>
<li>Program desa binaan</li>
<li>Pemberdayaan UMKM</li>
<li>Pelatihan vokasi masyarakat</li>
</ul>

<h3>Program Publikasi</h3>
<ul>
<li>Bantuan biaya publikasi jurnal internasional</li>
<li>Pendampingan penulisan artikel ilmiah</li>
<li>Program HKI (Hak Kekayaan Intelektual)</li>
</ul>',
            ],
        ];

        foreach ($pages as $pageData) {
            $title = $pageData['title'];
            $content = $pageData['content'];
            unset($pageData['title'], $pageData['content']);

            // Check if page already exists
            $existing = Page::where('slug', $pageData['slug'])->first();
            if ($existing) {
                continue;
            }

            $page = Page::create(array_merge($pageData, ['created_by' => 1]));

            // Create translations for Indonesian
            $page->translations()->create([
                'locale' => 'id',
                'title' => $title,
                'content' => $content,
                'meta_title' => $title . ' - LPPM',
                'meta_description' => 'Halaman ' . $title . ' LPPM Universitas',
            ]);

            // Create translations for English (same content for now)
            $page->translations()->create([
                'locale' => 'en',
                'title' => $title,
                'content' => $content,
                'meta_title' => $title . ' - LPPM',
                'meta_description' => $title . ' page of LPPM University',
            ]);
        }
    }
}
