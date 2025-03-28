<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RodsMedia;

class RodsMediaController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'rod_id' => 'required|integer',
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
                $fileName = $this->cleanString(time() . '_' . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('uploads/rods', $fileName, 'public');

                $rodsMedia = new RodsMedia();
                $rodsMedia->rod_id = $validatedData['rod_id'];
                $rodsMedia->media_path = $filePath;
                $rodsMedia->save();

                $uploadedFiles[] = [
                    'id' => $rodsMedia->id,
                    'rods_id' => $rodsMedia->rod_id,
                    'file_name' => $fileName,
                    'file_path' => $rodsMedia->media_path,
                    'created_at' => $rodsMedia->created_at,
                ];
            }

            return $this->sendResponse('File(s) uploaded successfully.', $uploadedFiles, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to add file(s)', $e->getMessage(), 500);
        }
    }
    public function deleteRodsMedia(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|integer',
            ]);

            $rodsMedia = RodsMedia::find($validatedData['id']);

            if (!$rodsMedia) {
                return $this->sendError('File not found', 'The file you are trying to delete does not exist.', 404);
            }

            $rodsMedia->delete();

            return $this->sendResponse('File deleted successfully.', [], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete file', $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $rodsMedia = RodsMedia::where('rod_id', $id)->get();
            $data = [
                'media' => $rodsMedia,
                'media_path' => '/storage/'
            ];
            return $this->sendResponse('File(s) fetched successfully', $data, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch file', $e->getMessage(), 500);
        }
    }
    public function setThumbnail(Request $request)
    {
        $request->validate([
            'rod_id' => 'required|integer|exists:rods,id',
            'media_id' => 'required|integer|exists:rods_media,id',
        ]);

        // Reset any existing thumbnail for this book
        RodsMedia::where('rod_id', $request->rod_id)
            ->update(['thumbnail' => null]);

        // Set new thumbnail
        RodsMedia::where('id', $request->media_id)
            ->update(['thumbnail' => 'thumbnail']);

        return response()->json(['message' => 'Thumbnail updated successfully']);
    }
}
