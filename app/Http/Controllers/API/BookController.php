<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;


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
}
