<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Gate;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['password'])) {
            $data['password'] = $this->record->password;
        }

        return $data;
    }


    protected function afterSave(): void
    {
        $formData = $this->form->getState();
        if (isset($formData['role'])) {
            $this->record->syncRoles([$formData['role']]);
        }
    }
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->visible(fn() => Gate::allows('adminUpdate', User::class)),
            DeleteAction::make()->visible(fn() => Gate::allows('adminDelete', User::class)),
        ];
    }
}
