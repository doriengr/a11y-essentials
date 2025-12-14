<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const LEVEL_THRESHOLDS = [25, 50, 75, 95];

    public const LEVEL_LABELS = [
        25 => 'Stufe 1 | Anfänger:in',
        50 => 'Stufe 2 | Fortgeschrittene',
        75 => 'Stufe 3 | Erfahren',
        95 => 'Stufe 4 | Expert:in',
    ];

    // The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // The attributes that should be hidden for arrays.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'preferences' => 'json',
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

    // Get all entries that are already visited
    public function visitedEntriesByCollection(string $collection)
    {
        return $this->visitedEntries()
            ->where('collection', $collection)
            ->pluck('entry_id')
            ->toArray();
    }

    // Calculate total progress points for the user.
    public function progressPoints(): int
    {
        return
            $this->automaticTests()->count()
            + $this->checklists()->count()
            + count($this->visitedEntriesByCollection('requirements'))
            + count($this->visitedEntriesByCollection('learning_modules'));
    }

    // Return level label
    public function levelLabel(): string
    {
        $points = $this->progressPoints();

        foreach (self::LEVEL_LABELS as $threshold => $label) {
            if ($points < $threshold) {
                return $label;
            }
        }

        return 'Stufe 5 | Meister:in';
    }

    // Calculate how many points are needed for the next level.
    public function pointsToNextLevel(): int
    {
        $points = $this->progressPoints();

        $nextLevel = collect(self::LEVEL_THRESHOLDS)
            ->first(fn ($v) => $points < $v);

        return $nextLevel ? $nextLevel - $points : 0;
    }
}
