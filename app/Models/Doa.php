<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doa extends Model
{
    protected $fillable = [
        'judul', 'arab', 'latin', 'terjemahan', 'kategori', 'sumber'
    ];
}