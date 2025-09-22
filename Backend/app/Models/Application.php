<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    //
    use HasFactory;

protected $fillable = [
    'job_seeker_id',
    'job_post_id',
    'cover_letter',
];

    public function jobPost()
{
    return $this->belongsTo(Job::class, 'job_post_id');
}    public function jobSeeker(){
        return $this->belongsTo(Job_seeker::class,'job_seeker_id');
    }
}
