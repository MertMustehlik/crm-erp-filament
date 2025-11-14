<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Müşteri Tipi')
                    ->getStateUsing(fn($record) => $record->type->label()),
                TextColumn::make('name')
                    ->label('Müşteri Ünvanı')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('E-Posta')
                    ->searchable(),
                PhoneColumn::make('phone')
                    ->label('Telefon')->displayFormat(PhoneInputNumberType::NATIONAL),
                TextColumn::make('assignedUser.name')
                    ->label('Sorumlu'),
                TextColumn::make('status.name')
                    ->label('Durum')
                    ->badge()
                    ->color(fn($record) => $record->status?->color ?? 'primary'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
