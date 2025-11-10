<?php

namespace App\Filament\Resources\Customers\Widgets;

use App\Enums\CustomerType;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;
use App\Models\Customer;
use Filament\Support\Icons\Heroicon;

class TypeStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Toplam', Customer::count())
                ->description('Toplam müşteri sayısı')
                ->descriptionIcon(Heroicon::ClipboardDocumentList, IconPosition::Before)
                ->color('primary'),
            Stat::make('Bireysel', Customer::where('type', CustomerType::INDIVIDUAL->value)->count())
                ->description('Toplam bireysel müşteri sayısı')
                ->descriptionIcon(Heroicon::ClipboardDocumentList, IconPosition::Before)
                ->color('primary'),
            Stat::make('Kurumsal', Customer::where('type', CustomerType::CORPORATE->value)->count())
                ->description('Toplam kurumsal müşteri sayısı')
                ->descriptionIcon(Heroicon::ClipboardDocumentList, IconPosition::Before)
                ->color('primary'),
        ];
    }
}
