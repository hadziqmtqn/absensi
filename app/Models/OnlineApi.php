<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineApi extends Model
{
    use HasFactory;

    protected $fillable = [
        'website'
    ];
}
