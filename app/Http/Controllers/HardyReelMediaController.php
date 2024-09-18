<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HardyReelMedia;

class HardyReelMediaController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'reel_id' => 'required|integer',
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
                $filePath = $file->storeAs('uploads/hardy-reels', $fileName, 'public');
    
                $reelMedia = new HardyReelMedia();
                $reelMedia->reel_id = $validatedData['reel_id'];
                $reelMedia->media_path = $filePath;
                $reelMedia->save();
    
                $uploadedFiles[] = [
                    'id' => $reelMedia->id,
                    'reel_id' => $reelMedia->reel_id,
                    'file_name' => $fileName,
                    'file_path' => $reelMedia->media_path,
                    'created_at' => $reelMedia->created_at,
                ];
            }
    
            return $this->sendResponse('File(s) uploaded successfully.', $uploadedFiles, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to add file(s)', $e->getMessage(), 500);
        }
    }
    public function deleteHardyReelMedia(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|integer',
            ]);
    
            $reelMedia = HardyReelMedia::find($validatedData['id']);
    
            if (!$reelMedia) {
                return $this->sendError('File not found', 'The file you are trying to delete does not exist.', 404);
            }
    
            $reelMedia->delete();
    
            return $this->sendResponse('File deleted successfully.', [], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete file', $e->getMessage(), 500);
        }
    }
    
    public function show($id)
    {
        try {
            $reelMedia = HardyReelMedia::where('reel_id', $id)->get();
            $data = [
                'media' => $reelMedia,
                'media_path' => '/storage/'
            ];
            return $this->sendResponse('File(s) fetched successfully', $data, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch file', $e->getMessage(), 500);
        }
    }
}
