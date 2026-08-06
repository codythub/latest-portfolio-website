<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectGalleryImage extends Model
{
    protected $fillable = [
        'project_id',
        'image',
        'alt_text',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'display_order' => 'integer',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}