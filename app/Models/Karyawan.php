<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'users';
    protected $guarded = [];

    public function absensi()
    {
        return $this->hasOne(Absensi::class, 'user_id');
    }

    public function dataJob()
    {
        return $this->hasOne(DataJob::class, 'user_id');
    }

    public function teknisiCadangan()
    {
        return $this->hasOne(TeknisiCadangan::class, 'user_id');
    }
}
