<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $appends = ['commented_by', 'commented_ago'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCommentedByAttribute(){
        return $this->user->name;
    }
    public function getCommentedAgoAttribute(){
        return $this->created_at->diffForHumans();
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
