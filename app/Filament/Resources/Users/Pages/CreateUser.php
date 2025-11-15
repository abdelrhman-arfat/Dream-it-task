<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Mail\WelcomeUserMail;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;


    protected function beforeCreate(): void
    {
        $formData = $this->form->getState();
    }

    protected function afterCreate(): void
    {
        $formData = $this->form->getState();
        if (isset($formData['role'])) {
            $this->record->assignRole($formData['role']);
            if ($formData["role"] == "author") {
                Mail::to($this->record->email)->queue(new WelcomeUserMail($this->record));
            }
        }
    }

    protected function getFormSchema(): array
    {
        $schema = parent::getFormSchema();

        if ($this instanceof CreateUser) {
            foreach ($schema as $field) {
                if ($field instanceof TextInput && $field->getName() === 'password') {
                    $field->required();
                }
            }
        }

        return $schema;
    }
}
