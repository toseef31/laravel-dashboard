<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Book;

class BookController extends Controller
{

    public function storeBooksFromConfig()
    {
        
        // Retrieve books array from the config file
        $books = config('books');
        $bookData = [];
        $skippedBooks = []; // Array to store skipped book_ids


    
        foreach ($books as $book) {
            // Check if the book_id already exists in the database
            $existingBook = DB::table('books')->where('book_id', $book['book_id'])->exists();
    
            if ($existingBook) {
                // If the book_id already exists, add it to the skipped books array and skip the record
                $skippedBooks[] = $book['book_id'];
                continue;
            }
    
            // Prepare the book data for insertion
            $bookData[] = [
                'book_id'          => $book['book_id'],
                'title'            => $book['title'],
                'author'           => $book['author'] ?? 'Nil',
                'description'      => '', // Add description here if available
                'pages'            => (int) $book['pages'],
                'size'             => $book['size'] ? (int) $book['size'] : null,
                'edition'          => $book['edition'],
                'publisher'        => $book['publisher'],
                'publication_year' => $book['publication_year'],
                'status'           => $book['status'] ?? 'for_sale',
                'book_condition'   => $book['book_condition'],
                'jacket_condition' => $book['jacket_condition'],
                'comment'          => $book['comment'],
                'add_date'         => $book['add_date'],
                'sold_date'        => $book['sold_date'],
                'buyer_name'       => $book['buyer_name'],
                'buyer_email'      => $book['buyer_email'],
                'sold_price'       => $book['sold_price'] !== null ? (float) $book['sold_price'] : null,
                'cost_price'       => $book['cost_price'] !== null ? (float) $book['cost_price'] : null,
                'valuation'        => $book['valuation'],
                'created_at'       => now(),
                'updated_at'       => now(),
            ];
        }
    
        // Insert data in chunks to handle large arrays efficiently
        $chunks = array_chunk($bookData, 5); // Adjust the chunk size if necessary
        $chunkNumber = 0;
       // try{
        foreach ($chunks as $chunk) {
            $chunkNumber++;
            DB::table('books')->insert($chunk);
         }  //}catch (\Exception $e) {
        //     return response()->json(['message' => $chunkNumber], 500);
        // }
    
        // Prepare response message
        $message = [
            'message' => 'Books inserted successfully',
            'skipped_books' => $skippedBooks,
            'inserted_books_count' => count($bookData)
        ];
    
        return response()->json($message, 200);
    }

    public function insertMedia(){
        $files = config('files');
        $bookImages = [];
        foreach ($files as $file) {
            if($file['TableName'] == 'BOOKS'){
                $book = Book::where('book_id', $file['ID'])->first();
                if($book){
                    $bookImages[] = [
                        'book_id' => $book->id,
                        'media_path' => 'uploads/'.basename($file['FilePath']),
                    ];
                }
            }
        }
        $chunks = array_chunk($bookImages, 10);
        foreach ($chunks as $chunk) {
            DB::table('book_media')->insert($chunk);
        }
        return response()->json('Inserted Successfully', 200);
    }
    
}
