<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikel';
    protected $primaryKey = 'id_buku';

    protected $fillable = [
        'id_buku',
        'judul',
        'kategori',
        'isi_buku',
        'gambar',
        'username'
    ];

    protected $casts = [
        'judul' => 'string',
        'kategori' => 'string',
        'isi_buku' => 'string',
        'gambar' => 'string',
        'username' => 'string'
    ];
}
