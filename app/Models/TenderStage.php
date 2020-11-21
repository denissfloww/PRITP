<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TenderStage
 * @package App\Models
 *
 * @property string $name
 * @property string $description
 * @property Tender[]|null tenders
 */
class TenderStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description'
    ];

    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }
}
