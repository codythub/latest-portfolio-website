<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ExpertiseController extends Controller
{
    /**
     * Display all tools and skills.
     */
    public function index()
    {
        $tools = Tool::query()
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        $skills = Skill::query()
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('admin.expertise.index', compact('tools', 'skills'));
    }

    /**
     * Save a new tool.
     */
    public function storeTool(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('tools', 'name')],
            'logo' => ['nullable', 'file', 'mimes:svg,png,jpg,jpeg,webp', 'max:2048'],
        ]);

        $data = [
            'name' => $validated['name'],
            'is_visible' => true,
            'display_order' => Tool::query()->max('display_order') + 1,
        ];

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('tool-logos', 'public');
        }

        Tool::create($data);

        return back()->with('success', 'Tool created successfully.');
    }

    /**
     * Rename/update an existing tool.
     */
    public function updateTool(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tools', 'name')->ignore($tool),
            ],
            'logo' => ['nullable', 'file', 'mimes:svg,png,jpg,jpeg,webp', 'max:2048'],
        ]);

        $data = [
            'name' => $validated['name'],
        ];

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('tool-logos', 'public');

            $this->deleteToolLogo($tool);
        }

        $tool->update($data);

        return back()->with('success', 'Tool updated successfully.');
    }

    /**
     * Show or hide a tool on the public site.
     */
    public function toggleToolVisibility(Tool $tool)
    {
        $tool->update([
            'is_visible' => ! $tool->is_visible,
        ]);

        return back()->with('success', 'Tool visibility updated successfully.');
    }

    /**
     * Move a tool up or down.
     */
    public function moveTool(Request $request, Tool $tool)
    {
        $this->moveItem($request, $tool, Tool::class);

        return back()->with('success', 'Tool order updated successfully.');
    }

    /**
     * Delete a tool.
     */
    public function destroyTool(Tool $tool)
    {
        $this->deleteToolLogo($tool);

        $tool->delete();

        return redirect()
            ->route('admin.expertise.index')
            ->with('success', 'Tool deleted successfully.');
    }

    /**
     * Save a new skill.
     */
    public function storeSkill(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('skills', 'name')],
        ]);

        $validated['is_visible'] = true;
        $validated['display_order'] = Skill::query()->max('display_order') + 1;

        Skill::create($validated);

        return back()->with('success', 'Skill created successfully.');
    }

    /**
     * Rename/update an existing skill.
     */
    public function updateSkill(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('skills', 'name')->ignore($skill),
            ],
        ]);

        $skill->update($validated);

        return back()->with('success', 'Skill updated successfully.');
    }

    /**
     * Show or hide a skill on the public site.
     */
    public function toggleSkillVisibility(Skill $skill)
    {
        $skill->update([
            'is_visible' => ! $skill->is_visible,
        ]);

        return back()->with('success', 'Skill visibility updated successfully.');
    }

    /**
     * Move a skill up or down.
     */
    public function moveSkill(Request $request, Skill $skill)
    {
        $this->moveItem($request, $skill, Skill::class);

        return back()->with('success', 'Skill order updated successfully.');
    }

    /**
     * Delete a skill.
     */
    public function destroySkill(Skill $skill)
    {
        $skill->delete();

        return redirect()
            ->route('admin.expertise.index')
            ->with('success', 'Skill deleted successfully.');
    }

    /**
     * Swap display order with the previous or next item.
     */
    private function moveItem(Request $request, Model $item, string $modelClass): void
    {
        $validated = $request->validate([
            'direction' => ['required', Rule::in(['up', 'down'])],
        ]);

        $orderedItems = $modelClass::query()
            ->orderBy('display_order')
            ->orderBy('name')
            ->get()
            ->values();

        $currentIndex = $orderedItems
            ->search(fn ($orderedItem) => $orderedItem->id === $item->id);

        if ($currentIndex === false) {
            return;
        }

        $targetIndex = $validated['direction'] === 'up'
            ? $currentIndex - 1
            : $currentIndex + 1;

        if (! isset($orderedItems[$targetIndex])) {
            return;
        }

        $targetItem = $orderedItems[$targetIndex];
        $currentOrder = $item->display_order;
        $targetOrder = $targetItem->display_order;

        $item->update([
            'display_order' => $targetOrder,
        ]);

        $targetItem->update([
            'display_order' => $currentOrder,
        ]);

        if ($currentOrder === $targetOrder) {
            foreach ($orderedItems as $index => $orderedItem) {
                $orderedItem->update([
                    'display_order' => $index,
                ]);
            }
        }
    }

    /**
     * Delete a stored tool logo if one exists.
     */
    private function deleteToolLogo(Tool $tool): void
    {
        if (! $tool->logo_path) {
            return;
        }

        Storage::disk('public')->delete($tool->logo_path);
    }
}
