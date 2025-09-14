<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    //
    use HasFactory;
    protected $table = 'job_posts';
    protected $fillable = [
        'title',
        'description',
        'salary_min',
        'salary_max',
        'job_function_id',
        'location_id',
        'company_id',
        'employment_type_id',
        'status',
        'posted_at',
        'expires_at',
    ];
    public function job_function(){
        return $this->belongsTo(Job_function::class,'job_function_id');
    }
    public function location(){
        return $this->belongsTo(Location::class,'location_id');
    }
    public function company(){
        return $this->belongsTo(Company::class,'company_id');
    }
     // Job belongs to a category through job function
    public function category() {
        return $this->hasOneThrough(
            Category::class,
            Job_function::class,
            'id',           // Job_function primary key
            'id',           // Category primary key
            'job_function_id', // Local key on jobs table
            'category_id'   // Foreign key on job_functions table
        );
    }
}
