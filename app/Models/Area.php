<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'geom',
    ];

    protected $casts = [
        'geom' => 'array', // Mengkonversi kolom geom menjadi array PHP (JSON)
    ];

    // Jika Anda memiliki relasi dengan user yang membuat area
    public function user()
    {
        return $this->belongsTo(User::class, 'user_created_id'); // Sesuaikan dengan nama kolom user_id Anda
    }
}
