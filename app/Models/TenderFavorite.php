<?php


namespace App\Models;
use DateTimeInterface;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderFavorite
{
    use HasFactory;

    protected $fillable = [

    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }
}
