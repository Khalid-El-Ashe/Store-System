<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // in here you can to write all method you need to use in all controllers project
    protected function uploadFile(Request $request, $filename)
    {
        if (!$request->hasFile($filename)) {
            return;
        }

        $file = $request->file($filename);
        // information about file
        // $file->getClientOriginalName(); // get the Original name for the image or file from computer client
        // $file->getSize(); // get the size by byte
        // $file->getClientOriginalExtension();
        // $file->getMimeType(); // image/png file/txt,pdf ....

        $path = $file->store('uploads', ['disk' => 'public']);
        return $path;
    }
    private function validateFiles(UploadedFile $file, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'file,max:8000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    }
}
