<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\PortfolioView;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $period = in_array($request->query('period'), ['7', '30', '12m'], true)
            ? $request->query('period')
            : '7';

        $hasViewsTable = Schema::hasTable('portfolio_views');
        $hasContactMessagesTable = Schema::hasTable('contact_messages');

        $analytics = [
            'total_views' => $hasViewsTable ? PortfolioView::query()->count() : 0,
            'unique_visitors' => $hasViewsTable
                ? PortfolioView::query()->distinct('visitor_hash')->count('visitor_hash')
                : 0,
            'views_this_month' => $hasViewsTable
                ? PortfolioView::query()
                    ->whereBetween('viewed_at', [
                        now()->startOfMonth(),
                        now()->endOfMonth(),
                    ])
                    ->count()
                : 0,
            'unread_messages' => $hasContactMessagesTable
                ? ContactMessage::query()
                    ->where('is_archived', false)
                    ->where('is_read', false)
                    ->count()
                : 0,
        ];

        $contentOverview = [
            'total_projects' => Project::query()->count(),
            'published_projects' => Project::query()->where('is_published', true)->count(),
            'draft_projects' => Project::query()->where('is_published', false)->count(),
            'blog_posts' => Post::query()->count(),
            'draft_blog_posts' => Post::query()->where('is_published', false)->count(),
        ];

        $latestMessages = $hasContactMessagesTable
            ? ContactMessage::query()->latest()->limit(3)->get()
            : collect();

        return view('admin.dashboard', [
            'analytics' => $analytics,
            'chartData' => $hasViewsTable ? $this->trafficChartData($period) : $this->emptyChartData($period),
            'period' => $period,
            'mostViewedContent' => $hasViewsTable ? $this->mostViewedContent() : collect(),
            'contentOverview' => $contentOverview,
            'latestMessages' => $latestMessages,
            'needsAttention' => $this->needsAttention($analytics, $contentOverview),
            'recentContent' => $this->recentContent(),
        ]);
    }

    private function trafficChartData(string $period): array
    {
        if ($period === '12m') {
            return $this->monthlyTrafficChartData();
        }

        $days = $period === '30' ? 30 : 7;
        $start = now()->subDays($days - 1)->startOfDay();

        $counts = PortfolioView::query()
            ->selectRaw('viewed_on, COUNT(*) as aggregate')
            ->where('viewed_at', '>=', $start)
            ->groupBy('viewed_on')
            ->pluck('aggregate', 'viewed_on');

        $labels = [];
        $values = [];

        for ($date = $start->copy(); $date->lte(now()); $date->addDay()) {
            $key = $date->toDateString();
            $labels[] = $date->format($days === 7 ? 'D' : 'd M');
            $values[] = (int) ($counts[$key] ?? 0);
        }

        return compact('labels', 'values');
    }

    private function monthlyTrafficChartData(): array
    {
        $start = now()->startOfMonth()->subMonths(11);

        $counts = PortfolioView::query()
            ->selectRaw("to_char(viewed_at, 'YYYY-MM') as period, COUNT(*) as aggregate")
            ->where('viewed_at', '>=', $start)
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('aggregate', 'period');

        $labels = [];
        $values = [];

        for ($date = $start->copy(); $date->lte(now()); $date->addMonth()) {
            $key = $date->format('Y-m');
            $labels[] = $date->format('M y');
            $values[] = (int) ($counts[$key] ?? 0);
        }

        return compact('labels', 'values');
    }

    private function emptyChartData(string $period): array
    {
        if ($period === '12m') {
            $start = now()->startOfMonth()->subMonths(11);
            $labels = [];

            for ($date = $start->copy(); $date->lte(now()); $date->addMonth()) {
                $labels[] = $date->format('M y');
            }

            return [
                'labels' => $labels,
                'values' => array_fill(0, count($labels), 0),
            ];
        }

        $days = $period === '30' ? 30 : 7;
        $start = now()->subDays($days - 1)->startOfDay();
        $labels = [];

        for ($date = $start->copy(); $date->lte(now()); $date->addDay()) {
            $labels[] = $date->format($days === 7 ? 'D' : 'd M');
        }

        return [
            'labels' => $labels,
            'values' => array_fill(0, count($labels), 0),
        ];
    }

    private function mostViewedContent()
    {
        return PortfolioView::query()
            ->select('viewable_type', 'viewable_id', DB::raw('COUNT(*) as views_count'))
            ->whereNotNull('viewable_type')
            ->whereNotNull('viewable_id')
            ->groupBy('viewable_type', 'viewable_id')
            ->orderByDesc('views_count')
            ->limit(12)
            ->get()
            ->map(function ($row) {
                $model = $row->viewable_type::query()->find($row->viewable_id);

                if (! $model) {
                    return null;
                }

                return [
                    'title' => $model->title,
                    'type' => $row->viewable_type === Project::class ? 'Project' : 'Blog post',
                    'views_count' => (int) $row->views_count,
                    'url' => $row->viewable_type === Project::class
                        ? route('projects.show', $model)
                        : route('blog.show', $model),
                ];
            })
            ->filter()
            ->take(3)
            ->values();
    }

    private function needsAttention(array $analytics, array $contentOverview): array
    {
        return array_values(array_filter([
            $analytics['unread_messages'] > 0 ? [
                'label' => $analytics['unread_messages'] . ' unread ' . Str::plural('message', $analytics['unread_messages']) . ' waiting for a reply',
                'href' => route('admin.contact-messages.index', ['filter' => 'unread']),
                'action' => 'Inbox',
            ] : null,
            $contentOverview['draft_projects'] > 0 ? [
                'label' => $contentOverview['draft_projects'] . ' draft ' . Str::plural('project', $contentOverview['draft_projects']) . ' not published yet',
                'href' => route('admin.projects.index'),
                'action' => 'Projects',
            ] : null,
            $contentOverview['draft_blog_posts'] > 0 ? [
                'label' => $contentOverview['draft_blog_posts'] . ' draft blog ' . Str::plural('post', $contentOverview['draft_blog_posts']) . ' awaiting review',
                'href' => route('admin.posts.index'),
                'action' => 'Blog',
            ] : null,
        ]));
    }

    private function recentContent()
    {
        $projects = Project::query()
            ->latest('updated_at')
            ->limit(5)
            ->get()
            ->map(fn (Project $project) => [
                'title' => $project->title,
                'type' => 'Project',
                'status' => $project->is_published ? 'Published updated' : 'Draft updated',
                'date' => Carbon::parse($project->updated_at),
                'href' => route('admin.projects.edit', $project),
            ]);

        $posts = Post::query()
            ->latest('updated_at')
            ->limit(5)
            ->get()
            ->map(fn (Post $post) => [
                'title' => $post->title,
                'type' => 'Blog post',
                'status' => $post->is_published ? 'Published updated' : 'Draft saved',
                'date' => Carbon::parse($post->updated_at),
                'href' => route('admin.posts.edit', $post),
            ]);

        return $projects
            ->concat($posts)
            ->sortByDesc('date')
            ->take(6)
            ->values();
    }
}
