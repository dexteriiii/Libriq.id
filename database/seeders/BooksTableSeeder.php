<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('books')->delete();
        
        \DB::table('books')->insert(array (
            0 => 
            array (
                'id' => 1,
                'isbn' => '9789790152786',
            'title' => 'Hutan Hujan (Seri Ada Apa Di Bumi? Bioma)',
                'author' => 'dada',
                'publisher' => 'Erlangga for Kids',
                'publish_year' => 2000,
                'synopsis' => 'ada',
                'category' => 'novel',
                'cover_url' => 'https://books.google.com/books/content?id=h7rqmmR5V9AC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'cover_path' => NULL,
                'rack_location' => NULL,
                'total_stock' => 7,
                'available_stock' => 7,
                'created_at' => '2026-09-10 01:29:02',
                'updated_at' => '2026-09-25 02:27:33',
            ),
            1 => 
            array (
                'id' => 2,
                'isbn' => '1212',
            'title' => 'Buku Saku Tes Kemampuan Akademik (TKA)',
                'author' => 'Hari Wibowo',
                'publisher' => 'Teras Kencana',
                'publish_year' => 2026,
            'synopsis' => 'Tes Kemampuan Akademik (TKA) merupakan salah satu instrumen penting dalam sistem asesmen pendidikan nasional yang berfungsi untuk mengukur kemampuan berpikir logis, analitis, verbal, numerik, dan spasial peserta didik secara objektif dan terstandar. Dalam konteks transformasi pendidikan Indonesia, TKA memiliki peran strategis sebagai alat diagnostik dan pengembangan yang mendukung terwujudnya pembelajaran yang berorientasi pada kompetensi dan karakter sebagaimana tercermin dalam Delapan Profil Lulusan. Buku saku Tes Kemampuan Akademik (TKA) ini disusun berdasarkan Panduan Pusat Asesmen dan Pembelajaran (Pusmendik) Kemendikdasmen, dengan tujuan memberikan pemahaman yang komprehensif kepada pendidik, pengembang asesmen, dan pemangku kepentingan pendidikan mengenai konsep, prinsip, serta implementasi TKA secara profesional dan berintegritas. Isi buku ini mencakup sepuluh bab utama yang disusun secara sistematis, dimulai dari konsep dasar dan landasan hukum TKA, pengembangan instrumen, pelaksanaan tes, hingga analisis hasil dan pemanfaatannya dalam konteks pendidikan. Selain itu, buku ini juga memuat pembahasan mengenai penjaminan mutu, etika asesmen, serta inovasi pengembangan TKA berbasis teknologi digital yang relevan dengan kebutuhan pendidikan abad ke-21. Melalui buku saku ini, diharapkan para pendidik dan pengelola asesmen dapat: Memahami prinsip-prinsip ilmiah dalam penyusunan dan pelaksanaan TKA. Menggunakan hasil TKA secara tepat untuk perencanaan pembelajaran, program remedial, dan pengayaan. Menjamin pelaksanaan asesmen yang adil, objektif, dan berorientasi pada pengembangan peserta didik. Berpartisipasi aktif dalam peningkatan mutu asesmen nasional melalui inovasi dan kolaborasi berkelanjutan. Akhirnya, besar harapan bahwa buku saku ini dapat menjadi rujukan praktis dan ilmiah dalam memperkuat kapasitas satuan pendidikan dalam melaksanakan asesmen akademik yang bermutu, berkeadilan, dan berdaya guna. Semoga upaya ini turut berkontribusi dalam mewujudkan sistem pendidikan nasional yang adaptif, inklusif, dan berorientasi pada keunggulan.',
                'category' => 'Education',
                'cover_url' => 'https://books.google.com/books/content?id=sYPHEQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'cover_path' => NULL,
                'rack_location' => 'T-01',
                'total_stock' => 6,
                'available_stock' => 5,
                'created_at' => '2026-09-10 01:50:37',
                'updated_at' => '2026-09-25 04:15:51',
            ),
            2 => 
            array (
                'id' => 3,
                'isbn' => '9780134494166',
                'title' => 'Clean Architecture: A Craftsman\'s Guide to Software Structure and Design',
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'publish_year' => 2017,
                'synopsis' => 'Praktik terbaik arsitektur perangkat lunak untuk menghasilkan sistem yang bersih, fleksibel, dan mudah dirawat.',
                'category' => 'Teknologi',
                'cover_url' => 'https://covers.openlibrary.org/b/isbn/9780134494166-L.jpg',
                'cover_path' => NULL,
                'rack_location' => 'T-01-A',
                'total_stock' => 5,
                'available_stock' => 5,
                'created_at' => '2026-09-17 02:16:44',
                'updated_at' => '2026-09-17 02:16:44',
            ),
            3 => 
            array (
                'id' => 4,
                'isbn' => '9786024125189',
                'title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'publisher' => 'Penerbit Buku Kompas',
                'publish_year' => 2018,
                'synopsis' => 'Penerapan filsafat Stoisisme dalam kehidupan sehari-hari untuk mengatasi emosi negatif dan mental toughness.',
                'category' => 'Filsafat',
                'cover_url' => 'https://covers.openlibrary.org/b/isbn/9786024125189-L.jpg',
                'cover_path' => NULL,
                'rack_location' => 'F-04-C',
                'total_stock' => 3,
                'available_stock' => 3,
                'created_at' => '2026-09-17 02:16:44',
                'updated_at' => '2026-09-17 02:16:44',
            ),
            4 => 
            array (
                'id' => 5,
                'isbn' => '9786024246945',
                'title' => 'Laut Bercerita',
                'author' => 'Leila S. Chudori',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'publish_year' => 2017,
                'synopsis' => 'Novel tentang perjuangan para aktivis mahasiswa pada era Orde Baru dan kisah keluarga yang kehilangan.',
                'category' => 'Fiksi',
                'cover_url' => 'https://covers.openlibrary.org/b/isbn/9786024246945-L.jpg',
                'cover_path' => NULL,
                'rack_location' => 'S-02-B',
                'total_stock' => 8,
                'available_stock' => 8,
                'created_at' => '2026-09-17 02:16:44',
                'updated_at' => '2026-09-17 02:16:44',
            ),
            5 => 
            array (
                'id' => 6,
                'isbn' => '9780135957059',
                'title' => 'The Pragmatic Programmer: Your Journey to Mastery',
                'author' => 'David Thomas, Andrew Hunt',
                'publisher' => 'Addison-Wesley',
                'publish_year' => 2019,
                'synopsis' => 'Panduan esensial bagi pengembang software profesional dari dasar hingga pengembangan tingkat lanjut.',
                'category' => 'Teknologi',
                'cover_url' => 'https://covers.openlibrary.org/b/isbn/9780135957059-L.jpg',
                'cover_path' => NULL,
                'rack_location' => 'T-02-B',
                'total_stock' => 4,
                'available_stock' => 4,
                'created_at' => '2026-09-17 02:16:44',
                'updated_at' => '2026-09-17 02:16:44',
            ),
            6 => 
            array (
                'id' => 7,
                'isbn' => '9789799731234',
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Hasta Mitra',
                'publish_year' => 1980,
                'synopsis' => 'Kisah Minke di akhir abad ke-19, awal kebangkitan kesadaran nasional di Hindia Belanda.',
                'category' => 'Sastra Klasik',
                'cover_url' => 'https://covers.openlibrary.org/b/isbn/9789799731234-L.jpg',
                'cover_path' => NULL,
                'rack_location' => 'S-01-A',
                'total_stock' => 6,
                'available_stock' => 6,
                'created_at' => '2026-09-17 02:16:44',
                'updated_at' => '2026-09-17 02:16:44',
            ),
            7 => 
            array (
                'id' => 8,
                'isbn' => '9786235081656',
                'title' => 'Buku Ajar Epidemiologi Kesehatan Lingkungan: Pendekatan One Health dan Penilaian Risiko Ekologi',
                'author' => 'Dr. Sang G. Purnama, SKM, MSc, Dr. drh. I Made Subrata, M.Erg',
                'publisher' => 'MEGA PRESS NUSANTARA',
                'publish_year' => 2024,
                'synopsis' => 'Buku "Epidemiologi Kesehatan Lingkungan: Pendekatan One Health dan Penilaian Risiko Ekologi" menyajikan pemahaman mendalam mengenai interaksi antara faktor lingkungan dan kesehatan manusia.',
                'category' => 'Medical',
                'cover_url' => 'https://books.google.com/books/content?id=NHpJEQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'cover_path' => NULL,
                'rack_location' => NULL,
                'total_stock' => 4,
                'available_stock' => 3,
                'created_at' => '2026-09-25 02:27:33',
                'updated_at' => '2026-09-25 02:27:33',
            ),
            8 => 
            array (
                'id' => 9,
                'isbn' => '9786230107245',
                'title' => '7 Materi Pemrograman Web untuk Pemula 5: Laravel & MariaDB',
                'author' => 'Rohi Abdulloh',
                'publisher' => 'PT Elex Media Komputindo',
                'publish_year' => 2022,
                'synopsis' => 'Buku ini membahas 7 materi pemrograman web sekaligus yang menjadi materi utama dalam mempelajari pemrograman web. Disertai contoh pembuatan aplikasi dengan Laravel dan MariaDB.',
                'category' => 'Computers',
                'cover_url' => 'https://books.google.com/books/content?id=P4l-EAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'cover_path' => NULL,
                'rack_location' => NULL,
                'total_stock' => 5,
                'available_stock' => 4,
                'created_at' => '2026-09-25 02:27:33',
                'updated_at' => '2026-09-25 02:27:33',
            ),
            9 => 
            array (
                'id' => 10,
                'isbn' => '9786230005788',
            'title' => 'Logika Pemrograman Java (Update Version)',
                'author' => 'Abdul Kadir',
                'publisher' => 'PT Elex Media Komputindo',
                'publish_year' => 2023,
                'synopsis' => 'Buku ini dirancang sebagai bahan penuntun dalam memprogram komputer menggunakan bahasa Java. Menekankan cara menyelesaikan masalah dengan banyak contoh permasalahan.',
                'category' => 'Computers',
                'cover_url' => 'https://books.google.com/books/content?id=PiG_EAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'cover_path' => NULL,
                'rack_location' => NULL,
                'total_stock' => 2,
                'available_stock' => 2,
                'created_at' => '2026-09-25 02:27:33',
                'updated_at' => '2026-09-25 02:27:33',
            ),
            10 => 
            array (
                'id' => 11,
                'isbn' => '9786342657652',
                'title' => 'Konsep Dasar Sistem Informasi',
                'author' => 'B. Harjo Baskoro, Loso Judijanto, Lucia Sri Istiyowati, Novi Indrayani, Khoirul Islam, Budanis Dwi Meilani, Mahaning Indrawaty Wijaya, Raymond Bahana',
                'publisher' => 'PT. Sonpedia Publishing Indonesia',
                'publish_year' => 2026,
                'synopsis' => 'Buku ini memberikan pemahaman komprehensif mengenai konsep, prinsip, dan penerapan sistem informasi dalam berbagai organisasi, mencakup komponen, jenis, keamanan, hingga tren modern.',
                'category' => 'Business & Economics',
                'cover_url' => 'https://books.google.com/books/content?id=kCLzEQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'cover_path' => NULL,
                'rack_location' => NULL,
                'total_stock' => 1,
                'available_stock' => 1,
                'created_at' => '2026-09-25 02:27:33',
                'updated_at' => '2026-09-25 02:27:33',
            ),
            11 => 
            array (
                'id' => 12,
                'isbn' => '9789791227452',
                'title' => 'Maryamah Karpov',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'publish_year' => 2008,
                'synopsis' => 'Keberanian dan keteguhan hati telah membawa Ikal pada banyak tempat dan peristiwa. Sudut-sudut dunia telah dia kunjungi demi menemukan A Ling.',
                'category' => 'Indonesian fiction',
                'cover_url' => 'https://books.google.com/books/content?id=vzD-3U60S34C&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'cover_path' => NULL,
                'rack_location' => NULL,
                'total_stock' => 1,
                'available_stock' => 1,
                'created_at' => '2026-09-25 02:27:33',
                'updated_at' => '2026-09-25 02:27:33',
            ),
            12 => 
            array (
                'id' => 13,
                'isbn' => '9789799281609',
                'title' => 'Geografi: Jelajah Bumi dan Alam Semesta',
                'author' => 'Rohi Abdulloh',
                'publisher' => 'PT Grafindo Media Pratama',
                'publish_year' => 2009,
                'synopsis' => '-',
                'category' => 'Geografi',
                'cover_url' => 'https://books.google.com/books/content?id=B_-9_R66O3IC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                'cover_path' => NULL,
                'rack_location' => NULL,
                'total_stock' => 3,
                'available_stock' => 3,
                'created_at' => '2026-09-25 02:27:33',
                'updated_at' => '2026-09-25 02:27:33',
            ),
        ));
        
        
    }
}