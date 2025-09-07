<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job_function extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function jobs(){
        return $this->hasMany(Job::class);
    }
}
