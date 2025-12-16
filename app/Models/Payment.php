<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_code',
        'transaction_id',
        'bank_id',
        'payment_date',
        'amount',
        'proof',
        'status',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'verified_at'  => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
