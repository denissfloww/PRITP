<?php

namespace App\Models;
use DateTimeInterface;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TenderStage
 *
 * @package App\Models
 *
 * @property string okvad2_classifier
 * @property User user
 */

class TenderMailing extends Model
{
    use HasFactory;

    protected $fillable = [
        'okvad2_classifier'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
