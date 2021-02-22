<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'email',
        'phone',
        'address_1',
        'address_2',
        'city',
        'postcode',
        'notes',
        'created_at',
        'updated_at',
    ];

    public function uploads()
    {
        return $this->morphMany(Upload::class, 'uploadable');
    }

    public function candidates(){
        return $this->belongsToMany(Candidate::class,'candidate_employer');
    }

    public function accounts(){

        return $this->hasMany(User::class);

    }



}
