<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $words = Str::of($this->name)->explode(' ');
        if ($words->isEmpty()) {
            return '';
        }
        $first = Str::substr($words->first(), 0, 1);
        $last = Str::substr($words->last(), 0, 1);
        return $first . $last;
    }

    /**
     * Get the user's categories
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the user's transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
