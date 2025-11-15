<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\App;

class BookInfolist
{
    public static function configure(Schema $schema): Schema
    {
        App::setLocale(session('locale', app()->getLocale()));
        return $schema
            ->components([
                // Title
                TextEntry::make('title_en')
                    ->label(fn() => __('statics.title'))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'en'),

                TextEntry::make('title_ar')
                    ->label(fn() => __('statics.title'))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'ar'),

                // Description
                TextEntry::make('description_en')
                    ->label(__("statics.description"))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'en'),

                TextEntry::make('description_ar')
                    ->label(__("statics.description"))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'ar'),

                // Author
                TextEntry::make('author.name_en')
                    ->label(__('statics.author'))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'en'),

                TextEntry::make('author.name_ar')
                    ->label(__('statics.author'))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'ar'),
                // Created at
                TextEntry::make('created_at')
                    ->label(__("statics.created_at"))
                    ->dateTime(),

                // Updated at
                TextEntry::make('updated_at')
                    ->label(__("statics.updated_at"))
                    ->dateTime(),
            ]);
    }
}
