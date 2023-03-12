<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lrn',
        'first_name',
        'last_name',
        'middle_name',
        'role',
        'email',
        'username',
        'password',
        'student_type',
        'age',
        'birthdate',
        'birth_place',
        'contact',
        'gender',
        'role',
        'civil_status',
        'citizenship',
        'religion',
        'address',
        'deleted',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function enroll(){
        return $this->hasMany(Enroll::class, 'user_id', 'id')->with('strand', 'sectiond');
    }

    public function shsgrade(){
        return $this->hasMany(SHSGrade::class, 'user_id', 'id')->with('subjectd');
    }

    public function jhsgrade(){
        return $this->hasMany(JHSGrade::class, 'user_id', 'id')->with('subjectd'); 
    }


}
