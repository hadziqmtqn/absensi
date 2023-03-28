<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_name',
        'description',
        'email',
        'no_hp',
        'logo',
        'awal_absensi',
        'akhir_absensi'
    ];
}
