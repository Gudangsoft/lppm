<?php

namespace App\Providers;

use App\Models\Language;
use App\Models\Menu;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share data with all frontend views
        View::composer('frontend.*', function ($view) {
            // Settings
            $settings = cache()->remember('site_settings', 3600, function () {
                return Setting::pluck('value', 'key')->toArray();
            });
            
            // Active Languages
            $activeLanguages = cache()->remember('active_languages', 3600, function () {
                return Language::where('is_active', true)->orderBy('order')->get();
            });
            
            // Main Menu
            $mainMenu = cache()->remember('main_menu_' . app()->getLocale(), 3600, function () {
                $menu = Menu::whereIn('location', ['header', 'main'])->first();
                if ($menu) {
                    return $menu->items()
                        ->whereNull('parent_id')
                        ->where('is_active', true)
                        ->with(['children' => function ($query) {
                            $query->where('is_active', true)->orderBy('order');
                        }])
                        ->orderBy('order')
                        ->get();
                }
                return collect();
            });
            
            $view->with(compact('settings', 'activeLanguages', 'mainMenu'));
        });
        
        // Share data with all admin views
        View::composer('admin.*', function ($view) {
            $languages = cache()->remember('admin_languages', 3600, function () {
                return Language::where('is_active', true)->orderBy('order')->get();
            });
            
            $settings = cache()->remember('admin_settings', 3600, function () {
                return Setting::all()->pluck('value', 'key')->toArray();
            });
            
            $view->with(compact('languages', 'settings'));
        });
    }
}
