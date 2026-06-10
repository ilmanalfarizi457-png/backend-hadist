<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// TAMBAH INI
use App\Models\Kategori;
use App\Models\Kitab;

class Hadist extends Model
{
    use HasFactory;

    protected $table = 'hadists';

    protected $fillable = [
        'judul',
        'arab',
        'terjemahan',
        'kitab_id',
        'kategori_id'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function kitab()
    {
        return $this->belongsTo(Kitab::class, 'kitab_id');
    }
}