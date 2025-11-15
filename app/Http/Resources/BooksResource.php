<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BooksResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = session('locale', $request->header("accept-language", app()->getLocale())); // default 'en'

        return [
            'id' => $this->id,
            'title' => $lang === 'ar' ? $this->title_ar : $this->title_en,
            'description' => $lang === 'ar' ? $this->description_ar : $this->description_en,
            'author' => $this->author ? [
                'id' => $this->author->id,
                'name' => $lang === 'ar' ? $this->author->name_ar : $this->author->name_en,
                'bio' => $lang === 'ar' ? $this->author->bio_ar : $this->author->bio_en,
            ] : null, // returns null if author not loaded
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
