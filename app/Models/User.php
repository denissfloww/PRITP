<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'middle_name',
        'inn',
        'uuid',
    ];

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
        return $this->belongsToMany(Tender::class, 'tender_mailings');
    }
}
