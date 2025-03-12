<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKalori extends Model
{
    use HasFactory;

    // Nama tabel yang sudah ada di database
    protected $table = 'detail_kalori';

    // Primary key yang sudah ada di database
    protected $primaryKey = 'id_detailkalori';

    // Kolom yang bisa diisi (fillable)
    protected $fillable = [
        'id_user',
        'id_menu',
        'tanggal',
        'jumlah',
        'total_kalori',
        'total_minum',
        'total_protein',
        'total_karbohidrat',
        'total_lemak',
        'total_gula'
    ];

    // Nonaktifkan timestamps (created_at dan updated_at) jika tidak ada di tabel
    public $timestamps = false;

    // Relasi ke tabel lain (jika diperlukan)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }
}