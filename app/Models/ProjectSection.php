<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectSection extends Model
{
    // These are the fields Laravel is allowed to save.
    protected $fillable = [
        'project_id',
        'title',
        'body',
        'display_order',
        'is_visible',
    ];

    // Convert database values into useful PHP types.
    protected function casts(): array
    {
        return [
            'display_order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    // This section belongs to one project.
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}