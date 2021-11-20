<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorHits extends Model
{
    use HasFactory;

    protected $table = 'visitor_hits';

    protected $fillable = [
        'visitor_id',
        'visited_page_id',
        'hits',
        'ip',
        'user_agent',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function links(): BelongsTo
    {
        return $this->belongsTo(link::class, 'visited_page_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserHits::class, 'visited_page_id')->withDefault();
    }

}
