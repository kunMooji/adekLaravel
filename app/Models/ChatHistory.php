<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatHistory extends Model
{
    protected $table = 'chat_history';
    protected $primaryKey = 'id_chat';
    public $timestamps = false;
    
    protected $fillable = [
        'id_user',
        'sender',
        'message',
        'timestamp'
    ];
    
    const CREATED_AT = 'timestamp';
    const UPDATED_AT = null;
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}