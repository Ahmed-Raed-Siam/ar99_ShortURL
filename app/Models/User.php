<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasTimestamps, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
//        return $this->belongsToMany(Role::class);
//        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id');
        return $this->belongsToMany(Role::class, 'role_users')->withTimestamps();
    }

    /**
     * @param $roles
     * @return bool
     */
    public function hasAnyRoles($roles): bool
    {
        if ($this->roles()->whereIn('name', $roles)->first()) {
            return true;
        }
        return false;
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }

    /**
     * @return HasMany
     */
    public function links(): HasMany
    {
        return $this->hasMany(link::class, 'user_id','id');
    }

    /**
     * @return HasMany
     */
    public function userLogs(): HasMany
    {
        return $this->hasMany(UserHits::class, 'user_id','id')->latest('visited_at');
    }


}
