<?php

namespace App\Filament\Resources\Permissions\Pages;

use App\Filament\Resources\Permissions\PermissionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\PermissionRegistrar;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->after(fn () => app()[PermissionRegistrar::class]->forgetCachedPermissions()),
        ];
    }

    protected function afterSave(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
