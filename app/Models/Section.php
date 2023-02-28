<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $table = 'section';
    protected $fillable = [
        'year_level',
        'section_name',
        'adviser'
    ];

    public function advise(){
        return $this->hasOne(User::class, 'id', 'adviser');
    }
}
