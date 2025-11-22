<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')->label('Ürün'),
                TextEntry::make('sku')
                    ->label('SKU'),
                TextEntry::make('price')
                    ->label('Fiyat')
                    ->money('TRY'),
                TextEntry::make('vat_percent')
                    ->label('KDV')
                    ->suffix('%')
                    ->numeric(),
                TextEntry::make('unit.name')
                    ->label('Birim')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime('d M Y, H:i:s')
                    ->placeholder('-'),
            ]);
    }
}
