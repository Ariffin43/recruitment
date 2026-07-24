<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelamar extends Model
{
    use HasFactory;

    protected $table = 'pelamar';

    protected $fillable = [
        'user_id',
        'jenis_kelamin',
        'no_hp',
        'alamat',
        'pendidikan_terakhir',
        'pengalaman_kerja',
        'foto',
        'file_ktp',
        'file_kk',
        'file_cv',
        'file_ijazah',
        'file_sertifikat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lamaran()
    {
        return $this->hasMany(Lamaran::class);
    }
}