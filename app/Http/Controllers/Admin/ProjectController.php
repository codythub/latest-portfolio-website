<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    // This gets all projects and shows them on the projects page.
    public function index()
    {
        $projects = Project::with('category')
        ->orderBy('display_order')
        ->latest()
        ->get();

        return view('admin.projects.index', [
            'projects' => $projects,
        ]);
    }

    // This opens the form where we can add a new project.
    public function create()
    {
        // Load project categories for the category dropdown.
        $categories = Category::query()
            ->where('type', Category::TYPE_PROJECT)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('admin.projects.create', compact('categories'));
    }

    // This receives the form information and saves it.
    public function store(Request $request)
    {
        // First, Laravel checks that the important fields are correct.
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
    
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
                    ->where(fn ($query) => $query
                        ->where('type', Category::TYPE_PROJECT)),
            ],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'role' => ['nullable', 'string', 'max:255'],
            'timeline' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'max:20'],

            'tag_label' => ['nullable', 'string', 'max:255'],
            // Tags will come from separate input fields.
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'string', 'max:100'],

            // Case-study sections such as Overview and The Problem.
            'sections' => ['nullable', 'array'],
            'sections.*.title' => ['nullable', 'string', 'max:255'],
            'sections.*.body' => ['nullable', 'string'],
            'sections.*.is_visible' => ['nullable', 'boolean'],

            'external_link_label' => ['nullable', 'string', 'max:255'],
            'external_link_url' => ['nullable', 'url', 'max:2048'],
            'display_order' => ['nullable', 'integer', 'min:0'],

            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'gallery' => ['nullable', 'array'],
            'gallery.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'gallery.*.alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        // We create the slug automatically from the title.
        // Example: SilentCare becomes silentcare.
        $validated['slug'] = Str::slug($validated['title']);

        // Keep the legacy non-null column populated until it can be safely
        // removed or given a database-level default in a later migration.
        $validated['project_type'] = 'development';

        // Remove empty tag fields and extra spaces before saving.
        $validated['tags'] = collect($validated['tags'] ?? [])
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->values()
            ->all();

        // A checkbox is only sent when it is ticked.
        // This changes it into true or false.
        $validated['is_published'] = $request->boolean('is_published');

        // If no display order was entered, use zero.
        $validated['display_order'] = $validated['display_order'] ?? 0;

        // Keep the sections separate from the main project information.
        $sections = $validated['sections'] ?? [];

        $galleryItems = $validated['gallery'] ?? [];

        unset($validated['sections']);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request
                ->file('thumbnail')
                ->store('projects/thumbnails', 'public');
        }

        // Save the new project inside PostgreSQL.
        $project = Project::create($validated);

        // Save each case-study section under this project.
        foreach ($sections as $index => $section) {
            // Skip completely empty sections.
            if (empty($section['title']) && empty($section['body'])) {
                continue;
            }

            $project->sections()->create([
                'title' => $section['title'] ?? '',
                'body' => $section['body'] ?? null,
                'display_order' => $index,
                'is_visible' => $section['is_visible'] ?? false,
            ]);
        }

        // Save each gallery image under this project.
        foreach ($galleryItems as $index => $galleryItem) {
            if (!isset($galleryItem['image'])) {
                continue;
            }

            $imagePath = $galleryItem['image']
                ->store('projects/gallery', 'public');

            $project->galleryImages()->create([
                'image' => $imagePath,
                'alt_text' => $galleryItem['alt_text'] ?? '',
                'display_order' => $index,
            ]);
        }

        // After saving, return to the projects page.
        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project added successfully.');
    }

        // This opens the edit form for one project.
        public function edit(Project $project)
        {
            // Load the project's related content.
            $project->load([
                'sections',
                'galleryImages',
            ]);

            // Load project categories for the category dropdown.
            $categories = Category::query()
                ->where('type', Category::TYPE_PROJECT)
                ->orderBy('display_order')
                ->orderBy('name')
                ->get();

            return view('admin.projects.edit', compact(
                'project',
                'categories'
            ));
        }

    // This receives the edited form and updates the project.
    public function update(Request $request, Project $project)
    {
        // Laravel checks the edited information first.
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
                    ->where(fn ($query) => $query
                        ->where('type', Category::TYPE_PROJECT)),
            ],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'role' => ['nullable', 'string', 'max:255'],
            'timeline' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'max:20'],

            'tag_label' => ['nullable', 'string', 'max:255'],
            // Tags will come from separate input fields.
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'string', 'max:100'],

            // Case-study sections such as Overview and The Problem.
            'sections' => ['nullable', 'array'],
            'sections.*.title' => ['nullable', 'string', 'max:255'],
            'sections.*.body' => ['nullable', 'string'],
            'sections.*.is_visible' => ['nullable', 'boolean'],

            'external_link_label' => ['nullable', 'string', 'max:255'],
            'external_link_url' => ['nullable', 'url', 'max:2048'],
            'display_order' => ['nullable', 'integer', 'min:0'],

            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'gallery' => ['nullable', 'array'],
            'gallery.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'gallery.*.alt_text' => ['nullable', 'string', 'max:255'],

            'remove_gallery_images' => ['nullable', 'array'],
            'remove_gallery_images.*' => ['integer', 'exists:project_gallery_images,id'],
        ]);

        // If the project title changes,
        // we create a new slug from the new title.
        $validated['slug'] = Str::slug($validated['title']);

         // Remove empty tag fields and extra spaces before saving.
        $validated['tags'] = collect($validated['tags'] ?? [])
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->values()
            ->all();
            
        // Turn the publish checkbox into true or false.
        $validated['is_published'] = $request->boolean('is_published');

        // If display order is empty, use zero.
        $validated['display_order'] = $validated['display_order'] ?? 0;

        // Keep the sections separate from the main project information.
        $sections = $validated['sections'] ?? [];

        $galleryItems = $validated['gallery'] ?? [];
        $galleryImagesToRemove = $validated['remove_gallery_images'] ?? [];

        unset(
            $validated['sections'],
            $validated['gallery'],
            $validated['remove_gallery_images']
        );

        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }

            $validated['thumbnail'] = $request
                ->file('thumbnail')
                ->store('projects/thumbnails', 'public');
        }

        // Update the main project information.
        $project->update($validated);

        // This checks that the image actually belongs to the current project
        //before deleting both the file and database record.
        foreach ($galleryImagesToRemove as $galleryImageId) {
            $galleryImage = $project->galleryImages()
                ->whereKey($galleryImageId)
                ->first();

            if (!$galleryImage) {
                continue;
            }

            Storage::disk('public')->delete($galleryImage->image);

            $galleryImage->delete();
        }

        // Save each new gallery image under this project.
        foreach ($galleryItems as $index => $galleryItem) {
            if (!isset($galleryItem['image'])) {
                continue;
            }

            $imagePath = $galleryItem['image']
                ->store('projects/gallery', 'public');

            $project->galleryImages()->create([
                'image' => $imagePath,
                'alt_text' => $galleryItem['alt_text'] ?? '',
                'display_order' => $project->galleryImages()->count() + $index,
            ]);
        }

        // Remove the old sections before saving the edited ones.
        $project->sections()->delete();

        // Save the edited case-study sections.
        foreach ($sections as $index => $section) {
            // Ignore a section when both fields are empty.
            if (empty($section['title']) && empty($section['body'])) {
                continue;
            }

            $project->sections()->create([
                'title' => $section['title'] ?? '',
                'body' => $section['body'] ?? null,
                'display_order' => $index,
                'is_visible' => $section['is_visible'] ?? false,
            ]);
        }
        
        // Go back to the projects page after saving.
        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    // This deletes one project from the database.
    public function destroy(Project $project)
    {   
        //Delete the thumbnail image from storage if it exists

        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }

        foreach ($project->galleryImages as $galleryImage) {
            Storage::disk('public')->delete($galleryImage->image);
        }

        // Delete the selected project.
        $project->delete();

        // Return to the projects page.
        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
