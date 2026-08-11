<?php

namespace App\Services\Admin;

use App\Enums\UserRole;
use App\Models\Attendance;
use App\Models\BlogPost;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\Service;
use App\Models\Task;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Carbon;

class AdminDashboardService
{
    /**
     * @return array{
     *     users_total: int,
     *     users_admin: int,
     *     users_staff: int,
     *     projects: int,
     *     tasks: int,
     *     tasks_completed: int,
     *     contacts: int,
     *     contacts_week: int,
     *     blog_posts: int,
     *     blog_published: int,
     *     portfolios: int,
     *     portfolios_active: int,
     *     services: int,
     *     services_active: int,
     *     faqs: int,
     *     testimonials: int,
     *     attendance_today: int,
     *     attendance_open: int
     * }
     */
    public function stats(): array
    {
        $today = Carbon::today()->toDateString();

        return [
            'users_total' => User::query()->where('role', '!=', UserRole::SuperAdmin)->count(),
            'users_admin' => User::query()->where('role', UserRole::Admin)->count(),
            'users_staff' => User::query()->where('role', UserRole::User)->count(),
            'projects' => Project::query()->count(),
            'tasks' => Task::query()->count(),
            'tasks_completed' => Task::query()
                ->whereHas('status', fn ($query) => $query->where('is_completed', true))
                ->count(),
            'contacts' => Contact::query()->count(),
            'contacts_week' => Contact::query()
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            'blog_posts' => BlogPost::query()->count(),
            'blog_published' => BlogPost::query()->published()->count(),
            'portfolios' => Portfolio::query()->count(),
            'portfolios_active' => Portfolio::query()->where('is_active', true)->count(),
            'services' => Service::query()->count(),
            'services_active' => Service::query()->where('is_active', true)->count(),
            'faqs' => Faq::query()->count(),
            'testimonials' => Testimonial::query()->count(),
            'attendance_today' => Attendance::query()
                ->whereDate('work_date', $today)
                ->whereNotNull('check_in_at')
                ->count(),
            'attendance_open' => Attendance::query()
                ->whereDate('work_date', $today)
                ->whereNotNull('check_in_at')
                ->whereNull('check_out_at')
                ->count(),
        ];
    }
}
