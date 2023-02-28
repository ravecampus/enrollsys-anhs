<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollSched extends Model
{
    use HasFactory;

    protected $table = 'enroll_sched';

    protected $fillable = [
        'enroll_id',
        'schedule_id',
    ];

    public function schedule(){
        return $this->hasOne(Schedule::class, 'id', 'schedule_id')->with('subjectd', 'teacherd');
    }


}
