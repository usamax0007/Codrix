<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'slug' => 'why-laravel-best-saas-2026',
                'title' => 'Why Laravel Is Still the Best Choice for SaaS in 2026',
                'excerpt' => 'Laravel\'s ecosystem, performance improvements, and developer experience make it the top framework for building scalable SaaS products.',
                'category' => 'Laravel',
                'read_time' => '5 min',
                'published_at' => '2026-06-15 10:00:00',
                'content' => <<<'HTML'
<p>Laravel's ecosystem, performance improvements, and developer experience make it the top framework for building scalable SaaS products in 2026.</p>
<p>From first-party packages like Horizon, Cashier, Sanctum, and Scout to a mature community of packages, Laravel reduces the time between idea and production. Teams can ship billing, queues, authentication, and search without reinventing core infrastructure.</p>
<p>Performance gains in recent PHP and Laravel releases, combined with Octane and solid caching strategies, keep SaaS apps responsive under load. Pair that with clear conventions and excellent documentation, and Laravel remains the pragmatic choice for product teams that need to move fast without sacrificing maintainability.</p>
HTML,
            ],
            [
                'slug' => 'twilio-voice-integration-guide',
                'title' => 'Building Production Twilio Voice Systems: A Complete Guide',
                'excerpt' => 'Learn how to build reliable IVR systems, call routing, and voice bots with Twilio and Laravel.',
                'category' => 'Twilio',
                'read_time' => '8 min',
                'published_at' => '2026-06-01 10:00:00',
                'content' => <<<'HTML'
<p>Learn how to build reliable IVR systems, call routing, and voice bots with Twilio and Laravel.</p>
<p>Production voice systems need more than a webhook that returns TwiML. You need idempotent call handling, clear status callbacks, queue-based processing for long-running work, and careful logging so support teams can debug failed calls.</p>
<p>In this guide we cover inbound and outbound flows, IVR menu design, agent routing patterns, and how to keep Laravel and Twilio in sync when webhooks arrive out of order. The same patterns apply whether you are building a support hotline, appointment reminders, or an AI-assisted voice bot.</p>
HTML,
            ],
            [
                'slug' => 'ai-chatbot-business-roi',
                'title' => 'How AI Chatbots Deliver 60% Support Cost Reduction',
                'excerpt' => 'Real-world case study on implementing LLM-powered customer support and measuring ROI.',
                'category' => 'AI',
                'read_time' => '6 min',
                'published_at' => '2026-05-20 10:00:00',
                'content' => <<<'HTML'
<p>Real-world case study on implementing LLM-powered customer support and measuring ROI.</p>
<p>Companies that deploy AI chatbots well do not replace humans overnight. They deflect repetitive tickets first — order status, password resets, policy questions — and escalate edge cases to agents with full conversation context.</p>
<p>In this case study we walk through how one product team cut support cost by roughly 60% by combining retrieval-augmented answers, clear handoff rules, and analytics that proved deflection quality. The lesson: ROI comes from scoped automation plus measurable guardrails, not from a chatbot alone.</p>
HTML,
            ],
            [
                'slug' => 'vue-nuxt-seo-best-practices',
                'title' => 'Vue.js vs Nuxt.js: Choosing the Right Frontend for SEO',
                'excerpt' => 'When to use SPA vs SSR for your next project and how it impacts Google rankings and AI discoverability.',
                'category' => 'Frontend',
                'read_time' => '7 min',
                'published_at' => '2026-05-10 10:00:00',
                'content' => <<<'HTML'
<p>When to use SPA vs SSR for your next project and how it impacts Google rankings and AI discoverability.</p>
<p>A pure Vue SPA can feel fast for authenticated apps, but marketing pages and content sites usually benefit from Nuxt SSR or hybrid rendering. Search engines and AI crawlers get real HTML on first request, and you still keep a modern Vue developer experience.</p>
<p>We compare routing, meta tags, caching, and when a Laravel API plus Nuxt frontend is the right split versus a simpler Blade or Inertia setup. Choose based on content needs and crawlability — not on frontend fashion alone.</p>
HTML,
            ],
            [
                'slug' => 'mvp-development-checklist',
                'title' => 'The Ultimate MVP Development Checklist for Startups',
                'excerpt' => 'Everything you need to know before building your first product — from tech stack to launch strategy.',
                'category' => 'Startups',
                'read_time' => '10 min',
                'published_at' => '2026-04-28 10:00:00',
                'content' => <<<'HTML'
<p>Everything you need to know before building your first product — from tech stack to launch strategy.</p>
<p>A strong MVP is not a half-finished product. It is the smallest version that proves a valuable outcome for a specific user. That means ruthlessly cutting nice-to-haves, validating assumptions early, and shipping instrumentation so you learn from real usage.</p>
<p>This checklist covers problem definition, scope control, stack choices that will not trap you later, launch readiness, and post-launch support. Use it before you write the first line of code — and again before you call the MVP “done.”</p>
HTML,
            ],
            [
                'slug' => 'laravel-api-design-patterns',
                'title' => 'Laravel API Design Patterns for Scalable Applications',
                'excerpt' => 'Best practices for versioning, authentication, rate limiting, and documentation in Laravel APIs.',
                'category' => 'Laravel',
                'read_time' => '9 min',
                'published_at' => '2026-04-15 10:00:00',
                'content' => <<<'HTML'
<p>Best practices for versioning, authentication, rate limiting, and documentation in Laravel APIs.</p>
<p>Scalable APIs stay predictable as clients multiply. Version early, keep resources consistent, and separate authentication concerns (Sanctum, tokens, or OAuth) from business logic. Rate limiting and clear error shapes protect both your servers and your consumers.</p>
<p>We also cover resource transformers, pagination conventions, idempotent writes, and how to keep OpenAPI docs in sync with the real endpoints your team ships. These patterns keep Laravel APIs maintainable long after the first mobile or SPA client goes live.</p>
HTML,
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::query()->updateOrCreate(
                ['slug' => $post['slug']],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'content' => $post['content'],
                    'category' => $post['category'],
                    'read_time' => $post['read_time'],
                    'is_published' => true,
                    'published_at' => $post['published_at'],
                    'meta_title' => $post['title'].' | XCodrix',
                    'meta_description' => $post['excerpt'],
                ]
            );
        }
    }
}
