<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use App\Models\User;
use App\Models\Customer;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Illuminate\Support\HtmlString;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#'),
                TextColumn::make('causer.name')
                    ->label('Kullanıcı')
                    ->default('-'),
                TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        return class_basename($state);
                    })
                    ->badge()
                    ->colors([
                        'info',
                    ]),
                TextColumn::make('event')
                    ->label('Olay')
                    ->badge()
                    ->colors([
                        'success' => 'created',
                        'warning' => 'updated',
                        'danger'  => 'deleted',
                        'info'    => 'viewed',
                    ]),
                TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d M Y H:i:s'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters(filters: [
                SelectFilter::make('causer_id')
                    ->label('Kullanıcı')
                    ->native(false)
                    ->searchable()
                    ->options(
                        User::query()
                            ->orderBy('id', 'desc')
                            ->get()
                            ->mapWithKeys(function ($user) {
                                return [
                                    $user->id => $user->name,
                                ];
                            })
                            ->toArray()
                    ),
                DateRangeFilter::make('created_at')
                    ->label('Tarih'),
                ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                ViewAction::make()
                    ->label('Aktivite Detay')
                    ->modalHeading('Aktivite Detayı')
                    ->modalWidth('4xl')
                    ->modalContent(function ($record) {
                        $props = $record->properties;
                        $old = $props['old'] ?? null;
                        $new = $props['attributes'] ?? null;

                        $html = '<div style="font-family: Inter, sans-serif; font-size:14px;">';

                        // Eski Değerler
                        if ($old) {
                            $html .= '<h3 style="margin-bottom:6px;">🟡 Eski Değerler</h3>';
                            $html .= '<table style="width:100%; border-collapse: collapse; margin-bottom:16px;">';
                            foreach ($old as $key => $value) {
                                $html .= "<tr>
                            <td style='padding:6px; font-weight:600; border-bottom:1px solid #ccc;'>{$key}</td>
                            <td style='padding:6px; border-bottom:1px solid #ccc;'>{$value}</td>
                          </tr>";
                            }
                            $html .= '</table>';
                        }

                        // Yeni Değerler
                        if ($new) {
                            $html .= '<h3 style="margin-bottom:6px;">🟢 Yeni Değerler</h3>';
                            $html .= '<table style="width:100%; border-collapse: collapse;">';
                            foreach ($new as $key => $value) {
                                $changed = $old[$key] ?? null;
                                $rowStyle = ($changed != $value) ? 'background:#fff8e6;' : '';
                                $html .= "<tr style='{$rowStyle}'>
                            <td style='padding:6px; font-weight:600; border-bottom:1px solid #ccc;'>{$key}</td>
                            <td style='padding:6px; border-bottom:1px solid #ccc;'>{$value}</td>
                          </tr>";
                            }
                            $html .= '</table>';
                        }

                        $html .= '</div>';

                        return new HtmlString($html);
                    })
            ])
            ->toolbarActions([
                //
            ]);
    }
}
