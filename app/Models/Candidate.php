<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'date_of_birth',
        'email',
        'contact',
        'address_1',
        'address_2',
        'city',
        'postcode',
        'current_salary',
        'desired_salary',
        'notes',
        'department_id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = ['created_at','updated_at'];

    protected $appends = ['name'];

    public function uploads()
    {
        return $this->morphMany(Upload::class, 'uploadable');
    }

    public function industries(){
        return $this->belongsToMany(Industry::class,'candidate_industry');
    }

    public function employers(){
        return $this->belongsToMany(Employer::class,'candidate_employer');
    }

    public function shortlistedBy(){
        return $this->belongsToMany(User::class,'shortlisted_candidates');
    }

    public function getNameAttribute(){
        return $this->title.' '.$this->first_name.' '.$this->last_name;
    }

    public function comments(){
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }


}
