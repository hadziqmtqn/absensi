<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataJob extends Model
{
    protected $fillable = [
        'job_api',
        'user_id',
        'kode_pasang_baru'
    ];

    public function dataPasangBaru(): BelongsTo
    {
        return $this->belongsTo(DataPasangBaru::class, 'kode_pasang_baru');
    }

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeJobApi($query, $jobApi)
    {
        return $query->where('job_api', $jobApi);
    }
}
