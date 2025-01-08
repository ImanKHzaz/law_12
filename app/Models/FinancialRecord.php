<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'lawsuit_id',
        'claim_value',
        'amount_paid',
        'amount_remaining',
        'notes'
    ];

    // العلاقة بين FinancialRecord و Lawsuit
    public function lawsuit()
    {
        return $this->belongsTo(Lawsuit::class);
    }
}
