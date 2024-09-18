<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EphemeraMedia;

class EphemeraMediaController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'ephemera_id' => 'required|integer',
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
                $filePath = $file->storeAs('uploads/ephemera', $fileName, 'public');
    
                $ephemeraMedia = new EphemeraMedia();
                $ephemeraMedia->ephemera_id = $validatedData['ephemera_id'];
                $ephemeraMedia->media_path = $filePath;
                $ephemeraMedia->save();
    
                $uploadedFiles[] = [
                    'id' => $ephemeraMedia->id,
                    'ephemera_id' => $ephemeraMedia->ephemera_id,
                    'file_name' => $fileName,
                    'file_path' => $ephemeraMedia->media_path,
                    'created_at' => $ephemeraMedia->created_at,
                ];
            }
    
            return $this->sendResponse('File(s) uploaded successfully.', $uploadedFiles, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to add file(s)', $e->getMessage(), 500);
        }
    }
    public function deleteEphemeraMedia(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|integer',
            ]);
    
            $ephemeraMedia = EphemeraMedia::find($validatedData['id']);
    
            if (!$ephemeraMedia) {
                return $this->sendError('File not found', 'The file you are trying to delete does not exist.', 404);
            }
    
            $ephemeraMedia->delete();
    
            return $this->sendResponse('File deleted successfully.', [], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete file', $e->getMessage(), 500);
        }
    }
    
    public function show($id)
    {
        try {
            $ephemeraMedia = EphemeraMedia::where('ephemera_id', $id)->get();
            $data = [
                'media' => $ephemeraMedia,
                'media_path' => '/storage/'
            ];
            return $this->sendResponse('File(s) fetched successfully', $data, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch file', $e->getMessage(), 500);
        }
    }
}
