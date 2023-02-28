<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enroll extends Model
{
    use HasFactory;

    protected $table = 'enroll';
    protected $fillable = [
        'user_id',
        'student_type',
        'school_year_id',
        'grade',
        'section_id',
        'term',
        'strand',
        'status',
        'deleted',
    ];

    public function sectiond(){
        return $this->hasOne(Section::class, 'id', 'section_id');
    }

    public function strand(){
        return $this->hasOne(Strand::class, 'id', 'strand');
    }

    public function ensched(){
        return $this->hasMany(EnrollSched::class, 'enroll_id', 'id')->with('schedule');
    }

}
