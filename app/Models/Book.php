<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;
    protected $table = 'books';

    protected  $fillable = [
        'author_id',
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
    ];



    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }
}
