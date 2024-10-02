<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LuresMedia;

class LuresMediaController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'lures_id' => 'required|integer',
                'media_file' => 'required', # Accepts either a file or an array of files
                'media_file.*' => 'file|mimes:jpg,jpeg,png,gif|max:2048', # Validation for each file
            ]);
    
            $files = $request->file('media_file');
    
            # Check if it's an array of files or a single file
            if (!is_array($files)) {
                $files = [$files]; # Convert single file to an array for uniform processing
            }
    
            $uploadedFiles = [];
    
            foreach ($files as $file) {
                $fileName = $this->cleanString(time() . '_' . $file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();
                $filePath = $file->storeAs('uploads/lures', $fileName, 'public');
    
                $luresMedia = new LuresMedia();
                $luresMedia->lures_id = $validatedData['lures_id'];
                $luresMedia->media_path = $filePath;
                $luresMedia->save();
    
                $uploadedFiles[] = [
                    'id' => $luresMedia->id,
                    'lures_id' => $luresMedia->lures_id,
                    'file_name' => $fileName,
                    'file_path' => $luresMedia->media_path,
                    'created_at' => $luresMedia->created_at,
                ];
            }
    
            return $this->sendResponse('File(s) uploaded successfully.', $uploadedFiles, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to add file(s)', $e->getMessage(), 500);
        }
    }
    public function deleteLuresMedia(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|integer',
            ]);
    
            $luresMedia = LuresMedia::find($validatedData['id']);
    
            if (!$luresMedia) {
                return $this->sendError('File not found', 'The file you are trying to delete does not exist.', 404);
            }
    
            $luresMedia->delete();
    
            return $this->sendResponse('File deleted successfully.', [], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete file', $e->getMessage(), 500);
        }
    }
    
    public function show($id)
    {
        try {
            $luresMedia = LuresMedia::where('lures_id', $id)->get();
            $data = [
                'media' => $luresMedia,
                'media_path' => '/storage/'
            ];
            return $this->sendResponse('File(s) fetched successfully', $data, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch file', $e->getMessage(), 500);
        }
    }
}
