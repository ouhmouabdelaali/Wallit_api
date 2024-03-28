<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function senderTransactions()
    {
        return $this->hasMany(Transaction::class, 'sender_wallet_id');
    }

    public function receiverTransactions()
    {
        return $this->hasMany(Transaction::class, 'receiver_wallet_id');
    }
}
