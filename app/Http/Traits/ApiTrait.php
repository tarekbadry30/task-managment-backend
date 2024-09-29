<?php
namespace App\Http\Traits;


trait ApiTrait
{
    public function successResponse($data=[],$message='',$status=200){
        return response()->json([
            'data'=>$data,
            'message'=>$message
        ],$status);
    }
    public function errorResponse($message='',$errors=[],$status=403){
        return response()->json([
            'message'=>$message,
            'errors'=>$errors
        ],$status);
    }

}
