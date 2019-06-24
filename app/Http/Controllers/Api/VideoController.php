<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    public function getVideos(Request $request){
        $validator = Validator::make($request->all(),[
          'lang'=>'required|string|max:2|in:en,ar'
        ]);
        if($validator->fails()) {
          return '{"code":"400","message":' . '[' . json_encode($validator->errors()) . ']}';
        }
        
    	$token = $request->token;
    	$lang = $request->lang ? $request->lang : 'en';


        $videos = Video::get(['videos.id','videos.description_'.$lang.' as description','videos.video_path as video']);
        

        if(count($videos)>0){
            return '{"code":"200","message":"Success","data":'.json_encode($videos).'}';
        }else{
            $error = 'There is no data';
            return '{"code":"400","message":'.'['.json_encode($error).']}';
        }

    }
}
