<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Models\Customer;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Fatura No')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->label('Müşteri'),
                TextColumn::make('grand_total')
                    ->label('Genel Toplam')
                    ->money('TRY')
                    ->sortable(),
                TextColumn::make('invoice_date')
                    ->label('Fatura Tarihi')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('customer_id')
                    ->label('Müşteri')
                    ->native(false)
                    ->searchable()
                    ->options(
                        Customer::query()
                            ->limit(20)
                            ->orderBy('id', 'desc')
                            ->get()
                            ->mapWithKeys(function ($customer) {
                                return [
                                    $customer->id => $customer->name,
                                ];
                            })
                            ->toArray()
                    )
                    ->getSearchResultsUsing(function (string $search): array {
                        return Customer::query()
                            ->where(function ($query) use ($search) {
                                $query->whereRaw('CONCAT(first_name, " ", last_name) LIKE ?', ["%{$search}%"])->orWhere('company_name', 'like', "%{$search}%");
                            })
                            ->limit(10)
                            ->get()
                            ->mapWithKeys(function ($customer) {
                                return [
                                    $customer->id => $customer->name,
                                ];
                            })
                            ->toArray();
                    }),
                DateRangeFilter::make('invoice_date')
                    ->label('Fatura Tarihi'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
