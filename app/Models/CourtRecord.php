<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'lawsuit_id',
        'user_id',

        'court_name',
        'court_number',
        'case_status',
        'decision_number',
        'base_number',
        'notes',
    ];



    public function lawsuit()
    {
        return $this->belongsTo(Lawsuit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // علاقة السجلات القضائية مع الموكلين 
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
