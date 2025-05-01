<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gym';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'lat',
        'lng',
        'address',
        'rating',
        'openHours',
        'facilities',
        'price',
        'image',
        'phone',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rating' => 'float',
        'facilities' => 'array', // Laravel will automatically handle JSON encoding/decoding
        'price' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}