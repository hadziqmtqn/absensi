<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use SoftDeletes;

    protected $table = 'users';
    protected $guarded = [];

    public function absensi(): HasOne
    {
        return $this->hasOne(Absensi::class, 'user_id');
    }

    public function dataJob(): HasOne
    {
        return $this->hasOne(DataJob::class, 'user_id');
    }

    public function teknisiCadangan(): HasOne
    {
        return $this->hasOne(TeknisiCadangan::class, 'user_id');
    }
}
