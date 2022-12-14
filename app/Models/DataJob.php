<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataJob extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dataPasangBaru()
    {
        return $this->belongsTo(DataPasangBaru::class, 'kode_pasang_baru');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'user_id');
    }
}
