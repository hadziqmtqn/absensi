<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPasangBaru extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'inet',
        'nama_pelanggan',
        'no_hp',
        'alamat',
        'acuan_lokasi',
        'foto',
        'status'
    ];

    public function data_job()
    {
        return $this->hasOne(DataJob::class, 'kode_pasang_baru');
    }
}
