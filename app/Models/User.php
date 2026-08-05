<?php

namespace App\Models;

use App\Enums\UserStatus;
use App\Observers\UserObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Onpay\Core\Auth\Authenticatable;
use Onpay\Core\Eloquent\Concerns\Bannable;
use Onpay\Core\Eloquent\Concerns\SortStringResolver;
use Onpay\Core\Eloquent\Concerns\ValueSearcher;
use Onpay\Core\Eloquent\Contracts\Bannable as BannableContract;

#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements BannableContract, MustVerifyEmail
{
    use Bannable, HasFactory, Notifiable, SortStringResolver, ValueSearcher;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'locale',
        'timezone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'last_online_at' => 'datetime',
        ];
    }

    /**
     * Get the user's status.
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => match ([(bool) $attributes['email_verified_at'], (bool) $attributes['banned_at']]) {
                [true, false] => UserStatus::Active,
                [false, false] => UserStatus::Inactive,
                [false, true], [true, true] => UserStatus::Banned,
            }
        );
    }

    /**
     * Scope a query to only include users with the given status.
     */
    public function scopeStatus(Builder $query, UserStatus|null $status): void
    {
        match ($status) {
            UserStatus::Active => $query->whereNotNull('email_verified_at')->whereNull('banned_at'),
            UserStatus::Inactive => $query->whereNull('email_verified_at')->whereNull('banned_at'),
            UserStatus::Banned => $query->whereNotNull('banned_at'),
            default => $query,
        };
    }
}
