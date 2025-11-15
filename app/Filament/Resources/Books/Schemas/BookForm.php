<?php

namespace App\Filament\Resources\Books\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        app()->setLocale(session('locale', app()->getLocale()));
        return $schema
            ->components([
                TextInput::make('title_en')
                    ->required(),
                TextInput::make('title_ar')
                    ->required(),
                Textarea::make('description_en')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description_ar')
                    ->required()
                    ->columnSpanFull(),
                Select::make('author_id')
                    ->relationship('author', 'id')
                    ->options(function () {
                        $locale = session('locale', app()->getLocale());
                        return User::role('author')
                            ->get()
                            ->mapWithKeys(function ($user) use ($locale) {
                                $name = $locale === 'ar' ? $user->name_ar : $user->name_en;
                                $label = $name . ' (' . $user->email . ')';
                                return [$user->id => $label];
                            })
                            ->toArray();
                    })
                    ->required()

            ]);
    }
}
