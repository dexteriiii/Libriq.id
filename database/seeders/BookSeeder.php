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
            // ── Buku asli dari PRD ───────────────────────────────────────────
            [
                'isbn'            => '9780134494166',
                'title'           => 'Clean Architecture: A Craftsman\'s Guide to Software Structure and Design',
                'author'          => 'Robert C. Martin',
                'publisher'       => 'Prentice Hall',
                'publish_year'    => 2017,
                'synopsis'        => 'Praktik terbaik arsitektur perangkat lunak untuk menghasilkan sistem yang bersih, fleksibel, dan mudah dirawat.',
                'category'        => 'Teknologi',
                'cover_url'       => 'https://covers.openlibrary.org/b/isbn/9780134494166-L.jpg',
                'rack_location'   => 'T-01-A',
                'total_stock'     => 5,
                'available_stock' => 5,
            ],
            [
                'isbn'            => '9786024125189',
                'title'           => 'Filosofi Teras',
                'author'          => 'Henry Manampiring',
                'publisher'       => 'Penerbit Buku Kompas',
                'publish_year'    => 2018,
                'synopsis'        => 'Penerapan filsafat Stoisisme dalam kehidupan sehari-hari untuk mengatasi emosi negatif dan mental toughness.',
                'category'        => 'Filsafat',
                'cover_url'       => 'https://covers.openlibrary.org/b/isbn/9786024125189-L.jpg',
                'rack_location'   => 'F-04-C',
                'total_stock'     => 3,
                'available_stock' => 3,
            ],
            [
                'isbn'            => '9786024246945',
                'title'           => 'Laut Bercerita',
                'author'          => 'Leila S. Chudori',
                'publisher'       => 'Kepustakaan Populer Gramedia',
                'publish_year'    => 2017,
                'synopsis'        => 'Novel tentang perjuangan para aktivis mahasiswa pada era Orde Baru dan kisah keluarga yang kehilangan.',
                'category'        => 'Fiksi',
                'cover_url'       => 'https://covers.openlibrary.org/b/isbn/9786024246945-L.jpg',
                'rack_location'   => 'S-02-B',
                'total_stock'     => 8,
                'available_stock' => 8,
            ],
            [
                'isbn'            => '9780135957059',
                'title'           => 'The Pragmatic Programmer: Your Journey to Mastery',
                'author'          => 'David Thomas, Andrew Hunt',
                'publisher'       => 'Addison-Wesley',
                'publish_year'    => 2019,
                'synopsis'        => 'Panduan esensial bagi pengembang software profesional dari dasar hingga pengembangan tingkat lanjut.',
                'category'        => 'Teknologi',
                'cover_url'       => 'https://covers.openlibrary.org/b/isbn/9780135957059-L.jpg',
                'rack_location'   => 'T-02-B',
                'total_stock'     => 4,
                'available_stock' => 4,
            ],
            [
                'isbn'            => '9789799731234',
                'title'           => 'Bumi Manusia',
                'author'          => 'Pramoedya Ananta Toer',
                'publisher'       => 'Hasta Mitra',
                'publish_year'    => 1980,
                'synopsis'        => 'Kisah Minke di akhir abad ke-19, awal kebangkitan kesadaran nasional di Hindia Belanda.',
                'category'        => 'Sastra Klasik',
                'cover_url'       => 'https://covers.openlibrary.org/b/isbn/9789799731234-L.jpg',
                'rack_location'   => 'S-01-A',
                'total_stock'     => 6,
                'available_stock' => 6,
            ],
            // ── Buku yang ditambahkan melalui sistem ─────────────────────────
            [
                'isbn'            => '9789790152786',
                'title'           => 'Hutan Hujan (Seri Ada Apa Di Bumi? Bioma)',
                'author'          => 'dada',
                'publisher'       => 'Erlangga for Kids',
                'publish_year'    => 2000,
                'synopsis'        => 'ada',
                'category'        => 'novel',
                'cover_url'       => 'https://books.google.com/books/content?id=h7rqmmR5V9AC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'rack_location'   => null,
                'total_stock'     => 7,
                'available_stock' => 7,
            ],
            [
                'isbn'            => '9786235081656',
                'title'           => 'Buku Ajar Epidemiologi Kesehatan Lingkungan: Pendekatan One Health dan Penilaian Risiko Ekologi',
                'author'          => 'Dr. Sang G. Purnama, SKM, MSc, Dr. drh. I Made Subrata, M.Erg',
                'publisher'       => 'MEGA PRESS NUSANTARA',
                'publish_year'    => 2024,
                'synopsis'        => 'Buku "Epidemiologi Kesehatan Lingkungan: Pendekatan One Health dan Penilaian Risiko Ekologi" menyajikan pemahaman mendalam mengenai interaksi antara faktor lingkungan dan kesehatan manusia.',
                'category'        => 'Medical',
                'cover_url'       => 'https://books.google.com/books/content?id=NHpJEQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'rack_location'   => null,
                'total_stock'     => 4,
                'available_stock' => 3,
            ],
            [
                'isbn'            => '9786230107245',
                'title'           => '7 Materi Pemrograman Web untuk Pemula 5: Laravel & MariaDB',
                'author'          => 'Rohi Abdulloh',
                'publisher'       => 'PT Elex Media Komputindo',
                'publish_year'    => 2022,
                'synopsis'        => 'Buku ini membahas 7 materi pemrograman web sekaligus yang menjadi materi utama dalam mempelajari pemrograman web. Disertai contoh pembuatan aplikasi dengan Laravel dan MariaDB.',
                'category'        => 'Computers',
                'cover_url'       => 'https://books.google.com/books/content?id=P4l-EAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'rack_location'   => null,
                'total_stock'     => 5,
                'available_stock' => 4,
            ],
            [
                'isbn'            => '9786230005788',
                'title'           => 'Logika Pemrograman Java (Update Version)',
                'author'          => 'Abdul Kadir',
                'publisher'       => 'PT Elex Media Komputindo',
                'publish_year'    => 2023,
                'synopsis'        => 'Buku ini dirancang sebagai bahan penuntun dalam memprogram komputer menggunakan bahasa Java. Menekankan cara menyelesaikan masalah dengan banyak contoh permasalahan.',
                'category'        => 'Computers',
                'cover_url'       => 'https://books.google.com/books/content?id=PiG_EAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'rack_location'   => null,
                'total_stock'     => 2,
                'available_stock' => 2,
            ],
            [
                'isbn'            => '9786342657652',
                'title'           => 'Konsep Dasar Sistem Informasi',
                'author'          => 'B. Harjo Baskoro, Loso Judijanto, Lucia Sri Istiyowati, Novi Indrayani, Khoirul Islam, Budanis Dwi Meilani, Mahaning Indrawaty Wijaya, Raymond Bahana',
                'publisher'       => 'PT. Sonpedia Publishing Indonesia',
                'publish_year'    => 2026,
                'synopsis'        => 'Buku ini memberikan pemahaman komprehensif mengenai konsep, prinsip, dan penerapan sistem informasi dalam berbagai organisasi, mencakup komponen, jenis, keamanan, hingga tren modern.',
                'category'        => 'Business & Economics',
                'cover_url'       => 'https://books.google.com/books/content?id=kCLzEQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'rack_location'   => null,
                'total_stock'     => 1,
                'available_stock' => 1,
            ],
            [
                'isbn'            => '9789791227452',
                'title'           => 'Maryamah Karpov',
                'author'          => 'Andrea Hirata',
                'publisher'       => 'Bentang Pustaka',
                'publish_year'    => 2008,
                'synopsis'        => 'Keberanian dan keteguhan hati telah membawa Ikal pada banyak tempat dan peristiwa. Sudut-sudut dunia telah dia kunjungi demi menemukan A Ling.',
                'category'        => 'Indonesian fiction',
                'cover_url'       => 'https://books.google.com/books/content?id=vzD-3U60S34C&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'rack_location'   => null,
                'total_stock'     => 1,
                'available_stock' => 1,
            ],
            [
                'isbn'            => '9789799281609',
                'title'           => 'Geografi: Jelajah Bumi dan Alam Semesta',
                'author'          => 'Rohi Abdulloh',
                'publisher'       => 'PT Grafindo Media Pratama',
                'publish_year'    => 2009,
                'synopsis'        => '-',
                'category'        => 'Geografi',
                'cover_url'       => 'https://books.google.com/books/content?id=B_-9_R66O3IC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'rack_location'   => null,
                'total_stock'     => 3,
                'available_stock' => 3,
            ],
        ];

        foreach ($books as $bookData) {
            Book::updateOrCreate(['isbn' => $bookData['isbn']], $bookData);
        }
    }
}
