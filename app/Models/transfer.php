<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transfer extends Model
{
    protected $fillable = ['value', 'payer_id', 'payee_id'];

    // Relacionamentos para saber quem pagou e quem recebeu
    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function payee()
    {
        return $this->belongsTo(User::class, 'payee_id');
    }
}
