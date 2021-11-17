<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHits extends Model
{
    use HasFactory, HasTimestamps;

//    public $attributes = [ 'hits' => 0 ];

    protected $fillable = [
        'user_id',
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
        return $this->belongsTo(link::class, 'visited_page_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
