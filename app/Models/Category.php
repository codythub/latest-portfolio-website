<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    public const TYPE_PROJECT = 'project';
    public const TYPE_BLOG = 'blog';
    public const PROJECT_CLASSIFICATION_DESIGN = 'design';
    public const PROJECT_CLASSIFICATION_DEVELOPMENT = 'development';
    public const DESIGN_COLOR = '#0EA5E9';
    public const DEVELOPMENT_COLOR = '#DC2626';
    public const BLOG_COLOR = '#6366F1';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'project_classification',
        'is_visible',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    /**
     * Blog posts assigned to this category.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Projects assigned to this category.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function projectClassificationColor(): string
    {
        return $this->resolvedProjectClassification() === self::PROJECT_CLASSIFICATION_DESIGN
            ? self::DESIGN_COLOR
            : self::DEVELOPMENT_COLOR;
    }

    public function projectClassificationLabel(): string
    {
        return $this->resolvedProjectClassification() === self::PROJECT_CLASSIFICATION_DESIGN
            ? 'Design'
            : 'Development';
    }

    public function resolvedProjectClassification(): string
    {
        if (in_array($this->project_classification, [
            self::PROJECT_CLASSIFICATION_DESIGN,
            self::PROJECT_CLASSIFICATION_DEVELOPMENT,
        ], true)) {
            return $this->project_classification;
        }

        $searchableName = Str::lower($this->name . ' ' . $this->slug);

        if (Str::contains($searchableName, 'design')) {
            return self::PROJECT_CLASSIFICATION_DESIGN;
        }

        return self::PROJECT_CLASSIFICATION_DEVELOPMENT;
    }
}
