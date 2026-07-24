<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lamaran extends Model
{
    use HasFactory;

    protected $table = 'lamaran';

    protected $fillable = [
        'nomor_lamaran',
        'lowongan_id',
        'pelamar_id',
        'status',
        'hasil_akhir',
        'catatan_hrd',
        'catatan_hod',
        'catatan_interview',
        'tanggal_dilamar',
        'tanggal_screening_hrd',
        'tanggal_dikirim_ke_hod',
        'tanggal_screening_hod',
        'metode_interview',
        'tanggal_interview',
        'lokasi_interview',
        'link',
    ];

    protected $casts = [
        'tanggal_dilamar' => 'datetime',
    ];

    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class);
    }

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class);
    }

    public function histories()
    {
        return $this->hasMany(LamaranHistori::class);
    }
}