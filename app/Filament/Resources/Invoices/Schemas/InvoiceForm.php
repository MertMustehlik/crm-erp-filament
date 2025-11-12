<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Enums\VatPercent;
use App\Helpers\MoneyHelper;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\TextEntry;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                TextInput::make('number')
                    ->label('Fatura No')
                    ->columnSpan(4)
                    ->default(fn() => Invoice::generateNumber())
                    ->required(),
                Select::make('customer_id')
                    ->label('Müşteri Seçimi')
                    ->columnSpan(4)
                    ->searchable()
                    ->required()
                    ->validationMessages([
                        'required' => 'Müşteri seçimi zorunlu',
                    ])
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
                    })
                    ->getOptionLabelUsing(fn($value): ?string => Customer::find($value)?->name),
                DatePicker::make('invoice_date')
                    ->label('Fatura Tarihi')
                    ->columnSpan(4)
                    ->native(false)
                    ->displayFormat('d M Y')
                    ->default(now())
                    ->required()
                    ->validationMessages([
                        'required' => 'Fatura tarihi zorunlu',
                    ]),
                Section::make('Fatura Kalemleri')
                    ->columnSpan('full')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->hiddenLabel()
                            ->minItems(1)
                            ->addActionLabel('Ürün Ekle')
                            ->columnSpan('full')
                            ->columns(6)
                            ->schema([
                                Select::make('product_id')
                                    ->label('Ürün Seçimi')
                                    ->columnSpan(2)
                                    ->searchable()
                                    ->required()
                                    ->validationMessages([
                                        'required' => 'Ürün seçimi zorunlu',
                                    ])
                                    ->options(Product::query()->limit(10)->orderBy('id', 'desc')->get()->pluck('name', 'id'))
                                    ->getSearchResultsUsing(function (string $search): array {
                                        return Product::query()->where('name', 'like', "%{$search}%")
                                            ->limit(10)
                                            ->pluck('name', 'id')
                                            ->all();
                                    })
                                    ->getOptionLabelUsing(fn($value): ?string => Product::find($value)?->name)
                                    ->reactive()
                                    ->debounce(1000)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if (!$state) return;

                                        $product = Product::find($state);
                                        if (!$product) return;

                                        $set('price', MoneyHelper::format($product->price));
                                        $set('vat_percent', $product->vat_percent);

                                        self::calculateItem($set, $get);
                                    }),
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->label('Adet')
                                    ->default(1)
                                    ->required()
                                    ->minValue(1)
                                    ->validationMessages([
                                        'required' => 'Adet zorunlu',
                                        'min' => 'Adet en az 1 olmalıdır',
                                    ])
                                    ->reactive()
                                    ->debounce(1000)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        self::calculateItem($set, $get);
                                    }),

                                TextInput::make('price')
                                    ->label('Birim Fiyat')
                                    ->placeholder('0,00')
                                    ->reactive()
                                    ->debounce(1000)
                                    ->prefix('₺')
                                    ->required()
                                    ->minValue(0)
                                    ->validationMessages([
                                        'required' => 'Birim fiyat zorunlu',
                                    ])
                                    ->afterStateHydrated(fn($set, $get) => $set('price', $get('price') ? number_format($get('price'), 2, ',', '.') : null))
                                    ->dehydrateStateUsing(fn($state) => floatval(str_replace(['.', ','], ['', '.'], $state)))
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        self::calculateItem($set, $get);
                                    }),
                                Select::make('vat_percent')
                                    ->label('KDV (%)')
                                    ->options(VatPercent::options())
                                    ->native(false)
                                    ->reactive()
                                    ->debounce(1000)
                                    ->required()
                                    ->validationMessages([
                                        'required' => 'KDV oranı zorunlu',
                                    ])
                                    ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                        self::calculateItem($set, $get);
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        self::calculateItem($set, $get);
                                    }),

                                TextInput::make('line_total')
                                    ->label('Toplam')
                                    ->prefix('₺')
                                    ->placeholder('0,00')
                                    ->readOnly()
                                    ->dehydrateStateUsing(fn($state) => floatval(str_replace(['.', ','], ['', '.'], $state))),
                            ])
                            ->deletable(true)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                [$invoiceTotal, $invoiceVatAmount, $invoiceGrandTotal] = self::calculateInvoiceTotals($get('items') ?? []);

                                $set('invoice_total', $invoiceTotal);
                                $set('invoice_vat_amount', $invoiceVatAmount);
                                $set('invoice_grand_total', $invoiceGrandTotal);
                            }),
                    ]),
                Section::make('')
                    ->columnSpan("full")
                    ->schema([
                        TextInput::make('invoice_total')
                            ->label('Toplam')
                            ->default('₺0,00')
                            ->reactive()
                            ->readOnly()
                            ->dehydrated(false),
                        TextInput::make('invoice_vat_amount')
                            ->label('Vergi Toplamı')
                            ->default('₺0,00')
                            ->reactive()
                            ->readOnly()
                            ->dehydrated(false),
                        TextInput::make('invoice_grand_total')
                            ->label('Genel Toplam')
                            ->default('₺0,00')
                            ->reactive()
                            ->dehydrated(false),
                    ]),
            ]);
    }

    protected static function calculateItem(callable $set, callable $get): void
    {
        $price    = MoneyHelper::parse($get('price') ?? 0);
        $quantity = $get('quantity') ?? 0;
        $vatPercent  = $get('vat_percent') ?? 0;

        $subtotal    = $price * $quantity;
        $vatAmount   = $subtotal * ($vatPercent / 100);
        $total       = $subtotal + $vatAmount;

        $set('vat_amount', MoneyHelper::format($vatAmount));
        $set('line_total', MoneyHelper::format($total));


        [$invoiceTotal, $invoiceVatAmount, $invoiceGrandTotal] = self::calculateInvoiceTotals($get('../../items') ?? []);

        $set('../../invoice_total', $invoiceTotal);
        $set('../../invoice_vat_amount', $invoiceVatAmount);
        $set('../../invoice_grand_total', $invoiceGrandTotal);
    }

    protected static function calculateInvoiceTotals($items): array
    {
        $subtotal = collect($items)->sum(function ($data) {
            $price    = isset($data['price']) ? MoneyHelper::parse($data['price']) : 0;
            $quantity = isset($data['quantity']) ? (float) $data['quantity'] : 0;
            return $price * $quantity;
        });

        $vatAmount = collect($items)->sum(function ($data) {
            return isset($data['vat_amount']) && $data['vat_amount'] ? MoneyHelper::parse($data['vat_amount']) : 0;
        });

        $grandTotal = $subtotal + $vatAmount;


        return [
            MoneyHelper::format($subtotal, true),
            MoneyHelper::format($vatAmount, true),
            MoneyHelper::format($grandTotal, true),
        ];
    }
}
