<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre;


class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $genres = [
            'Science Fiction',
            'Fantasy',
            'Mystery',
            'Biography',
            'Historical Fiction',
            'Religious',
            'Self-Help',
        ];
    
        foreach ($genres as $name) {
            Genre::firstOrCreate(['name' => $name]);
        }
    }
}
