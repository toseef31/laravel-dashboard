<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use DB;

class BookController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'description' => 'required|string',
                'pages' => 'required|integer|min:0',
                'size' => 'nullable|integer|min:0',
                'edition' => 'nullable|string|max:255',
                'publisher' => 'nullable|string|max:255',
                'publication_year' => 'nullable|string|max:4',
                'status' => 'nullable|string|in:for_sale,sold,out_of_stock,not_selected',
                'book_condition' => 'nullable|string',
                'jacket_condition' => 'nullable|string',
                'comment' => 'nullable|string',
                'add_date' => 'nullable|string|date_format:Y-m-d',
                'sold_date' => 'nullable|string|date_format:Y-m-d',
                'buyer_name' => 'nullable|string|max:255',
                'buyer_email' => 'nullable|string|email|max:255',
                'sold_price' => 'nullable|numeric|min:0',
                'cost_price' => 'nullable|numeric|min:0',
                'valuation' => 'nullable|string|max:255',
            ]);

            $book = Book::create($validatedData);
            return $this->sendResponse('Book Created Successfully', $book, 201);

        } catch (\Exception $e) {
            return $this->sendError('Failed to create book', $e->getMessage(), 500);
        }
    }
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 25);
            $query = Book::query();
            $query->orderBy('id', 'desc');
    
            if ($request->has('book_id')) {
                $query->where('book_id', $request->input('book_id'));
            }
    
            if ($request->has('title')) {
                $query->where('title', 'LIKE', '%' . $request->input('title') . '%');
            }
    
            if ($request->has('publisher')) {
                $query->where('publisher', 'LIKE', '%' . $request->input('publisher') . '%');
            }
    
            if ($request->has('author')) {
                $query->where('author', 'LIKE', '%' . $request->input('author') . '%');
            }
    
            if ($request->has('publication_year')) {
                $query->where('publication_year', $request->input('publication_year'));
            }
    
            if ($request->has('edition')) {
                $query->where('edition', 'LIKE', '%' . $request->input('edition') . '%');
            }
            $query->with('bookMedia', 'bookSize');
            $books = $query->paginate($perPage);
            return $this->sendPaginatedResponse($books, 200);
    
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch books', $e->getMessage(), 500);
        }
    }
    public function deleteBook(Request $request)
    {
        try {
            $book = Book::find($request->id);
            if($book){
                $book->delete();
                return $this->sendResponse('Book deleted successfully', null, 200);
            }else{
                return $this->sendError('Book not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete book', $e->getMessage(), 500);
        }
    }
    public function editBook(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'id' => 'required|integer|min:0',
            ]);
    
            if ($request->has('book_id')) {
                return $this->sendError('Validation Error, Book ID cannot be edited', null, 422);
            }
    
            $book = Book::find($request->id);
    
            if ($book) {
                $updateData = $request->except('id');
                $book->update($updateData);
    
                return $this->sendResponse('Book updated successfully', $book, 200);
            } else {
                return $this->sendError('Book not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to update book', $e->getMessage(), 500);
        }
    }
    public function show(Request $request){
        try {
            $book = Book::find($request->id);
            if($book){
                return $this->sendResponse('Book fetched successfully', $book, 200);
            }else{
                return $this->sendError('Book not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch book', $e->getMessage(), 500);
        }
    }
    
    public function duplicateBook($id)
    {
        try {
            # Find the book by ID
            $book = Book::with('bookMedia')->find($id); // Include the related media
    
            if ($book) {
                # Duplicate the book
                $newBook = $book->replicate();
                $newBook->book_condition = '';
                $newBook->jacket_condition = '';
                $newBook->comment = '';
                $newBook->add_date = '';
                $newBook->save();
    
                // # Duplicate associated media
                // foreach ($book->bookMedia as $media) {
                //     $newMedia = $media->replicate();
                //     $newMedia->book_id = $newBook->id; // Associate with the new book
                //     $newMedia->save();
                // }
    
                return $this->sendResponse('Book and its media duplicated successfully, new book ID: ' . $newBook->book_id, $newBook, 200);
            } else {
                return $this->sendError('Book not found', null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to duplicate book and media', $e->getMessage(), 500);
        }
    }
    public function compareBooks(Request $request){
        try {
            $request->validate([
                'book_ids' => 'required|array',
                'book_ids.*' => 'required|integer|min:0',
            ]);
            $books = Book::whereIn('id', $request->input('book_ids'))->with('bookMedia', 'bookSize')->get();
            return $this->sendResponse('Books fetched successfully', $books, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch selected books details', $e->getMessage(), 500);
        }
    }
    public function getNextBookId()
    {
        try {
            // Get the maximum 'id' from the 'Book' table
            $nextId = DB::select("SHOW TABLE STATUS LIKE 'books'");
            $nextAutoIncrementId = $nextId[0]->Auto_increment;

            // Get the maximum value of the book_id column (ignoring the 'B' prefix)
            $maxBookId = Book::max(DB::raw("CAST(SUBSTRING(book_id, 2) AS UNSIGNED)"));

            // Determine the next ID to be greater than both the next auto-increment and max book_id
            $nextIdToUse = max($nextAutoIncrementId, $maxBookId + 1);

            // Generate the next book_id with 'B' prefix and 5 digits, padded with zeros
            $nextBookId = 'B' . str_pad($nextIdToUse, 5, '0', STR_PAD_LEFT);
            
            return $this->sendResponse('Next book ID fetched successfully', ['book_id' => $nextBookId], 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch next book ID', $e->getMessage(), 500);
        }
    }
    
}
