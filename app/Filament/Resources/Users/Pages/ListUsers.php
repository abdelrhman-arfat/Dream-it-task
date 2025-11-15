<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->label(__('statics.tabs.all'))
                ->icon('heroicon-o-users'),

            'admin' => Tab::make()
                ->label(__('statics.tabs.admin'))
                ->icon('heroicon-o-shield-check')
                ->modifyQueryUsing(fn(Builder $query) => $query->role('admin')),

            'editor' => Tab::make()
                ->label(__('statics.tabs.editor'))
                ->icon('heroicon-o-pencil-square')
                ->modifyQueryUsing(fn(Builder $query) => $query->role('editor')),

            'author' => Tab::make()
                ->label(__('statics.tabs.author'))
                ->icon('heroicon-o-document-text')
                ->modifyQueryUsing(fn(Builder $query) => $query->role('author')),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'all';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->model(User::class)
                ->after(function ($data) {
                    
                    
                })
                ,
        ];
    }
}
