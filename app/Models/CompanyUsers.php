<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUsers extends Model
{
    use HasFactory;

    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }
    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
    protected $appends = [
        'join_date'
    ];
    public function getJoinDateAttribute(){
        return Carbon::parse($this->created_at)->toDateString();
    }

}
