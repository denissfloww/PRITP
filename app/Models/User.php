<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Tymon\JWTAuth\Contracts\JWTSubject;


/**
 * Class Tender
 *
 * @package App\Models
 *
 * @OA\Schema(
 *     title="User model",
 *     description="User model",
 * )
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes, HasRolesAndAbilities;

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'middle_name',
        'inn',
        'uuid',
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
     *     format="placed",
     *     title="uuid",
     *     default="8d587133-180c-3dfd-acc1-f5ab35e10730",
     *     description="uuid",
     * )
     *
     * @var string
     */
    private $uuid;

    /**
     * @OA\Property(
     *     format="placed",
     *     title="email",
     *     default="edeckow@example.org",
     *     description="email",
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     *     format="placed",
     *     title="first_name",
     *     default="Pearline",
     *     description="first_name",
     * )
     *
     * @var string
     */
    private $first_name;

    /**
     * @OA\Property(
     *     format="placed",
     *     title="last_name",
     *     default="Price",
     *     description="last_name",
     * )
     *
     * @var string
     */
    private $last_name;

    /**
     * @OA\Property(
     *     format="placed",
     *     title="middle_name",
     *     default="Eleonore",
     *     description="middle_name",
     * )
     *
     * @var string
     */
    private $middle_name;

    /**
     * @OA\Property(
     *     format="placed",
     *     title="inn",
     *     default="496625159283",
     *     description="inn",
     * )
     *
     * @var string
     */
    private $inn;

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function favoriteTenders()
    {
        return $this->belongsToMany(Tender::class, 'tender_favorites');
    }

    public function mailingTenders()
    {
        return $this->hasMany(TenderMailing::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
