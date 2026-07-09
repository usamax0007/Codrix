<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $domain = config('xcodrix.domain');
        $pages = [
            ['loc' => '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => '/about', 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => '/services', 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => '/why-choose-us', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => '/process', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => '/industries', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => '/portfolio', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => '/technologies', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => '/testimonials', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => '/faq', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => '/blog', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => '/contact', 'priority' => '0.9', 'changefreq' => 'monthly'],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($pages as $page) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . $domain . $page['loc'] . "</loc>\n";
            $xml .= '    <lastmod>' . date('Y-m-d') . "</lastmod>\n";
            $xml .= '    <changefreq>' . $page['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $page['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
