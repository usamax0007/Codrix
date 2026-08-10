<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Enums\UserRole;
use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\PermissionRegistrar;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => RoleResource::canDelete($this->getRecord()))
                ->after(fn () => app()[PermissionRegistrar::class]->forgetCachedPermissions()),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $protected = [
            UserRole::Admin->value,
            UserRole::User->value,
        ];

        if (in_array($this->getRecord()->name, $protected, true)) {
            $data['name'] = $this->getRecord()->name;
            $data['guard_name'] = $this->getRecord()->guard_name;
        }

        return $data;
    }

    protected function afterSave(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
