<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $guarded = [];

    public function absensi()
    {
        return $this->hasOne(Absensi::class, 'user_id');
    }
}
