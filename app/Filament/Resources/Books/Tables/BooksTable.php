<?php

namespace App\Filament\Resources\Books\Tables;

use App\Models\Book;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;

class BooksTable
{


    public static function configure(Table $table): Table
    {
        app()->setLocale(session('locale', app()->getLocale()));

        return $table
            ->columns([
                TextColumn::make('title_en')
                    ->label(__("statics.title"))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'en')
                    ->searchable(),
                TextColumn::make('title_ar')
                    ->label(__("statics.title"))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'ar')
                    ->searchable(),
                TextColumn::make('description_en')
                    ->label(__("statics.description"))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'en')
                    ->searchable(),
                TextColumn::make('description_ar')
                    ->label(__("statics.description"))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'ar')
                    ->searchable(),
                TextColumn::make('author.name_en')
                    ->label(__('statics.author'))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'en')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author.name_ar')
                    ->label(__('statics.author'))
                    ->visible(fn() => session('locale', app()->getLocale()) === 'ar')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function (Book $record) {
                        $userLanguage = session("locale", app()->getLocale());
                        return $userLanguage === 'ar' ? $record->author->name_ar : $record->author->name_en;
                    })
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label(__("statics.created_at"))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->label(__("statics.updated_at"))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(function ($record) {
                        return Gate::allows("adminUpdate", $record);
                    }),
                DeleteAction::make()->visible(function ($record) {
                    return Gate::allows("adminDelete", $record);
                }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(fn() => Gate::allows("adminDelete", Book::class)),
                ]),
            ]);
    }
}
