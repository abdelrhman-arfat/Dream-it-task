<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\App;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        // Set the app locale from session
        App::setLocale(session('locale', app()->getLocale()));

        return $schema
            ->components([
                // Name
                TextEntry::make('name_en')
                    ->label(fn() => __('statics.name'))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'en'),

                TextEntry::make('name_ar')
                    ->label(fn() => __('statics.name'))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'ar'),

                // Bio
                TextEntry::make('bio_en')
                    ->label(fn() => __('statics.bio'))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'en'),

                TextEntry::make('bio_ar')
                    ->label(fn() => __('statics.bio'))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'ar'),

                // Email (always visible)
                TextEntry::make('email')
                    ->label(fn() => __('statics.email')),

                // Created at
                TextEntry::make('created_at')
                    ->label(fn() => __('statics.created_at'))
                    ->dateTime(),

                // Updated at
                TextEntry::make('updated_at')
                    ->label(fn() => __('statics.updated_at'))
                    ->dateTime(),
            ]);
    }
}
