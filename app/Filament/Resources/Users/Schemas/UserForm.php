<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\User;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Ad Soyad')
                    ->required(),
                TextInput::make('email')
                    ->label('E-Posta')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->label('Parola')
                    ->helperText(
                        fn(string $context) =>
                        $context === 'edit'
                            ? 'Boş bırakırsanız mevcut parola korunur.'
                            : null
                    )
                    ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn($context) => $context === 'create'),
                Select::make('roles')
                    ->label('Roller')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->required(),
            ]);
    }
}
