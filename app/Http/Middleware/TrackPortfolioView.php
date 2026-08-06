<?php

namespace App\Http\Middleware;

use App\Models\PortfolioView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class TrackPortfolioView
{
    /**
     * Routes counted as public portfolio traffic.
     */
    private const TRACKED_ROUTES = [
        'home' => 'home',
        'projects.show' => 'project',
        'blog.show' => 'blog_post',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldTrack($request, $response)) {
            return $response;
        }

        $routeName = $request->route()?->getName();
        $viewable = match ($routeName) {
            'projects.show' => $request->route('project'),
            'blog.show' => $request->route('post'),
            default => null,
        };

        PortfolioView::create([
            'route_name' => $routeName,
            'page_type' => self::TRACKED_ROUTES[$routeName],
            'path' => '/' . ltrim($request->path(), '/'),
            'viewable_type' => $viewable ? $viewable::class : null,
            'viewable_id' => $viewable?->getKey(),
            'visitor_hash' => hash('sha256', $request->session()->getId() . '|' . config('app.key')),
            'viewed_on' => now()->toDateString(),
            'viewed_at' => now(),
        ]);

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        $routeName = $request->route()?->getName();

        return $request->isMethod('GET')
            && $response->isSuccessful()
            && isset(self::TRACKED_ROUTES[$routeName])
            && ! $request->user()
            && ! $request->is('admin/*')
            && Schema::hasTable('portfolio_views')
            && ! $this->isLikelyBot((string) $request->userAgent());
    }

    private function isLikelyBot(string $userAgent): bool
    {
        if ($userAgent === '') {
            return true;
        }

        return (bool) preg_match(
            '/bot|crawl|spider|slurp|curl|wget|python|httpclient|preview|facebookexternalhit|whatsapp|telegram/i',
            $userAgent
        );
    }
}
