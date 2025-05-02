<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWorkout extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_olahraga',
        'durasi',
        'kalori',
        'tanggal',
    ];

    protected $casts = [
        'kalori' => 'decimal:2',
        'tanggal' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function olahraga()
    {
        return $this->belongsTo(Olahraga::class);
    }
}