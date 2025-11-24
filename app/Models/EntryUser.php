<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryUser extends Model
{
    use HasFactory;

    protected $table = 'entry_user';
    protected $fillable = ['user_id', 'entry_id', 'collection'];
}
