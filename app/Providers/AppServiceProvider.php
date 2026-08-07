<?php

namespace App\Providers;

use App\Models\AboutSetting;
use App\Models\FaqSetting;
use App\Models\IndustrySetting;
use App\Models\PortfolioSetting;
use App\Models\ProcessSetting;
use App\Models\Service;
use App\Models\ServiceSetting;
use App\Models\SiteSetting;
use App\Models\TechnologySetting;
use App\Models\TestimonialSetting;
use App\Models\WhyChooseUsSetting;
use Illuminate\Pagination\Paginator;
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
        Paginator::useTailwind();

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

        if (Schema::hasTable('why_choose_us_settings')) {
            View::share('whyChooseUsSettings', WhyChooseUsSetting::current());
        }

        if (Schema::hasTable('industry_settings')) {
            View::share('industrySettings', IndustrySetting::current());
        }

        if (Schema::hasTable('technology_settings')) {
            View::share('technologySettings', TechnologySetting::current());
        }

        if (Schema::hasTable('testimonial_settings')) {
            View::share('testimonialSettings', TestimonialSetting::current());
        }

        // Guard on column too: `services` may exist before the CMS migration adds `is_active`.
        // Querying too early (e.g. during `artisan migrate`) would break boot.
        if (Schema::hasTable('services') && Schema::hasColumn('services', 'is_active')) {
            View::share(
                'footerServices',
                Service::query()->active()->ordered()->take(6)->get()
            );
        }
    }
}
