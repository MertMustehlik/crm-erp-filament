<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Spatie\Permission\Models\Role;
use Filament\Actions\Action;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Rol')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime('d M Y, H:i:s')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->visible(fn(Role $record) => $record->name !== 'Super Admin'),
                DeleteAction::make()->visible(fn(Role $record) => $record->name !== 'Super Admin'),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
