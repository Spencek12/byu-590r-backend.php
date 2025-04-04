<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;


class BookController extends BaseController {
    public function index() {
        $books = Book::all()->map(function ($book) {
            $bookCoverPath = $book->book_cover_picture;
            $fullPath = $this->getS3Url($bookCoverPath);

            // Log the full path to the console
            error_log("Full path for book ID {$book->id}: {$fullPath}");

            $book->book_cover_picture = $fullPath;
            return $book;
        });

        return response()->json($books);
    }

    public function store (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            // 'inventory_total_qty' => 'required|integer |min:1'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $book = new Book();

        if ($request->hasFile('file')) {
            $extension = $request->file('file')->getClientOriginalExtension();
            $image_name = time() . '_book_cover.' . $extension;
            $path = $request->file('file')->storeAs('book_cover_pictures', $image_name, 's3');

            Storage::disk('s3')->setVisibility($path, 'public');
            if(!$path) {
                return $this->sendError('Book cover picture upload failed.');
            }
            $book->book_cover_picture = $path;
        }

        $book->name = $request->name;
        $book->description = $request->description;
        // $book->inventory_total_qty = $request->inventory_total_qty;
        // $book->checked_qty = 0;
        // $book->genre_id = request->genre_id;

        $book->save();

        if(isset($book->file)) {
            $book->book_cover_picture = $this->getS3Url($book->book_cover_picture);
        }
        $success['book'] = $book;
        return $this->sendResponse($success, 'Book created successfully.');
    }

    public function updateBookPicture(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $book = Book::findOrFail($id);
        if (is_null($book)) {
            return $this->sendError('Book not found.');
        }

        if ($request->hasFile('file')) {
            $extension = $request->file('file')->getClientOriginalExtension();
            $image_name = time() . '_book_cover.' . $extension;
            $path = $request->file('file')->storeAs('book_cover_pictures', $image_name, 's3');

            Storage::disk('s3')->setVisibility($path, 'public');
            if(!$path) {
                return $this->sendError('Book cover picture upload failed.');
            }
            $book->book_cover_picture = $path;
        }

        $book->save();
        if(isset($book->file)) {
            $book->book_cover_picture = $this->getS3Url($book->book_cover_picture);
        }
        return response()->json(['message' => 'Book cover picture updated successfully.', 'book' => $book]);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $book = Book::find($id);
        if (is_null($book)) {
            return $this->sendError('Book not found.');
        }

        $book = Book::findorFail($id);
        $book->name = $request->name;
        $book->description = $request->description;
        // $book->inventory_total_qty = $request->inventory_total_qty;
        $book->save();
        if(isset($book->file)) {
            $book->book_cover_picture = $this->getS3Url($book->book_cover_picture);
        }
        $success['book'] = $book;
        return $this->sendResponse($book, 'Book updated successfully.');

    }

    public function destroy($id) {
        $book = Book::findOrFail($id);
        Storage::disk('s3')->delete($book->book_cover_picture);
        $book->delete();
        $success['book']['id'] = $id;
        return $this->sendResponse($success, 'Book deleted successfully.');
    }

}
