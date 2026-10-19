<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleBooksService
{
    private const BASE_URL        = 'https://www.googleapis.com/books/v1/volumes';
    private const OPEN_LIB_URL    = 'https://openlibrary.org/api/books';
    private const OPEN_LIB_SEARCH = 'https://openlibrary.org/search.json';
    private const CACHE_TTL       = 86400; // 24 jam
    private const TIMEOUT_SECS    = 5;

    /**
     * Fetch metadata buku berdasarkan ISBN.
     * Menggunakan multi-strategi: Google Books API -> Fallback ke Open Library API.
     */
    public function fetchByIsbn(string $isbn): ?array
    {
        $cleanIsbn = preg_replace('/[^0-9X]/i', '', $isbn);
        if (! $cleanIsbn) {
            return null;
        }

        $cacheKey = "google_books.isbn.{$cleanIsbn}";

        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            if ($cached !== null) {
                return $cached;
            }
        }

        $result = null;

        // Strategi 1: Coba Google Books API dengan prefix 'isbn:'
        try {
            $result = $this->queryApi("isbn:{$cleanIsbn}");
        } catch (\Exception $e) {
            Log::info("GoogleBooksService: queryApi isbn:{$cleanIsbn} failed or rate limited", ['error' => $e->getMessage()]);
        }

        // Strategi 2: Jika belum ketemu, coba Google Books API tanpa prefix 'isbn:'
        if (! $result) {
            try {
                $result = $this->queryApi($cleanIsbn);
            } catch (\Exception $e) {
                Log::info("GoogleBooksService: queryApi plain {$cleanIsbn} failed", ['error' => $e->getMessage()]);
            }
        }

        // Strategi 3: Fallback ke Open Library API jika Google Books tidak menemukan atau error
        if (! $result) {
            $result = $this->fetchFromOpenLibrary($cleanIsbn);
        }

        if ($result !== null) {
            Cache::put($cacheKey, $result, self::CACHE_TTL);
        }

        return $result;
    }

    /**
     * Fetch metadata buku berdasarkan judul.
     */
    public function fetchByTitle(string $title): ?array
    {
        $result = null;

        try {
            $result = $this->queryApi($title);
        } catch (\Exception $e) {
            Log::info("GoogleBooksService: queryApi title '{$title}' failed or rate limited", ['error' => $e->getMessage()]);
        }

        // Fallback ke Open Library Search jika Google Books gagal/rate limited
        if (! $result) {
            $result = $this->searchOpenLibraryByTitle($title);
        }

        return $result;
    }

    private function queryApi(string $query): ?array
    {
        $params = ['q' => $query, 'maxResults' => 1];

        $apiKey = config('services.google_books.api_key');
        if ($apiKey) {
            $params['key'] = $apiKey;
        }

        $response = Http::timeout(self::TIMEOUT_SECS)
            ->get(self::BASE_URL, $params);

        if ($response->status() === 429) {
            Log::warning('GoogleBooksService: 429 Rate Limit / Quota Exceeded', ['query' => $query]);
            throw new \Exception('RATE_LIMIT_EXCEEDED');
        }

        if ($response->failed()) {
            Log::warning('GoogleBooksService: API request failed', [
                'status' => $response->status(),
                'query'  => $query,
            ]);
            return null;
        }

        $data = $response->json();

        if (empty($data['items'])) {
            return null;
        }

        return $this->normalize($data['items'][0]);
    }

    /**
     * Mengambil metadata buku dari Open Library API berdasarkan ISBN.
     */
    private function fetchFromOpenLibrary(string $cleanIsbn): ?array
    {
        try {
            $response = Http::timeout(self::TIMEOUT_SECS)
                ->get(self::OPEN_LIB_URL, [
                    'bibkeys' => "ISBN:{$cleanIsbn}",
                    'format'  => 'json',
                    'jscmd'   => 'data',
                ]);

            if (! $response->successful()) {
                return null;
            }

            $json = $response->json();
            $key = "ISBN:{$cleanIsbn}";
            if (empty($json[$key])) {
                return null;
            }

            $book = $json[$key];

            $authors = [];
            if (! empty($book['authors'])) {
                foreach ($book['authors'] as $a) {
                    if (! empty($a['name'])) {
                        $authors[] = $a['name'];
                    }
                }
            }

            $publishers = [];
            if (! empty($book['publishers'])) {
                foreach ($book['publishers'] as $p) {
                    if (! empty($p['name'])) {
                        $publishers[] = $p['name'];
                    }
                }
            }

            $categories = [];
            if (! empty($book['subjects'])) {
                foreach (array_slice($book['subjects'], 0, 3) as $s) {
                    if (! empty($s['name'])) {
                        $categories[] = $s['name'];
                    }
                }
            }

            $publishYear = null;
            if (! empty($book['publish_date'])) {
                if (preg_match('/\b(19\d\d|20\d\d)\b/', $book['publish_date'], $matches)) {
                    $publishYear = (int) $matches[1];
                }
            }

            $coverUrl = $book['cover']['large'] ?? $book['cover']['medium'] ?? $book['cover']['small'] ?? null;
            if ($coverUrl) {
                $coverUrl = str_replace('http://', 'https://', $coverUrl);
            }

            return [
                'isbn'         => $cleanIsbn,
                'title'        => $book['title'] ?? '',
                'author'       => implode(', ', $authors),
                'publisher'    => implode(', ', $publishers),
                'publish_year' => $publishYear,
                'synopsis'     => $book['notes'] ?? ($book['description'] ?? ''),
                'category'     => implode(', ', $categories),
                'cover_url'    => $coverUrl,
                'pages'        => $book['number_of_pages'] ?? null,
                'language'     => null,
            ];
        } catch (\Exception $e) {
            Log::warning('GoogleBooksService: Open Library fallback failed', ['isbn' => $cleanIsbn, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Pencarian fallback ke Open Library berdasarkan Judul jika Google Books gagal/limit.
     */
    private function searchOpenLibraryByTitle(string $title): ?array
    {
        try {
            $response = Http::timeout(self::TIMEOUT_SECS)
                ->get(self::OPEN_LIB_SEARCH, [
                    'title' => $title,
                    'limit' => 1,
                ]);

            if (! $response->successful()) {
                return null;
            }

            $docs = $response->json('docs');
            if (empty($docs[0])) {
                return null;
            }

            $doc = $docs[0];
            $isbn = $doc['isbn'][0] ?? '';
            $coverId = $doc['cover_i'] ?? null;
            $coverUrl = $coverId ? "https://covers.openlibrary.org/b/id/{$coverId}-L.jpg" : null;

            return [
                'isbn'         => $isbn,
                'title'        => $doc['title'] ?? $title,
                'author'       => implode(', ', $doc['author_name'] ?? []),
                'publisher'    => $doc['publisher'][0] ?? '',
                'publish_year' => $doc['first_publish_year'] ?? null,
                'synopsis'     => '',
                'category'     => implode(', ', array_slice($doc['subject'] ?? [], 0, 3)),
                'cover_url'    => $coverUrl,
                'pages'        => null,
                'language'     => $doc['language'][0] ?? null,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    private function normalize(array $item): array
    {
        $info = $item['volumeInfo'] ?? [];

        $isbn13 = null;
        $isbn10 = null;
        foreach ($info['industryIdentifiers'] ?? [] as $id) {
            if ($id['type'] === 'ISBN_13') {
                $isbn13 = $id['identifier'];
            }
            if ($id['type'] === 'ISBN_10') {
                $isbn10 = $id['identifier'];
            }
        }

        $thumbnail = $info['imageLinks']['thumbnail']
            ?? $info['imageLinks']['smallThumbnail']
            ?? null;
        if ($thumbnail) {
            $thumbnail = str_replace('http://', 'https://', $thumbnail);
        }

        return [
            'isbn'         => $isbn13 ?? $isbn10 ?? '',
            'title'        => $info['title'] ?? '',
            'author'       => implode(', ', $info['authors'] ?? []),
            'publisher'    => $info['publisher'] ?? '',
            'publish_year' => isset($info['publishedDate'])
                ? (int) substr($info['publishedDate'], 0, 4)
                : null,
            'synopsis'     => $info['description'] ?? '',
            'category'     => implode(', ', $info['categories'] ?? []),
            'cover_url'    => $thumbnail,
            'pages'        => $info['pageCount'] ?? null,
            'language'     => $info['language'] ?? null,
        ];
    }
}
