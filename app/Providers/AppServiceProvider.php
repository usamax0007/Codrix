<?php

namespace App\Providers;

use App\Models\AboutSetting;
use App\Models\FaqSetting;
use App\Models\PortfolioSetting;
use App\Models\ProcessSetting;
use App\Models\Service;
use App\Models\ServiceSetting;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if (Schema::hasTable('site_settings')) {
            View::share('siteSettings', SiteSetting::current());
        }

        if (Schema::hasTable('about_settings')) {
            View::share('aboutSettings', AboutSetting::current());
        }

        if (Schema::hasTable('service_settings')) {
            View::share('serviceSettings', ServiceSetting::current());
        }

        if (Schema::hasTable('process_settings')) {
            View::share('processSettings', ProcessSetting::current());
        }

        if (Schema::hasTable('faq_settings')) {
            View::share('faqSettings', FaqSetting::current());
        }

        if (Schema::hasTable('portfolio_settings')) {
            View::share('portfolioSettings', PortfolioSetting::current());
        }

        if (Schema::hasTable('services')) {
            View::share(
                'footerServices',
                Service::query()->active()->ordered()->take(6)->get()
            );
        }
    }
}
