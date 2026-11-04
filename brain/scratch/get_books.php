<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$books = \App\Models\Book::select('id','title','author','category','available_stock','cover_url','cover_path','synopsis')->get();
foreach ($books as $b) {
    echo $b->id . ' | ' . $b->title . ' | ' . $b->author . ' | ' . $b->category . ' | stock:' . $b->available_stock . ' | cover_url:' . $b->cover_url . ' | cover_path:' . $b->cover_path . "\n";
    echo "   synopsis: " . substr($b->synopsis ?? '', 0, 80) . "\n";
}
