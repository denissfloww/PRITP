<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Customer
 *
 * @package App\Models
 *
 * @property string $name
 * @property string $inn
 * @property string $kpp
 * @property string $ogrn
 * @property array $json_location
 * @property string $location
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
        'name',
        'inn',
        'kpp',
        'ogrn',
        'location',
        'json_location',
        'cp_name',
        'cp_email',
        'cp_phone'
    ];

    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }
}
