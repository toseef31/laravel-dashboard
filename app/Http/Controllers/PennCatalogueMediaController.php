<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PennCatalogueMedia;

class PennCatalogueMediaController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'catelogue_id' => 'required|integer',
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
                $filePath = $file->storeAs('uploads/pennCatalogue', $fileName, 'public');

                $pennCatalogueMedia = new PennCatalogueMedia();
                $pennCatalogueMedia->catelogue_id = $validatedData['catelogue_id'];
                $pennCatalogueMedia->media_path = $filePath;
                $pennCatalogueMedia->save();

                $uploadedFiles[] = [
                    'id' => $pennCatalogueMedia->id,
                    'catelogue_id' => $pennCatalogueMedia->catelogue_id,
                    'file_name' => $fileName,
                    'file_path' => $pennCatalogueMedia->media_path,
                    'created_at' => $pennCatalogueMedia->created_at,
                ];
            }

            return $this->sendResponse('File(s) uploaded successfully.', $uploadedFiles, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to add file(s)', $e->getMessage(), 500);
        }
    }
    public function deletePennCatalogueMedia(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|integer',
            ]);

            $pennCatalogueMedia = PennCatalogueMedia::find($validatedData['id']);

            if (!$pennCatalogueMedia) {
                return $this->sendError('File not found', 'The file you are trying to delete does not exist.', 404);
            }

            $pennCatalogueMedia->delete();

            return $this->sendResponse('File deleted successfully.', [], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete file', $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $pennCatalogueMedia = PennCatalogueMedia::where('catelogue_id', $id)->get();
            $data = [
                'media' => $pennCatalogueMedia,
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
            'pennCatalogue_id' => 'required|integer|exists:penn_catalogues,id',
            'media_id' => 'required|integer|exists:penn_catalogue_media,id',
        ]);

        // Reset any existing thumbnail for this book
        PennCatalogueMedia::where('catelogue_id', $request->pennCatalogue_id)
            ->update(['thumbnail' => null]);

        // Set new thumbnail
        PennCatalogueMedia::where('id', $request->media_id)
            ->update(['thumbnail' => 'thumbnail']);

        return response()->json(['message' => 'Thumbnail updated successfully']);
    }
}
