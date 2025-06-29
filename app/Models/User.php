<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'candidate_skill');
    }
    public function jobs()
    {
        return $this->hasMany(Job::class, 'user_id'); // employer

    }
    public function applications()
    {
        return $this->hasMany(Application::class, 'user_id'); // candidate
    }
    public function jobSkills()
    {
        return $this->belongsToMany(Skill::class, 'job_skill', 'job_id', 'skill_id');
    }
    public function candidateSkills()
    {
        return $this->belongsToMany(Skill::class, 'candidate_skill', 'user_id', 'skill_id');
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
