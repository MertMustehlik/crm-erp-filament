<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Enums\CustomerType;
use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Customers\Widgets\TypeStatsWidget;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TypeStatsWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'Tümü' => Tab::make(),
            'Bireysel' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', CustomerType::INDIVIDUAL->value)),
            'Kurumsal' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', CustomerType::CORPORATE->value)),
        ];
    }
}
