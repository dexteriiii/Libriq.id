<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Tampilkan detail buku id=1 dulu
$book = \App\Models\Book::find(1);
echo "ID: " . $book->id . "\n";
echo "Title: " . $book->title . "\n";
echo "Author: " . $book->author . "\n";
echo "Category: " . $book->category . "\n";
echo "ISBN: " . $book->isbn . "\n";
echo "Total Stock: " . $book->total_stock . "\n";
echo "Available Stock: " . $book->available_stock . "\n";
echo "Cover URL: " . $book->cover_url . "\n";
echo "Synopsis: " . $book->synopsis . "\n";
echo "Rack: " . $book->rack_location . "\n";
