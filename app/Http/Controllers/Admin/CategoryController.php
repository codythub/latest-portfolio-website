<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display all project and blog categories.
     */
    public function index()
    {
        $projectCategories = Category::query()
            ->where('type', Category::TYPE_PROJECT)
            ->withCount('projects')
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        $blogCategories = Category::query()
            ->where('type', Category::TYPE_BLOG)
            ->withCount('posts')
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact(
            'projectCategories',
            'blogCategories'
        ));
    }

    /**
     * Display the category creation form.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    
    /**
     * Save a new project or blog category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => [
                'required',
                Rule::in([
                    Category::TYPE_PROJECT,
                    Category::TYPE_BLOG,
                ]),
            ],
            'project_classification' => [
                'nullable',
                'required_if:type,' . Category::TYPE_PROJECT,
                Rule::in([
                    Category::PROJECT_CLASSIFICATION_DESIGN,
                    Category::PROJECT_CLASSIFICATION_DEVELOPMENT,
                ]),
            ],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_visible'] = true;
        $validated['project_classification'] = $validated['type'] === Category::TYPE_PROJECT
            ? $validated['project_classification']
            : null;

        // Place the new category after the existing categories of the same type.
        $validated['display_order'] = Category::query()
            ->where('type', $validated['type'])
            ->max('display_order') + 1;

        $duplicateExists = Category::query()
            ->where('type', $validated['type'])
            ->where('slug', $validated['slug'])
            ->exists();

        if ($duplicateExists) {
            return back()
                ->withErrors([
                    $validated['type'] . '_category' =>
                        'This category already exists.',
                ])
                ->withInput();
        }

        Category::create($validated);

        return back()->with(
            'success',
            'Category created successfully.'
        );
    }



    /**
     * Display the category edit form.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Rename/update an existing category.
     */
    public function update(Request $request, Category $category)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        if ($category->type === Category::TYPE_PROJECT) {
            $rules['project_classification'] = [
                'required',
                Rule::in([
                    Category::PROJECT_CLASSIFICATION_DESIGN,
                    Category::PROJECT_CLASSIFICATION_DEVELOPMENT,
                ]),
            ];
        }

        $validated = $request->validate($rules);

        $validated['slug'] = Str::slug($validated['name']);

        if ($category->type !== Category::TYPE_PROJECT) {
            unset($validated['project_classification']);
        }

        $duplicateExists = Category::query()
            ->where('type', $category->type)
            ->where('slug', $validated['slug'])
            ->whereKeyNot($category->id)
            ->exists();

        if ($duplicateExists) {
            return back()
                ->withErrors([
                    'category_' . $category->id =>
                        'This category already exists.',
                ])
                ->withInput();
        }

        $category->update($validated);

        return back()->with(
            'success',
            'Category updated successfully.'
        );
    }

    /**
     * Show or hide a category on the public site.
     */
    public function toggleVisibility(Category $category)
    {
        $category->update([
            'is_visible' => ! $category->is_visible,
        ]);

        return back()->with(
            'success',
            'Category visibility updated successfully.'
        );
    }

    /**
     * Move a category up or down within its category type.
     */
    public function move(Request $request, Category $category)
    {
        $validated = $request->validate([
            'direction' => ['required', Rule::in(['up', 'down'])],
        ]);

        $orderedCategories = Category::query()
            ->where('type', $category->type)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get()
            ->values();

        $currentIndex = $orderedCategories
            ->search(fn ($item) => $item->id === $category->id);

        if ($currentIndex === false) {
            return back();
        }

        $targetIndex = $validated['direction'] === 'up'
            ? $currentIndex - 1
            : $currentIndex + 1;

        // Do nothing when the category is already first or last.
        if (! isset($orderedCategories[$targetIndex])) {
            return back();
        }

        $targetCategory = $orderedCategories[$targetIndex];

        // Swap their positions.
        $currentOrder = $category->display_order;
        $targetOrder = $targetCategory->display_order;

        $category->update([
            'display_order' => $targetOrder,
        ]);

        $targetCategory->update([
            'display_order' => $currentOrder,
        ]);

        // Handle categories that currently share the same display order.
        if ($currentOrder === $targetOrder) {
            foreach ($orderedCategories as $index => $item) {
                $item->update([
                    'display_order' => $index,
                ]);
            }
        }

        return back()->with('success', 'Category order updated successfully.');
    }

    /**
     * Delete an unused category.
     */
    public function destroy(Category $category)
    {
        $hasPosts = $category->posts()->exists();
        $hasProjects = $category->projects()->exists();

        // Do not leave posts or projects without a category accidentally.
        if ($hasPosts || $hasProjects) {
            return back()->with(
                'error',
                'This category cannot be deleted because it is currently in use.'
            );
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
