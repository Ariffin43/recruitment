<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kualifikasi extends Model
{
    protected $table = 'kualifikasi';

    protected $fillable = [
        'departemen_id',
        'nama_kualifikasi',
    ];

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }
}