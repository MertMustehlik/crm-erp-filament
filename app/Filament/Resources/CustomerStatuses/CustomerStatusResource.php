<?php

namespace App\Filament\Resources\CustomerStatuses;

use App\Filament\Resources\CustomerStatuses\Pages\CreateCustomerStatus;
use App\Filament\Resources\CustomerStatuses\Pages\EditCustomerStatus;
use App\Filament\Resources\CustomerStatuses\Pages\ListCustomerStatuses;
use App\Filament\Resources\CustomerStatuses\Schemas\CustomerStatusForm;
use App\Filament\Resources\CustomerStatuses\Tables\CustomerStatusesTable;
use App\Models\CustomerStatus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class CustomerStatusResource extends Resource
{
    protected static ?string $model = CustomerStatus::class;
    protected static string | UnitEnum | null $navigationGroup = 'Müşteri Yönetimi';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationLabel = 'Müşteri Durumları';
    protected static ?string $modelLabel = 'Müşteri Durumu';

    public static function form(Schema $schema): Schema
    {
        return CustomerStatusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerStatusesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomerStatuses::route('/'),
            'create' => CreateCustomerStatus::route('/create'),
            'edit' => EditCustomerStatus::route('/{record}/edit'),
        ];
    }
}
