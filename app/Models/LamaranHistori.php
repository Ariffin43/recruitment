<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LamaranHistori extends Model
{
    use HasFactory;
    public const UPDATED_AT = null;
    
    protected $table = 'lamaran_histories';

    protected $fillable = [
        'lamaran_id',
        'status_lama',
        'status_baru',
        'aksi',
        'catatan',
        'changed_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function lamaran()
    {
        return $this->belongsTo(Lamaran::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}