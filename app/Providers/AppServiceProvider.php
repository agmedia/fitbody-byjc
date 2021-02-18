<?php

namespace App\Providers;

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
            return (new CategoryMenu())->menu()['list']['navbar'];
        });
        View::share('categories', $categories);
        //
        //
        //
        $info_menu = Cache::rememberForever('i_menu', function () {
            return Page::news(Category::find(13))->published()->latest()->get();
        });
        View::share('info_menu', $info_menu);
        //
        //
        //
        $mrav_menu = Cache::rememberForever('m_menu', function () {
            return Page::news(Category::find(18))->published()->latest()->get();
        });
        View::share('mrav_menu', $mrav_menu);
        //
        //
        // Zadnje novosti
        $latest = Cache::rememberForever('latest', function () {
            return Page::news(Category::find(10))->published()->latest()->limit(5)->get();
        });
        View::share('latest', $latest);
        //
        //
        //
        $services = Page::news(Category::find(11))->published()->inRandomOrder()->limit(3)->get();
        View::share('services', $services);
        //
        //
        //
        $products_data = Cache::rememberForever('products_data', function () {
            $list = Project::all();
            return [
                'count' => $list->count(),
                'amount' => $list->sum('amount')
            ];
        });
        View::share('products_data', $products_data);
        //
        //
        // App Settings - Admin
        /*view()->composer('*', function($view)
        {
            if (Auth::check()) {
                $view->with('settings', Cache::rememberForever('app_set', function () {
                    return Profile::settings(Auth::user()->id);
                }));
            }
        });*/
    }
}
