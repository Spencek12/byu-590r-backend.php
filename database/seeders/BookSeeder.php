<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class BookSeeder extends Seeder {
    public function run() {
        $books = [
            [
                'name' => 'The Book of Mormon',
                'description' => 'Another Testament of Jesus Christ',
                'book_cover_picture' => 'images/bom.png'
            ],
            [
                'name' => 'The Bible',
                'description' => 'A Testament of Jesus Christ',
                'book_cover_picture' => 'images/bible.png'
            ],
            [
                'name' => 'Grit',
                'description' => 'The power of passion and perseverance.',
                'book_cover_picture' => 'images/grit.png'
            ],
            [
                'name' => 'The False Prince',
                'description' => 'Orphan competes to impersonate prince in dangerous political plot',
                'book_cover_picture' => 'images/falseprince.png'
            ],
            [
                'name' => 'The Lightning Thief',
                'description' => 'Young demigod embarks on mythological quest.',
                'book_cover_picture' => 'images/lightningthief.png'
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
