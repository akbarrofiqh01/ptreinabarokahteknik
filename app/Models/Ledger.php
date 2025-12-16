<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = [
        'ledger_date',
        'type',
        'source',
        'reference_id',
        'amount',
    ];
}
