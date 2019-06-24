<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
    	'descriotion_en','descriotion_ar','video_path'
    ];
}
