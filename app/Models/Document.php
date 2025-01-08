<?php
// App\Models\Document.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['lawsuit_id', 'file_name', 'file_path'];

    public function lawsuit()
    {
        return $this->belongsTo(Lawsuit::class);
    }
}
