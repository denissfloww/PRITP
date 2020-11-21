<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TenderTenderObject extends Pivot
{
    protected $fillable = [
        'quantity'
    ];
}
