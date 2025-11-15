<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        // Set locale from session
        app()->setLocale(session('locale', app()->getLocale()));

        return $table
            ->columns([
                // Name
                TextColumn::make('name_en')
                    ->label(__('statics.name'))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'en')
                    ->searchable(),

                TextColumn::make('name_ar')
                    ->label(__('statics.name'))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'ar')
                    ->searchable(),

                // Bio
                TextColumn::make('bio_en')
                    ->label(__('statics.bio'))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'en'),

                TextColumn::make('bio_ar')
                    ->label(__('statics.bio'))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'ar'),
                // Email
                TextColumn::make('email')
                    ->label(__('statics.email'))
                    ->searchable(),

                // Created/Updated
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label(__('statics.created_at'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->label(__('statics.updated_at'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->visible(fn($record) => Gate::allows('adminUpdate', $record)),
                EditAction::make()
                    ->visible(fn($record) => Gate::allows('adminUpdate', $record)),
                DeleteAction::make()
                    ->visible(fn($record) => Gate::allows('adminDelete', $record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(fn() => Gate::allows('adminDelete', User::class)),
                ]),
            ]);
    }
}
