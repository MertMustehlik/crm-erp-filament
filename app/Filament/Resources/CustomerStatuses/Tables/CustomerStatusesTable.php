<?php

namespace App\Filament\Resources\CustomerStatuses\Tables;

use App\Models\CustomerStatus;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Actions\ReplicateAction;

class CustomerStatusesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('color')
                    ->label('Durum')
                    ->badge()
                    ->color(fn(string $state): string => $state)
                    ->formatStateUsing(fn($record): string => $record->name)
                    ->searchable(['name']),
                TextColumn::make('customers')
                    ->label('Müşteri Sayısı')
                    ->badge()
                    ->color('secondary')
                    ->state(fn($record): string => $record->customers->count() . ' Müşteri'),

                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime('d M Y, H:i:s'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->before(function (DeleteAction $action, CustomerStatus $record) {
                    if ($record->customers->count() > 0) {
                        Notification::make()
                            ->title('Bu duruma bağlı müşteriler var, silinemez!')
                            ->danger()
                            ->send();

                        $action->halt();
                    }
                }),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
