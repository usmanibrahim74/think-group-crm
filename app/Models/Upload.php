<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $hidden = ['uploadable_id','uploadable_type'];

    protected $appends = ['expire_status'];

    public function uploadable(){
        return $this->morphTo();
    }


    public function getExpireStatusAttribute(){
        $today = time();
        $twoDaysBefore = strtotime('-2 days', time());
        $expireOn = strtotime($this->expires_on);
        if($expireOn > $today){
            if ($expireOn > $twoDaysBefore){
                return "success";
            }else{
                return "warning";
            }
        }else{
            return "danger";
        }
    }
}
