<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    // These are the project fields Laravel is allowed to save
    // from our admin form later.
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'project_type',
        'subtitle',
        'summary',
        'role',
        'timeline',
        'industry',
        'year',
        'tag_label',
        'tags',
        'thumbnail',
        'cover_image',
        'external_link_label',
        'external_link_url',
        'is_published',
        'display_order',
    ];

    // The tags column is stored as JSON in PostgreSQL.
    // This tells Laravel to return it as a normal PHP array.
    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_published' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    // One project can have many case-study sections.
    public function sections()
    {
        return $this->hasMany(ProjectSection::class)
            ->orderBy('display_order');
    }

    // One project can have many gallery images.
    public function galleryImages()
    {
        return $this->hasMany(ProjectGalleryImage::class)
            ->orderBy('display_order');
    }

    /**
     * Category assigned to this project.
     */
    public function category(): BelongsTo
    {
         return $this->belongsTo(Category::class);
    }
}