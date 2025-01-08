<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Lawsuit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'next_case_number',
        'user_case_number',
        'lawsuit_type',
        'lawsuit_subject',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = Auth::user();
            $model->next_case_number = Lawsuit::where('user_id', $user->id)->max('next_case_number') + 1;
            $model->user_case_number = Lawsuit::where('user_id', $user->id)->max('user_case_number') + 1;
        });
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }


    public function defendants()
    {
        return $this->hasMany(Defendant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function financialRecord()
    {
        return $this->hasOne(FinancialRecord::class);
    }

    public function courtRecord()
    {
        return $this->hasOne(CourtRecord::class);
    }


    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
