<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'isbn' => '9780134494166',
                'title' => 'Clean Architecture: A Craftsman\'s Guide to Software Structure and Design',
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'publish_year' => 2017,
                'synopsis' => 'Praktik terbaik arsitektur perangkat lunak untuk menghasilkan sistem yang bersih, fleksibel, dan mudah dirawat.',
                'category' => 'Teknologi',
                'cover_url' => 'https://covers.openlibrary.org/b/isbn/9780134494166-L.jpg',
                'rack_location' => 'T-01-A',
                'total_stock' => 5,
                'available_stock' => 5,
            ],
            [
                'isbn' => '9786024125189',
                'title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'publisher' => 'Penerbit Buku Kompas',
                'publish_year' => 2018,
                'synopsis' => 'Penerapan filsafat Stoisisme dalam kehidupan sehari-hari untuk mengatasi emosi negatif dan mental toughness.',
                'category' => 'Filsafat',
                'cover_url' => 'https://covers.openlibrary.org/b/isbn/9786024125189-L.jpg',
                'rack_location' => 'F-04-C',
                'total_stock' => 3,
                'available_stock' => 3,
            ],
            [
                'isbn' => '9786024246945',
                'title' => 'Laut Bercerita',
                'author' => 'Leila S. Chudori',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'publish_year' => 2017,
                'synopsis' => 'Novel tentang perjuangan para aktivis mahasiswa pada era Orde Baru dan kisah keluarga yang kehilangan.',
                'category' => 'Fiksi',
                'cover_url' => 'https://covers.openlibrary.org/b/isbn/9786024246945-L.jpg',
                'rack_location' => 'S-02-B',
                'total_stock' => 8,
                'available_stock' => 8,
            ],
            [
                'isbn' => '9780135957059',
                'title' => 'The Pragmatic Programmer: Your Journey to Mastery',
                'author' => 'David Thomas, Andrew Hunt',
                'publisher' => 'Addison-Wesley',
                'publish_year' => 2019,
                'synopsis' => 'Panduan esensial bagi pengembang software profesional dari dasar hingga pengembangan tingkat lanjut.',
                'category' => 'Teknologi',
                'cover_url' => 'https://covers.openlibrary.org/b/isbn/9780135957059-L.jpg',
                'rack_location' => 'T-02-B',
                'total_stock' => 4,
                'available_stock' => 4,
            ],
            [
                'isbn' => '9789799731234',
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Hasta Mitra',
                'publish_year' => 1980,
                'synopsis' => 'Kisah Minke di akhir abad ke-19, awal kebangkitan kesadaran nasional di Hindia Belanda.',
                'category' => 'Sastra Klasik',
                'cover_url' => 'https://covers.openlibrary.org/b/isbn/9789799731234-L.jpg',
                'rack_location' => 'S-01-A',
                'total_stock' => 6,
                'available_stock' => 6,
            ],
            [
                'isbn' => '9789790152786',
                'title' => 'Hutan Hujan (Seri Ada Apa Di Bumi? Bioma)',
                'author' => 'dada',
                'publisher' => 'Erlangga for Kids',
                'publish_year' => 2000,
                'synopsis' => 'ada',
                'category' => 'novel',
                'cover_url' => 'https://books.google.com/books/content?id=h7rqmmR5V9AC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'rack_location' => 'R-01-A',
                'total_stock' => 7,
                'available_stock' => 7,
            ],
        ];

        foreach ($books as $bookData) {
            Book::updateOrCreate(['isbn' => $bookData['isbn']], $bookData);
        }
    }
}
