<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    public const DEFAULT_HERO_BACKGROUND_PATH = 'projects/thumbnails/4AHr0Js5j51L9Z3XpyDVjqhuWpBqZflpT1mQ5PkF.png';
    public const DEFAULT_FOOTER_CREDIT_TEXT = 'Designed & Built with ♥';
    public const DEFAULT_CONTACT_HEADING = 'Get in Touch';
    public const DEFAULT_CONTACT_DESCRIPTION = "I'm here to answer any questions you have.";
    public const DEFAULT_SEO_TITLE = 'Lanre Malumi Portfolio';
    public const DEFAULT_META_DESCRIPTION = 'Portfolio of Lanre Malumi, UI/UX designer and Laravel developer.';

    protected $fillable = [
        'hero_background_image',
        'about_background_image',
        'footer_credit_text',
        'contact_heading',
        'contact_description',
        'default_seo_title',
        'default_meta_description',
        'favicon_path',
    ];

    public static function current(): self
    {
        return self::query()->firstOrCreate([], self::defaults());
    }

    public static function defaults(): array
    {
        return [
            'footer_credit_text' => self::DEFAULT_FOOTER_CREDIT_TEXT,
            'contact_heading' => self::DEFAULT_CONTACT_HEADING,
            'contact_description' => self::DEFAULT_CONTACT_DESCRIPTION,
            'default_seo_title' => self::DEFAULT_SEO_TITLE,
            'default_meta_description' => self::DEFAULT_META_DESCRIPTION,
        ];
    }

    public static function fallbackHeroBackgroundUrl(): string
    {
        return asset('storage/' . self::DEFAULT_HERO_BACKGROUND_PATH);
    }

    public function heroBackgroundUrl(): string
    {
        return $this->publicDiskUrl($this->hero_background_image)
            ?? self::fallbackHeroBackgroundUrl();
    }

    public function aboutBackgroundUrl(): string
    {
        return $this->publicDiskUrl($this->about_background_image)
            ?? self::fallbackHeroBackgroundUrl();
    }

    public function faviconUrl(): ?string
    {
        return $this->publicDiskUrl($this->favicon_path);
    }

    private function publicDiskUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return asset(Storage::url($path));
    }
}
