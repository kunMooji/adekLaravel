<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';
    protected $primaryKey = 'id_menu';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_menu',
        'nama_menu',
        'kategori_menu',
        'kalori',
        'protein',
        'karbohidrat',
        'lemak',
        'gula',
        'resep',
        'gambar',
        'satuan'
    ];

    protected $casts = [
        'kategori_menu' => 'string',
        'kalori' => 'integer',
        'protein' => 'double',
        'karbohidrat' => 'double',
        'lemak' => 'double',
        'gula' => 'integer',
        'satuan' => 'string', 
    ];
}
