<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Pdf extends Model
{
    use HasFactory;
    protected $fillable = ['name','size'];
    protected $appends = ['path'];


    public function getPathAttribute(){
        return asset("storage/pdf/{$this->name}");
    }

}
