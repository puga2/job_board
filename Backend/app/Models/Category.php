<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    use HasFactory;
    protected $fillable =[
        'name',
        'description',
    ];
    public function job_functions(){
        return $this->hasMany(Job_function::class);
    }
    public function jobs(){
        return $this->hasManyThrough(Job::class,Job_function::class,'category_id','job_function_id');
    }
}
