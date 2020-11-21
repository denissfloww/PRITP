<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TenderObject
 * @package App\Models
 *
 * @property string name
 * @property string description
 * @property Tender[] tenders
 */
class TenderObject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description'
    ];

    public function tenders()
    {
        return $this->belongsToMany(Tender::class)->using(TenderTenderObject::class);
    }
}
