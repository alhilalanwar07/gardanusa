<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            
            $request->file('upload')->move(public_path('storage/ckeditor'), $fileName);

            $url = asset('storage/ckeditor/' . $fileName);

            return response()->json([
                'fileName' => $fileName,
                'uploaded' => 1,
                'url' => $url
            ]);
        }

        return response()->json([
            'uploaded' => 0,
            'error' => ['message' => 'File upload failed']
        ], 500);
    }
}