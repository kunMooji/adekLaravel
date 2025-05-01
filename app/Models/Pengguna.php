<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasFactory;

    protected $table = 'data_pengguna'; 
    public $timestamps = false;
    protected $primaryKey = 'id_user'; 
    public $incrementing = false;
    protected $keyType = 'string'; 
    protected $fillable = [
        'id_user',
        'nama_lengkap',
        'email',
        'password',
        'no_hp',
        'berat_badan',
        'tinggi_badan',
        'tanggal_lahir',
        'tipe_diet',
        'gender',
        'gambar',
        'aktifitas'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tipe_diet' => 'string',
        'gender' => 'string',
        'aktifitas' => 'string',
    ];
    protected $attributes = [
        'gambar' => null, 
    ];
    
    protected $hidden = [
        'password',
    ];
}
