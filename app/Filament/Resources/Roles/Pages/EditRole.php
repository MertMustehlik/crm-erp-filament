<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditRole extends EditRecord
{

    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        if ($this->record->id === 1) {
            Notification::make()
                ->title('Super Admin rolü düzenlenemez')
                ->body('Tüm sisteme erişim yetkisi vardır.')
                ->icon('heroicon-o-x-circle')
                ->iconColor('danger')
                ->send();

            $this->redirect($this->getResource()::getUrl('index'));
        }
    }

    /*public function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->id === 1) {
            Notification::make()
                ->title('Super Admin rolü düzenlenemez')
                ->body('Tüm sisteme erişim yetkisi vardır.')
                ->icon('heroicon-o-x-circle')
                ->iconColor('danger')
                ->send();

            $this->halt();
        }

        return $data;
    }*/
}
