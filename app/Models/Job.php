<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', // employer
        'title',
        'description',
        'location',
        'salary',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_skill', 'job_id', 'skill_id');
    }
}
