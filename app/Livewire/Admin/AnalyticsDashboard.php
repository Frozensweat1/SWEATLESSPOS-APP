<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\WebsitePageView;
use App\Models\WebsiteContactFormSubmission;
use App\Models\Testimonial;
use App\Models\NewsletterSubscriber;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class AnalyticsDashboard extends Component
{
    public $dateRange = 'month';
    public $selectedPage = 'all';

    public function mount()
    {
        \App\Models\ActivityLog::log(
            'viewed_analytics',
            'Admin viewed analytics dashboard'
        );
    }

    private function getDateStart()
    {
        return match ($this->dateRange) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'year' => now()->subYear(),
            default => now()->subMonth(),
        };
    }

    public function getTotalViewsProperty()
    {
        return WebsitePageView::where('created_at', '>=', $this->getDateStart())
            ->count();
    }

    public function getUniqueVisitorsProperty()
    {
        return WebsitePageView::where('created_at', '>=', $this->getDateStart())
            ->distinct('ip_address')
            ->count('ip_address');
    }

    public function getTopPagesProperty()
    {
        return WebsitePageView::where('created_at', '>=', $this->getDateStart())
            ->groupBy('page_slug', 'page_title')
            ->selectRaw('page_slug, page_title, count(*) as views')
            ->orderByDesc('views')
            ->limit(5)
            ->get();
    }

    public function getContactSubmissionsProperty()
    {
        return WebsiteContactFormSubmission::where('created_at', '>=', $this->getDateStart())
            ->count();
    }

    public function getUnreadContactsProperty()
    {
        return WebsiteContactFormSubmission::where('status', 'new')->count();
    }

    public function getTestimonialsProperty()
    {
        return Testimonial::where('created_at', '>=', $this->getDateStart())
            ->count();
    }

    public function getNewsletterSubscribersProperty()
    {
        return NewsletterSubscriber::active()->count();
    }

    public function getNewSubscribersProperty()
    {
        return NewsletterSubscriber::where('subscribed_at', '>=', $this->getDateStart())
            ->count();
    }

    public function getPageViewsChartProperty()
    {
        $views = WebsitePageView::where('created_at', '>=', $this->getDateStart())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $views->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d')),
            'data' => $views->pluck('count'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.analytics-dashboard');
    }
}
