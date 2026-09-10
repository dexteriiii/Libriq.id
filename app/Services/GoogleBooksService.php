<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleBooksService
{
    private const BASE_URL     = 'https://www.googleapis.com/books/v1/volumes';
    private const CACHE_TTL    = 86400; // 24 jam
    private const TIMEOUT_SECS = 5;

    /**
     * Fetch metadata buku berdasarkan ISBN.
     * Mengembalikan array metadata jika ditemukan.
     * Throws \Exception dengan pesan 'RATE_LIMIT_EXCEEDED' jika API 429 / Kuota habis.
     */
    public function fetchByIsbn(string $isbn): ?array
    {
        $cacheKey = "google_books.isbn.{$isbn}";

        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            if ($cached !== null) {
                return $cached;
            }
        }

        $result = $this->queryApi("isbn:{$isbn}");

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
        return $this->queryApi($title);
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
