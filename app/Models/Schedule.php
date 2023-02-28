<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedule';
    protected $fillable = [
        'school_year',
        'type',
        'strand',
        'adviser',
        'grade',
        'term',
        'section',
        'subject',
        'day',
        't_from',
        't_to',
        'teacher',

    ];

    public function subjectd(){
        return $this->hasOne(Subject::class, 'id', 'subject');
    }

    public function sectiond(){
        return $this->hasOne(Section::class, 'id', 'section');
    }

    public function teacherd(){
        return $this->hasOne(User::class, 'id', 'teacher');
    }
}
