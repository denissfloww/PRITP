<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TenderObject
 *
 * @package App\Models
 *
 * @property string name
 * @property string description
 * @property string okvad2_classifier
 * @property Tender tender
 */
class TenderObject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'okvad2_classifier'
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }
}
