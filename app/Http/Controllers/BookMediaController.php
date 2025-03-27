<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookMedia;

class BookMediaController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'book_id' => 'required|integer',
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
                $filePath = $file->storeAs('uploads', $fileName, 'public');

                $bookMedia = new BookMedia();
                $bookMedia->book_id = $validatedData['book_id'];
                $bookMedia->media_path = $filePath;
                $bookMedia->save();

                $uploadedFiles[] = [
                    'id' => $bookMedia->id,
                    'book_id' => $bookMedia->book_id,
                    'file_name' => $fileName,
                    'file_path' => $bookMedia->media_path,
                    'created_at' => $bookMedia->created_at,
                ];
            }

            return $this->sendResponse('File(s) uploaded successfully.', $uploadedFiles, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to add file(s)', $e->getMessage(), 500);
        }
    }
    public function deleteBookMedia(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|integer',
            ]);

            $bookMedia = BookMedia::find($validatedData['id']);

            if (!$bookMedia) {
                return $this->sendError('File not found', 'The file you are trying to delete does not exist.', 404);
            }

            $bookMedia->delete();

            return $this->sendResponse('File deleted successfully.', [], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete file', $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $bookMedia = BookMedia::where('book_id', $id)->get();
            $data = [
                'media' => $bookMedia,
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
            'book_id' => 'required|integer|exists:books,id',
            'media_id' => 'required|integer|exists:book_media,id',
        ]);

        // Reset any existing thumbnail for this book
        BookMedia::where('book_id', $request->book_id)
            ->update(['thumbnail' => null]);

        // Set new thumbnail
        BookMedia::where('id', $request->media_id)
            ->update(['thumbnail' => 'thumbnail']);

        return response()->json(['message' => 'Thumbnail updated successfully']);
    }
}
