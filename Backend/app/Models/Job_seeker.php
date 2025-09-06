<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job_seeker extends Model
{
    //
    use HasFactory;
     // Set primary key if your table uses user_id
    protected $primaryKey = 'user_id';

    // If primary key is not auto-incrementing
    public $incrementing = false;
    protected $fillable = ['resume','bio'];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
