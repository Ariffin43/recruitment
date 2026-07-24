<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fptk extends Model
{
    use HasFactory;

    protected $table = 'fptk';

    protected $fillable = [
        'nomor_fptk',
        'departemen_id',
        'hod_id',
        'posisi_dibutuhkan',
        'jumlah_kebutuhan',
        'tanggal_dibutuhkan',
        'alasan',
        'catatan_tambahan',
        'status',
        'catatan_gm',
        'gm_approved_at',
        'catatan_hrd',
        'hrd_approved_at',
    ];

    protected $casts = [
        'tanggal_dibutuhkan' => 'date',
        'gm_approved_at' => 'datetime',
        'hrd_approved_at' => 'datetime',
    ];

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }

    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class);
    }

    public function hod()
    {
        return $this->belongsTo(User::class, 'hod_id');
    }
}