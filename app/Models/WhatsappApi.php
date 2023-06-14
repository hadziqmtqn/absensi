<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappApi extends Model
{
    protected $fillable = [
        'domain',
        'api_keys',
        'no_hp_penerima'
    ];
}
