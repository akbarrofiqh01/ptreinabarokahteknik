<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction_item extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'item_name',
        'qty',
        'price',
        'total',
        'is_ready',
        'ready_at',
        'source',
    ];

    protected $casts = [
        'is_ready' => 'boolean',
        'ready_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
