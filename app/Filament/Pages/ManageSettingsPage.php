<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \Filament\Schemas\Schema $form
 */
abstract class ManageSettingsPage extends Page
{
    protected string $view = 'filament.pages.manage-settings';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    /**
     * @return class-string<Model>
     */
    abstract protected function settingsModel(): string;

    abstract protected function savedNotificationTitle(): string;

    public function mount(): void
    {
        $this->form->fill($this->formFillState());
    }

    /**
     * @return array<string, mixed>
     */
    protected function formFillState(): array
    {
        $model = $this->settingsModel();

        return $this->getRecord()?->attributesToArray() ?? $model::defaults();
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $model = $this->settingsModel();
        $record = $this->getRecord() ?? new $model;

        $record->fill($data);
        $record->save();

        if ($record->wasRecentlyCreated) {
            $this->form->record($record)->saveRelationships();
        }

        Notification::make()
            ->success()
            ->title($this->savedNotificationTitle())
            ->send();
    }

    public function getRecord(): ?Model
    {
        return $this->settingsModel()::query()->first();
    }
}
