<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'balance'];

    public function user()
    {
        // Uma carteira pertence a um usuário
        return $this->belongsTo(User::class);
    }
}
