<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticable;

class ApiKey extends Authenticable
{
    use HasFactory;

    protected $guard = 'api_key';

    protected $fillable = [
        'enkripsi',
        'domain',
        'api_key'
    ];

    protected $hidden = ['api_key'];
}
