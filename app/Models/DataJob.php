<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_api',
        'user_id',
        'kode_pasang_baru'
    ];

    public function dataPasangBaru()
    {
        return $this->belongsTo(DataPasangBaru::class, 'kode_pasang_baru');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeJobApi($query, $jobApi)
    {
        return $query->where('job_api', $jobApi);
    }
}
