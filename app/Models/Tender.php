<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tender
 * @package App\Models
 *
 * @property int $number
 * @property string $name
 * @property string $description
 * @property string $source_url
 * @property DateTimeInterface $start_request_date
 * @property DateTimeInterface $end_request_date
 * @property DateTimeInterface $result_date
 * @property float $nmc_price
 * @property float $ensure_request_price
 * @property float $ensure_contract_price
 * @property TenderType type
 * @property TenderStage stage
 * @property TenderClassifier classifier
 * @property Customer customer
 * @property Currency currency
 * @property TenderObject[]|null objects
 * @property User[]|null favoriteUsers
 */
class Tender extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'name',
        'description',
        'source_url',
        'start_request_date',
        'end_request_date',
        'result_date',
        'nmc_price',
        'ensure_request_price',
        'ensure_contract_price',
    ];

    public function type()
    {
        return $this->belongsTo(TenderType::class);
    }

    public function stage()
    {
        return $this->belongsTo(TenderStage::class);
    }

    public function classifier()
    {
        return $this->belongsTo(TenderClassifier::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function objects()
    {
        return $this->belongsToMany(TenderObject::class)->using(TenderTenderObject::class);
    }

    public function favoriteUsers()
    {
        return $this->belongsToMany(User::class, 'tender_favorites');
    }
}
