<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JHSGrade extends Model
{
    use HasFactory;

    protected $table = 'jhs_grade';
    protected $fillable = [
        'school_year_id',
        'section_id',
        'grade',
        'user_id',
        'subject_id',
        '1st_grade',
        '2nd_grade',
        '3rd_grade',
        '4th_grade',
        'average',
        'remark',
    ];

    public function studentd(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function subjectd(){
        return $this->hasOne(Subject::class, 'id', 'subject_id');
    }
}
