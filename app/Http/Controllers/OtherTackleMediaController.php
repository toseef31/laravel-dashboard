<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OtherTackleMedia;

class OtherTackleMediaController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'tackle_id' => 'required|integer',
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
                $filePath = $file->storeAs('uploads/tackles', $fileName, 'public');

                $tacklesMedia = new OtherTackleMedia();
                $tacklesMedia->tackle_id = $validatedData['tackle_id'];
                $tacklesMedia->media_path = $filePath;
                $tacklesMedia->save();

                $uploadedFiles[] = [
                    'id' => $tacklesMedia->id,
                    'tackle_id' => $tacklesMedia->tackle_id,
                    'file_name' => $fileName,
                    'file_path' => $tacklesMedia->media_path,
                    'created_at' => $tacklesMedia->created_at,
                ];
            }

            return $this->sendResponse('File(s) uploaded successfully.', $uploadedFiles, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to add file(s)', $e->getMessage(), 500);
        }
    }
    public function deleteOtherTackleMedia(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|integer',
            ]);

            $tacklesMedia = OtherTackleMedia::find($validatedData['id']);

            if (!$tacklesMedia) {
                return $this->sendError('File not found', 'The file you are trying to delete does not exist.', 404);
            }

            $tacklesMedia->delete();

            return $this->sendResponse('File deleted successfully.', [], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete file', $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $tacklesMedia = OtherTackleMedia::where('tackle_id', $id)->get();
            $data = [
                'media' => $tacklesMedia,
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
            'tackle_id' => 'required|integer|exists:other_tackles,id',
            'media_id' => 'required|integer|exists:other_tackle_media,id',
        ]);

        // Reset any existing thumbnail for this book
        OtherTackleMedia::where('tackle_id', $request->tackle_id)
            ->update(['thumbnail' => null]);

        // Set new thumbnail
        OtherTackleMedia::where('id', $request->media_id)
            ->update(['thumbnail' => 'thumbnail']);

        return response()->json(['message' => 'Thumbnail updated successfully']);
    }
}
