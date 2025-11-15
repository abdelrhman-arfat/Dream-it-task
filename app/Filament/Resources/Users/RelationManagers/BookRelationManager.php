<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Books\BookResource;
use App\Models\Book;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;

class BookRelationManager extends RelationManager
{
    protected static string $relationship = 'books';

    protected static ?string $relatedResource = BookResource::class;
    public function isReadOnly(): bool
    {
        return false;
    }
    public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->hasRole('author');
    }

    public static function getDefaultProperties(): array
    {
        return [
            'lazy' => false
        ];
    }



    public function table(Table $table): Table
    {

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
            ])
            ->headerActions([
                CreateAction::make()->visible(fn() => Gate::allows("adminCreate", Book::class)),
            ]);
    }


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
                        // Get only users with "author" role
                        return User::role('author')->get()->pluck(
                            $locale === 'ar' ? 'name_ar' : 'name_en',
                            'id'
                        )->toArray();
                    })->required()

            ]);
    }
}
