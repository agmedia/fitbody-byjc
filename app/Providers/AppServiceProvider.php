<?php

namespace App\Providers;

use App\Models\AppInfo;
use App\Models\Back\Custom\Project;
use App\Models\Front\Category\Category;
use App\Models\Front\Page;
use App\Models\Back\Settings\Profile;
use App\Models\CategoryMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $ttl = 60*60*2;
        //
        // Kategorije
        $categories = Cache::rememberForever('cats', function () {
            //dd((new CategoryMenu())->menu());
            return (new CategoryMenu())->menu()['list']['NAVBAR'];
        });
        View::share('categories', $categories);
        //
        //
        // Zadnje novosti
        $latest = Cache::rememberForever('latest', function () {
            //dd(Page::news(Category::find(config('settings.category.news')))->published()->latest()->limit(5)->get());
            return Page::news(Category::find(config('settings.category.news')))->published()->latest()->limit(5)->get();
        });
        View::share('latest', $latest);
        //
        //
        // App SETTINGS
        $appinfo = Cache::rememberForever('app_info', function () {
            return AppInfo::get();
        });
        View::share('appinfo', $appinfo);
        //
        //
        // USER SETTINGS
        view()->composer('*', function($view)
        {
            if (Auth::check()) {
                $view->with('settings', Profile::settings(Auth::user()->id));
            }
        });
    }
}
