<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Checklist extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'states' => 'array',
        'groups' => 'array',
        'progress' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'states',
        'groups',
        'progress'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
