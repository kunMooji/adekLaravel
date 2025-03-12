<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Olahraga extends Model
{
    use HasFactory;

    protected $table = 'olahraga';
    protected $primaryKey = 'id_olahraga';
    public $timestamps = true;

    protected $fillable = [
        'nama_olahraga',
        'deskripsi',
        'cara_olahraga',
        'jenis_olahraga',
        'username',
        'gambar',
        'kalori'
    ];

    protected $casts = [
        'id_olahraga' => 'string', 
        'nama_olahraga' => 'string',
        'deskripsi' => 'string',
        'cara_olahraga' => 'string',
        'jenis_olahraga' => 'string',
        'username' => 'string', 
        'gambar' => 'string', 
        'kalori' => 'decimal:2', 
    ];
}    