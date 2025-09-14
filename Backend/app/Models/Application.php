<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'job_id',
        'jobSeeker_id',
        'cover_letter',
        'applied_at',
    ];
    public function job(){
        return $this->belongsTo(Job::class,'job_id');
    }
    public function jobSeeker(){
        return $this->belongsTo(Job_seeker::class,'jobSeeker_id');
    }
}
