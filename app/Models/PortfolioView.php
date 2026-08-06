<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PortfolioView extends Model
{
    protected $fillable = [
        'route_name',
        'page_type',
        'path',
        'viewable_type',
        'viewable_id',
        'visitor_hash',
        'viewed_on',
        'viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'viewed_on' => 'date',
            'viewed_at' => 'datetime',
        ];
    }

    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }
}
