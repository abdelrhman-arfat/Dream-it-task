<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{

    protected $table = 'books';

    protected  $fillable = [
        'author_id',
        'title_en',
        'title_en',
        'description_en',
        'description_ar',
    ];

    public function author() :BelongsTo
    {
        return $this->belongsTo(User::class,'author_id');
    }

}
