<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    use HasFactory;

    protected $table = 'lowongan';

    protected $fillable = [
        'fptk_id',
        'judul',
        'lokasi',
        'tipe_kerja',
        'status',
        'tanggal_dibuka',
        'tanggal_ditutup',
    ];

    protected $casts = [
        'tanggal_dibuka' => 'date',
        'tanggal_ditutup' => 'date',
    ];

    public function fptk()
    {
        return $this->belongsTo(Fptk::class);
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }

    public function lamaran()
    {
        return $this->hasMany(Lamaran::class, 'lowongan_id');
    }

    public static function updateExpiredStatus()
    {
        self::where('status', 'dibuka')
            ->whereDate('tanggal_ditutup', '<=', now()->toDateString())
            ->update([
                'status' => 'ditutup',
            ]);
    }
}