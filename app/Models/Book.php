<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn', 'title', 'author', 'publisher', 'publish_year', 'synopsis',
        'category', 'cover_url', 'cover_path', 'rack_location',
        'total_stock', 'available_stock',
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * URL cover final: prioritaskan upload lokal (cover_path),
     * fallback ke cover_url dari Google Books API.
     * URL Google Books di-upgrade ke resolusi HD (fife parameter).
     */
    public function getCoverAttribute(): string
    {
        if ($this->cover_path) {
            if (str_starts_with($this->cover_path, 'http://') || str_starts_with($this->cover_path, 'https://')) {
                return $this->cover_path;
            }

            // Jika upload di Supabase Storage / S3
            if (config('filesystems.default') === 's3') {
                $endpoint = rtrim(env('AWS_ENDPOINT', ''), '/');
                $bucket = env('AWS_BUCKET', 'libriq');
                // Format URL publik Supabase Storage
                if (str_contains($endpoint, 'supabase.co')) {
                    $projectUrl = explode('/storage/v1/s3', $endpoint)[0];
                    return "{$projectUrl}/storage/v1/object/public/{$bucket}/{$this->cover_path}";
                }
                return Storage::disk('s3')->url($this->cover_path);
            }

            // Fallback disk lokal
            if (file_exists(public_path('storage/' . $this->cover_path))) {
                return asset('storage/' . $this->cover_path);
            }

            return Storage::disk('public')->url($this->cover_path);
        }

        if ($this->cover_url) {
            $url = $this->cover_url;

            // Upgrade Google Books thumbnail ke resolusi HD
            if (str_contains($url, 'books.google') || str_contains($url, 'googleapis.com')) {
                $url = str_replace('http://', 'https://', $url);
                $url = preg_replace('/&zoom=\d+/', '', $url);
                $url = preg_replace('/&edge=\w+/', '', $url);
                // Hapus fife lama jika ada, ganti ke HD
                $url = preg_replace('/&fife=[^&]+/', '', $url);
                $url .= '&fife=w800-h1200';
            }

            // Upgrade Open Library cover ke ukuran Large
            if (str_contains($url, 'covers.openlibrary.org')) {
                $url = preg_replace('/-[SML]\.jpg$/', '-L.jpg', $url);
            }

            return $url;
        }

        return asset('images/book-placeholder.png');
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->available_stock <= 0) {
            return 'unavailable'; // red
        }

        if ($this->available_stock <= 2) {
            return 'limited'; // amber
        }

        return 'available'; // emerald
    }
}
