<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'lawsuit_id',
        'user_id',
        'client_number',
        'phone_number',
        'id_front_image',
        'id_back_image',
        'power_of_attorney',
        'notes'
    ];

    public function lawsuit()
    {
        return $this->belongsTo(Lawsuit::class);
    }
}
