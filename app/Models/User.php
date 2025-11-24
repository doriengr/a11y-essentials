<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function automaticTests()
    {
        return $this->hasMany(AutomaticTest::class);
    }

    public function checklists()
    {
        return $this->hasMany(Checklist::class);
    }

    public function visitedEntries()
    {
        return $this->hasMany(EntryUser::class);
    }

    public function visitedEntriesByCollection(string $collection)
    {
        return $this->visitedEntries()
            ->where('collection', $collection)
            ->pluck('entry_id')
            ->toArray();
    }

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
            'preferences' => 'json',
        ];
    }
}
