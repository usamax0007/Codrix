<?php

namespace App\Filament\Resources\Roles\Schemas;

use App\Enums\UserRole;
use App\Support\AppPermission;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        $systemRoles = [
            UserRole::Admin->value,
            UserRole::User->value,
        ];

        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Role name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->rule('not_in:super_admin')
                    ->disabled(fn (?string $operation, $record): bool => $operation === 'edit'
                        && $record
                        && in_array($record->name, $systemRoles, true))
                    ->dehydrated()
                    ->helperText('Use a slug like admin or user.'),
                TextInput::make('guard_name')
                    ->default('web')
                    ->required()
                    ->maxLength(255)
                    ->disabled(fn (?string $operation, $record): bool => $operation === 'edit'
                        && $record
                        && in_array($record->name, $systemRoles, true))
                    ->dehydrated(),
                CheckboxList::make('permissions')
                    ->label('Permissions')
                    ->relationship('permissions', 'name')
                    ->getOptionLabelFromRecordUsing(
                        fn (Permission $record): string => AppPermission::label($record->name)
                    )
                    ->searchable()
                    ->bulkToggleable()
                    ->columns(2)
                    ->columnSpanFull()
                    ->helperText('Checked permissions are allowed for this role.'),
            ]);
    }
}
