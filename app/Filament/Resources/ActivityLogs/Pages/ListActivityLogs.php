<?php

namespace App\Filament\Resources\ActivityLogs\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public function getTabs(): array
    {
        return [
            'Tümü' => Tab::make(),
            'Oluşturuldu' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('event', 'created')),
            'Güncellendi' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('event', 'updated')),
            'Silindi' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('event', 'deleted')),
        ];
    }
}
