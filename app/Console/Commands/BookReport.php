<?php

namespace App\Console\Commands;
use App\Models\Book;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookListMail;
use Illuminate\Console\Command;

class BookReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:book-report {--email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A report of all of my books';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sendToEmail = $this->option('email');
        $books = Book::all();

        Mail::to($sendToEmail)->send(new BookListMail($books));

        return Command::SUCCESS;
    }
}
