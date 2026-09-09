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
     */
    public function getCoverAttribute(): string
    {
        if ($this->cover_path) {
            return Storage::disk('public')->url($this->cover_path);
        }

        return $this->cover_url ?: asset('images/book-placeholder.png');
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
