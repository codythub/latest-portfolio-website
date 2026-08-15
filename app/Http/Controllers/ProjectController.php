<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Skill;
use App\Models\Tool;
use App\Models\User;

class ProjectController extends Controller
{
        
    public function index(Request $request)
    {
        $selectedCategory = $request->query('category');
        $selectedSort = $request->query('sort') === 'earliest'
            ? 'earliest'
            : 'latest';
        $sortDirection = $selectedSort === 'earliest' ? 'asc' : 'desc';

        $categories = Category::query()
            ->where('type', Category::TYPE_PROJECT)
            ->where('is_visible', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        $blogCategories = Category::query()
            ->where('type', Category::TYPE_BLOG)
            ->where('is_visible', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        $tools = Tool::query()
            ->where('is_visible', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        $skills = Skill::query()
            ->where('is_visible', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        $profile = User::query()->first();

        // Find the selected visible category, whether it belongs to projects or blogs.
        $activeCategory = $selectedCategory
            ? Category::query()
                ->where('slug', $selectedCategory)
                ->where('is_visible', true)
                ->whereIn('type', [
                    Category::TYPE_PROJECT,
                    Category::TYPE_BLOG,
                ])
                ->first()
            : null;

        // Show blog posts when Blog is selected or a blog category is active.
        $showBlog = $request->query('type') === 'blog'
            || $activeCategory?->type === Category::TYPE_BLOG;

        $projects = Project::query()
            ->with('category')
            ->where('is_published', true)
            ->whereHas('category', function ($query) {
                $query
                    ->where('type', Category::TYPE_PROJECT)
                    ->where('is_visible', true);
            })

            ->when(
                $activeCategory?->type === Category::TYPE_PROJECT,
                function ($query) use ($activeCategory) {
                    $query->where('category_id', $activeCategory->id);
                }
            )

            ->orderByRaw("NULLIF(year, '') IS NULL")
            ->orderBy('year', $sortDirection)
            ->orderBy('created_at', $sortDirection)
            ->orderBy('display_order')
            ->paginate(6, ['*'], 'project_page')
            ->withQueryString();

        $posts = Post::query()
            ->with('category')
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->whereHas('category', function ($query) {
                $query
                    ->where('type', Category::TYPE_BLOG)
                    ->where('is_visible', true);
            })
            ->when(
                $activeCategory?->type === Category::TYPE_BLOG,
                function ($query) use ($activeCategory) {
                    $query->where('category_id', $activeCategory->id);
                }
            )
            ->orderBy('published_at', $sortDirection)
            ->orderBy('display_order')
            ->paginate(5, ['*'], 'blog_page')
            ->withQueryString();


        return view('home', [
            'projects' => $projects,
            'categories' => $categories,
            'blogCategories' => $blogCategories,
            'selectedCategory' => $selectedCategory,
            'selectedSort' => $selectedSort,
            'activeCategory' => $activeCategory,
            'posts' => $posts,
            'showBlog' => $showBlog,
            'tools' => $tools,
            'skills' => $skills,
            'profile' => $profile,
        ]);
    }

    public function show(Project $project)
    {
        abort_unless($project->is_published, 404);

        $project->load([
            'category',
            'sections',
            'galleryImages',
        ]);

        $nextProject = Project::query()
            ->where('is_published', true)
            ->where('display_order', '>', $project->display_order)
            ->orderBy('display_order')
            ->first();

        if (!$nextProject) {
            $nextProject = Project::query()
                ->where('is_published', true)
                ->whereKeyNot($project->id)
                ->orderBy('display_order')
                ->first();
        }

        return view('projects.show', [
            'project' => $project,
            'nextProject' => $nextProject,
        ]);
    }
}
