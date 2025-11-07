<?php

namespace App\Filament\Resources\CustomerStatuses\Schemas;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\HtmlString;

class CustomerStatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Durum Adı')
                    ->columnSpan('full')
                    ->required()
                    ->maxLength(255),
                Radio::make('color')
                    ->label('Renk')
                    ->columnSpan('full')
                    ->inline()
                    ->required()
                    ->validationMessages([
                        'required' => 'Lütfen bir renk seçin',
                    ])
                    ->options([
                        'primary' => new HtmlString('<span class="fi-color fi-color-primary fi-text-color-700 dark:fi-text-color-400 fi-badge fi-size-sm">Örnek Durum</span>'),
                        'secondary' => new HtmlString('<span class="fi-color fi-color-secondary fi-text-color-700 dark:fi-text-color-400 fi-badge fi-size-sm">Örnek Durum</span>'),
                        'success' => new HtmlString('<span class="fi-color fi-color-success fi-text-color-700 dark:fi-text-color-400 fi-badge fi-size-sm">Örnek Durum</span>'),
                        'warning' => new HtmlString('<span class="fi-color fi-color-warning fi-text-color-700 dark:fi-text-color-400 fi-badge fi-size-sm">Örnek Durum</span>'),
                        'danger'  => new HtmlString('<span class="fi-color fi-color-danger fi-text-color-700 dark:fi-text-color-400 fi-badge fi-size-sm">Örnek Durum</span>'),
                    ])
            ]);
    }
}
