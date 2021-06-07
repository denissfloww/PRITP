<?php

namespace App\Models;

use DateTimeInterface;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tender
 *
 * @package App\Models
 *
 * @property int $number
 * @property string $name
 * @property string $description
 * @property string $source_url
 * @property \DateTimeInterface $start_request_date
 * @property \DateTimeInterface $end_request_date
 * @property \DateTimeInterface $result_date
 * @property float $nmc_price
 * @property float $ensure_request_price
 * @property float $ensure_contract_price
 * @property string $okvad2_classifier
 * @property TenderType type
 * @property TenderStage stage
 * @property Customer customer
 * @property Currency currency
 * @property TenderObject[]|null objects
 * @property User[]|null favoriteUsers
 * @OA\Schema(
 *     title="Tender model",
 *     description="Tender model",
 * )
 */
class Tender extends Model
{
    use HasFactory, SoftDeletes, Filterable;

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
        'okvad2_classifier'
    ];

    /**
     * @OA\Property(
     *     format="int64",
     *     title="ID",
     *     default=1,
     *     description="ID",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     *     format="int64",
     *     title="ID",
     *     default=1,
     *     description="ID",
     * )
     *
     * @var integer
     */
    private $number;

    /**
     * @OA\Property(
     *     format="placed",
     *     title="name",
     *     default="Ms. Natasha Effertz PhD",
     *     description="name",
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *     format="placed",
     *     title="description",
     *     default="Non id et similique vel omnis quidem quaerat.",
     *     description="description",
     * )
     *
     * @var string
     */
    private $description;

    /**
     * @OA\Property(
     *     format="placed",
     *     title="source_url",
     *     default="https://www.lockman.info/dolorum-aperiam-autem-quos-ipsam-aut",
     *     description="source_url",
     * )
     *
     * @var string
     */
    private $source_url;

    /**
     * @OA\Property(
     *     format="timestamp",
     *     title="start_request_date",
     *     default="2014-03-07 05:45:31",
     *     description="start_request_date",
     * )
     *
     * @var string
     */
    private $start_request_date;

    /**
     * @OA\Property(
     *     format="timestamp",
     *     title="end_request_date",
     *     default="2022-04-08 02:34:31",
     *     description="end_request_date",
     * )
     *
     * @var string
     */
    private $end_request_date;
    /**
     * @OA\Property(
     *     format="timestamp",
     *     title="result_date",
     *     default="2023-01-05 23:12:07",
     *     description="result_date",
     * )
     *
     * @var string
     */
    private $result_date;
    /**
     * @OA\Property(
     *     format="placed",
     *     title="nmc_price",
     *     default="3459.86",
     *     description="nmc_price",
     * )
     *
     * @var string
     */
    private $nmc_price;
    /**
     * @OA\Property(
     *     format="placed",
     *     title="ensure_request_price",
     *     default="9640.54",
     *     description="ensure_request_price",
     * )
     *
     * @var string
     */
    private $ensure_request_price;
    /**
     * @OA\Property(
     *     format="placed",
     *     title="ensure_contract_price",
     *     default="3500.85",
     *     description="ensure_contract_price",
     * )
     *
     * @var string
     */
    private $ensure_contract_price;

    /**
     * @OA\Property(
     *     format="int32",
     *     title="customer_id",
     *     default=11,
     *     description="customer_id",
     * )
     *
     * @var integer
     */
    private $customer_id;

    /**
     * @OA\Property(
     *     format="int32",
     *     title="type_id",
     *     default=1,
     *     description="type_id",
     * )
     *
     * @var integer
     */
    private $type_id;

    /**
     * @OA\Property(
     *     format="int32",
     *     title="currency_id",
     *     default=11,
     *     description="currency_id",
     * )
     *
     * @var integer
     */
    private $currency_id;

    /**
     * @OA\Property(
     *     format="int32",
     *     title="stage_id",
     *     default=1,
     *     description="stage_id",
     * )
     *
     * @var integer
     */
    private $stage_id;



    public function type()
    {
        return $this->belongsTo(TenderType::class);
    }

    public function stage()
    {
        return $this->belongsTo(TenderStage::class);
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
        return $this->hasMany(TenderObject::class);
    }

    public function favoriteUsers()
    {
        return $this->belongsToMany(User::class, 'tender_favorites');
    }
}
