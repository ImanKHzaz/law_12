<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Defendant extends Model
{
    use HasFactory;

    // تحديد اسم الجدول إذا كان يختلف عن الاسم الافتراضي (جمع اسم الموديل)
    protected $table = 'defendants';

    // السماح بتعبئة هذه الحقول بشكل مباشر
    protected $fillable = [
        'lawsuit_id',
        'user_id',
        'defendant_number',
        'full_name',
        'phone_number',
        'id_front_image',
        'id_back_image',
        'power_of_attorney',
        'notes',
    ];

    // تعريف العلاقات
    public function lawsuit()
    {
        return $this->belongsTo(Lawsuit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
