<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function uploadFile(Request $request){
        
        $name = $request->file->hashName();
        $name = env('APP_URL') . "/uploads/$name";
        // $name = base_path() . "/uploads/$name";
        $request->file->move(public_path('uploads'), $name);

        return response()->json([
            'message' => 'File has been uploaded successfully!',
            'url' => $name
        ], 200);
    }
}
