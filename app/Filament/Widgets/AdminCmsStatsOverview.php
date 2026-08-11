<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BlogPosts\BlogPostResource;
use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Resources\Portfolios\PortfolioResource;
use App\Filament\Resources\Services\ServiceResource;
use App\Filament\Resources\Testimonials\TestimonialResource;
use App\Services\Admin\AdminDashboardService;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminCmsStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Website content';

    protected ?string $description = 'Published inventory for the public site.';

    protected int|array|null $columns = 4;

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $stats = app(AdminDashboardService::class)->stats();

        return [
            Stat::make('Blog posts', number_format($stats['blog_posts']))
                ->description($stats['blog_published'].' published')
                ->descriptionIcon(Heroicon::OutlinedNewspaper)
                ->color('primary')
                ->url(BlogPostResource::getUrl('index')),
            Stat::make('Portfolios', number_format($stats['portfolios']))
                ->description($stats['portfolios_active'].' active')
                ->descriptionIcon(Heroicon::OutlinedPhoto)
                ->color('info')
                ->url(PortfolioResource::getUrl('index')),
            Stat::make('Services', number_format($stats['services']))
                ->description($stats['services_active'].' active')
                ->descriptionIcon(Heroicon::OutlinedWrenchScrewdriver)
                ->color('success')
                ->url(ServiceResource::getUrl('index')),
            Stat::make('FAQs', number_format($stats['faqs']))
                ->description($stats['testimonials'].' testimonials')
                ->descriptionIcon(Heroicon::OutlinedQuestionMarkCircle)
                ->color('warning')
                ->url(FaqResource::getUrl('index')),
            Stat::make('Testimonials', number_format($stats['testimonials']))
                ->description('Customer quotes')
                ->descriptionIcon(Heroicon::OutlinedChatBubbleLeftRight)
                ->color('gray')
                ->url(TestimonialResource::getUrl('index')),
        ];
    }
}
