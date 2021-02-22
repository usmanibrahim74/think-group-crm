<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortlistedCandidate extends Model
{
    use HasFactory;

    protected $appends = ['shortlisted_ago'];

    public function getShortlistedAgoAttribute(){
        return $this->created_at->diffForHumans();
    }
}
