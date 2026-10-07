<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $domain = config('app.url');
        $pages = [
            ['loc' => '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => '/about', 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => '/services', 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => '/why-choose-us', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => '/process', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => '/faq', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => '/contact', 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => '/privacy', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['loc' => '/terms', 'priority' => '0.5', 'changefreq' => 'yearly'],
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

        $posts = BlogPost::query()->published()->latest('published_at')->get(['slug', 'updated_at']);

        foreach ($posts as $post) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . $domain . '/blog/' . $post->slug . "</loc>\n";
            $xml .= '    <lastmod>' . $post->updated_at->format('Y-m-d') . "</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.6</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
