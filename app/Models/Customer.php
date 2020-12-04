<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Customer
 * @package App\Models
 *
 * @property string $name
 * @property int $inn
 * @property int $kpp
 * @property int $ogrn
 * @property string $region
 * @property int $region_id
 * @property string $place
 * @property int $place_id
 * @property string $cp_name
 * @property string $cp_email
 * @property string $cp_phone
 * @property Tender[]|null tenders
 *
 */
class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'inn', 'kpp', 'ogrn', 'region', 'region_id', 'place', 'place_id', 'cp_name', 'cp_email', 'cp_phone'
    ];

    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }
}
