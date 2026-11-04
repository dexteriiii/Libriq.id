<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Update buku id=1 dengan data yang proper: "Laskar Pelangi" - Andrea Hirata
$book = \App\Models\Book::find(1);

$book->update([
    'isbn'            => '9789792278095',
    'title'           => 'Laskar Pelangi',
    'author'          => 'Andrea Hirata',
    'publisher'       => 'Bentang Pustaka',
    'publish_year'    => 2005,
    'category'        => 'Fiksi',
    'cover_url'       => 'https://covers.openlibrary.org/b/isbn/9789792278095-L.jpg',
    'cover_path'      => null,
    'rack_location'   => 'A-01',
    'synopsis'        => 'Laskar Pelangi adalah novel pertama karya Andrea Hirata yang menceritakan kisah sepuluh anak dari keluarga miskin di Belitung yang berjuang untuk tetap bersekolah meski kondisi sekolah mereka sangat memprihatinkan. Kisah persahabatan, semangat belajar, dan mimpi yang tak pernah padam menjadi inti dari novel ini. Karya ini telah memenangkan berbagai penghargaan sastra Indonesia dan diadaptasi menjadi film yang sangat sukses.',
]);

echo "BERHASIL diupdate:\n";
echo "Title: " . $book->fresh()->title . "\n";
echo "Author: " . $book->fresh()->author . "\n";
echo "Cover: " . $book->fresh()->cover_url . "\n";
