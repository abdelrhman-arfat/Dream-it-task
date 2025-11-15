<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{

    public static function configure(Schema $schema): Schema
    {
        // Set the app locale based on session
        app()->setLocale(session('locale', app()->getLocale()));

        $locale = session('locale', app()->getLocale());

        return $schema
            ->components([
                // Name dynamic based on locale
                TextInput::make('name_en')
                    ->required(),
                TextInput::make('name_ar')
                    ->required(),

                TextInput::make('bio_en'),
                TextInput::make('bio_ar'),

                // Email always visible
                TextInput::make('email')
                    ->label(__('statics.email'))
                    ->email()
                    ->required(),

                // Password always visible
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => $state ? bcrypt($state) : null),
                Select::make('role')
                    ->options([
                        'admin' => __('statics.admin'),
                        'editor' => __('statics.editor'),
                        'author' => __('statics.author'),
                    ])
                    ->required()
            ]);
    }
}
