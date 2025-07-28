<?php
namespace App\Http\Controllers;

use App\Jobs\ProcessUploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    //
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpg,jpeg,png|max:10120',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $file = $request->file('file');
        $path = $file->store('uploads/originals', 'public');

        ProcessUploadedFile::dispatch($path);

        return response()->json(['message' => 'File uploaded successfully. Processing in queue.', 'path' => $path], 202);
    }
}
