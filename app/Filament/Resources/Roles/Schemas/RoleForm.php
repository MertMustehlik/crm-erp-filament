<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Spatie\Permission\Models\Permission;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\Model;

class RoleForm
{
    public static function configure(Schema $schema): Schema    
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Rol Adı')
                    ->columnSpan('full')
                    ->required()
                    ->maxLength(255),
                CheckboxList::make('permissions')
                    ->label('İzinler')
                    ->relationship('permissions', 'name', function ($query) {
                        return $query->orderBy('group_name', 'asc');
                    })
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => __("permissions.{$record->name}"))
                    ->columnSpan('full')
                    ->bulkToggleable()
                    ->searchable()
                    ->searchDebounce(300)
                    ->columns(6)
                    ->gridDirection('row'),
            ]);
    }
}
