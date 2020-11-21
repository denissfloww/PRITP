<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\File;

/**
 * Class TenderClassifier
 * @package App\Models
 *
 * @property string $code
 * @property string $description
 * @property TenderClassifier parent
 * @property Tender[]|null tenders
 */
class TenderClassifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'description'
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }
}
