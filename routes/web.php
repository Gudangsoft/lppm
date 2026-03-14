<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ResearchController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\InternalGrantSchemeController;
use App\Http\Controllers\Admin\InternalGrantPeriodController;
use App\Http\Controllers\Admin\InternalGrantSubmissionController;
use App\Http\Controllers\Admin\InternalGrantController;
use App\Http\Controllers\Admin\InternalGrantProgressReportController;
use App\Http\Controllers\Admin\InternalGrantFinalReportController;
use App\Http\Controllers\Admin\InternalGrantReportController;
use App\Http\Controllers\Admin\BackupController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [FrontendController::class, 'index'])->name('home');

// Language Switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Static Pages
Route::get('/page/{slug}', [FrontendController::class, 'page'])->name('page');

// Profile
Route::prefix('profil')->name('profile.')->group(function () {
    Route::get('/sejarah', [FrontendController::class, 'page'])->defaults('slug', 'sejarah')->name('history');
    Route::get('/visi-misi', [FrontendController::class, 'page'])->defaults('slug', 'visi-misi')->name('vision');
    Route::get('/struktur-organisasi', [FrontendController::class, 'organizationStructure'])->name('structure');
    Route::get('/program-kerja', [FrontendController::class, 'page'])->defaults('slug', 'program-kerja')->name('programs');
});

// News/Berita
Route::prefix('berita')->name('news.')->group(function () {
    Route::get('/', [FrontendController::class, 'news'])->name('index');
    Route::get('/{slug}', [FrontendController::class, 'newsDetail'])->name('detail');
});

// Research/Penelitian
Route::prefix('penelitian')->name('research.')->group(function () {
    Route::get('/roadmap', [FrontendController::class, 'researchRoadmap'])->name('roadmap');
    Route::get('/skema-hibah', [FrontendController::class, 'researchSchemes'])->name('schemes');
    Route::get('/panduan', [FrontendController::class, 'researchGuides'])->name('guides');
    Route::get('/hasil', [FrontendController::class, 'researches'])->name('index');
    Route::get('/hasil/{slug}', [FrontendController::class, 'researchDetail'])->name('detail');
});

// Community Service/Pengabdian
Route::prefix('pengabdian')->name('pengabdian.')->group(function () {
    Route::get('/program', [FrontendController::class, 'pkmPrograms'])->name('programs');
    Route::get('/panduan', [FrontendController::class, 'pkmGuides'])->name('guides');
    Route::get('/laporan', [FrontendController::class, 'pengabdians'])->name('index');
    Route::get('/laporan/{slug}', [FrontendController::class, 'pengabdianDetail'])->name('detail');
});

// Publications/Publikasi
Route::prefix('publikasi')->name('publication.')->group(function () {
    Route::get('/jurnal', [FrontendController::class, 'journals'])->name('journals');
    Route::get('/prosiding', [FrontendController::class, 'publications'])->defaults('type', 'proceeding')->name('proceedings');
    Route::get('/artikel', [FrontendController::class, 'publications'])->name('index');
    Route::get('/artikel/{slug}', [FrontendController::class, 'publicationDetail'])->name('detail');
    Route::get('/hki', [FrontendController::class, 'hkis'])->name('hki');
    Route::get('/repository', [FrontendController::class, 'repositories'])->name('repository');
});

// Cooperation/Kerjasama
Route::prefix('kerjasama')->name('cooperation.')->group(function () {
    Route::get('/', [FrontendController::class, 'cooperations'])->name('index');
    Route::get('/{slug}', [FrontendController::class, 'cooperationDetail'])->name('detail');
});

// Grants/Hibah
Route::prefix('hibah')->name('grant.')->group(function () {
    Route::get('/', [FrontendController::class, 'grants'])->name('index');
    Route::get('/{slug}', [FrontendController::class, 'grantDetail'])->name('detail');
});

// Downloads
Route::prefix('download')->name('download.')->group(function () {
    Route::get('/', [FrontendController::class, 'downloads'])->name('index');
    Route::get('/file/{download}', [FrontendController::class, 'downloadFile'])->name('file');
});

// Gallery
Route::prefix('galeri')->name('gallery.')->group(function () {
    Route::get('/', [FrontendController::class, 'gallery'])->name('index');
    Route::get('/{slug}', [FrontendController::class, 'galleryDetail'])->name('detail');
});

// Events
Route::prefix('agenda')->name('event.')->group(function () {
    Route::get('/', [FrontendController::class, 'events'])->name('index');
    Route::get('/{slug}', [FrontendController::class, 'eventDetail'])->name('detail');
});

// Contact
Route::get('/kontak', [FrontendController::class, 'contact'])->name('contact');
Route::post('/kontak', [FrontendController::class, 'submitContact'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:super-admin|admin|editor'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    
    // Users
    Route::resource('users', UserController::class);
    
    // Roles
    Route::resource('roles', RoleController::class);
    
    // Languages
    Route::resource('languages', LanguageController::class);
    
    // Pages
    Route::resource('pages', PageController::class);
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // News
    Route::resource('news', NewsController::class);
    
    // Research
    Route::resource('researches', ResearchController::class);
    
    // Menus
    Route::resource('menus', MenuController::class);
    Route::resource('menu-items', App\Http\Controllers\Admin\MenuItemController::class)->only(['store', 'update', 'destroy']);
    
    // Sliders
    Route::resource('sliders', SliderController::class);
    
    // Internal Grants System
    Route::prefix('internal-grants')->name('internal-grants.')->group(function () {
        // Schemes
        Route::resource('schemes', InternalGrantSchemeController::class);
        
        // Periods
        Route::resource('periods', InternalGrantPeriodController::class);
        Route::patch('periods/{period}/toggle-status', [InternalGrantPeriodController::class, 'toggleStatus'])->name('periods.toggle-status');
        
        // Submissions
        Route::resource('submissions', InternalGrantSubmissionController::class);
        Route::get('submissions/{submission}/review', [InternalGrantSubmissionController::class, 'review'])->name('submissions.review');
        Route::post('submissions/{submission}/review', [InternalGrantSubmissionController::class, 'storeReview'])->name('submissions.store-review');
        Route::patch('submissions/{submission}/status', [InternalGrantSubmissionController::class, 'updateStatus'])->name('submissions.update-status');
        Route::post('submissions/{submission}/accept', [InternalGrantSubmissionController::class, 'accept'])->name('submissions.accept');
        
        // Grants
        Route::resource('grants', InternalGrantController::class);
        Route::post('grants/{grant}/disbursement', [InternalGrantController::class, 'addDisbursement'])->name('grants.add-disbursement');
        Route::post('grants/{grant}/output', [InternalGrantController::class, 'addOutput'])->name('grants.add-output');
        
        // Progress Reports
        Route::resource('progress-reports', InternalGrantProgressReportController::class);
        Route::patch('progress-reports/{progressReport}/review', [InternalGrantProgressReportController::class, 'review'])->name('progress-reports.review');
        
        // Final Reports
        Route::resource('final-reports', InternalGrantFinalReportController::class);
        Route::patch('final-reports/{finalReport}/review', [InternalGrantFinalReportController::class, 'review'])->name('final-reports.review');
        
        // BIMA-style Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [InternalGrantReportController::class, 'index'])->name('index');
            Route::get('/submissions', [InternalGrantReportController::class, 'submissions'])->name('submissions');
            Route::get('/active-grants', [InternalGrantReportController::class, 'activeGrants'])->name('active-grants');
            Route::get('/budget-realization', [InternalGrantReportController::class, 'budgetRealization'])->name('budget-realization');
            Route::get('/outputs', [InternalGrantReportController::class, 'outputs'])->name('outputs');
            Route::get('/researcher-performance', [InternalGrantReportController::class, 'researcherPerformance'])->name('researcher-performance');
            Route::get('/export', [InternalGrantReportController::class, 'export'])->name('export');
        });
    });

    // Database Backup
    Route::prefix('backup')->name('backup.')->middleware('role:super-admin')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::post('/create', [BackupController::class, 'create'])->name('create');
        Route::get('/download/{filename}', [BackupController::class, 'download'])->name('download');
        Route::delete('/{filename}', [BackupController::class, 'destroy'])->name('destroy');
    });
});
