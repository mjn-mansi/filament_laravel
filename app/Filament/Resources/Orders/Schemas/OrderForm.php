<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Customer;
use App\Models\Item;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;

class OrderForm
{

    protected static function updateAmount(callable $get, callable $set) {
        $items = $get('../../items') ?? [];

        $total =   0;
        foreach ($items as $item) {
            $total += $item['total'] ?? 0;
        }

        $set('../../amount', $total);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Customer Details')
                    ->description("Details of the customer")
                    ->schema([
                        Select::make('customer_id')
                        ->label('Customer')
                        ->options(Customer::query()->active()->pluck('name', 'id'))
                        ->searchable()
                        ->required()->columnSpan('full'),
                        Textarea::make('address')->columnSpan('full'),
                        TextInput::make('amount')->numeric()->required()->columnSpan('full'),
                    ])->columns(2),

                Section::make('Order Items')
                ->description("Details of the order items")
                ->schema([
                    Repeater::make('items')
                        ->schema([

                            Select::make('item_id')
                            ->label('Item')
                            ->options(Item::query()->active()->pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->required()
                            ->afterStateUpdated(function($state, callable $set, callable $get ) {
                                $item = Item::find($state);
                                if($item) {
                                    $set('price', $item->price);
                                    $set('total', $item->price * ($get('quantity') ?: 1 ));
                                }

                                self::updateAmount($get, $set);
                            }),
                            TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->live()
                            ->afterStateUpdated(function($state, callable $set, callable $get ){
                                $price = $get('price');
                                if($price) {
                                    $set('total', $price * $state);
                                }

                                self::updateAmount($get, $set);
                            }),
                            TextInput::make('price')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function($state, callable $set, callable $get ){
                                $quantity = ($get('quantity') ?: 1);
                                $set('total', $quantity * $state);

                                self::updateAmount($get, $set);

                            }),
                            TextInput::make('total')->disabled()->dehydrated()
                        ])
                        ->columns(2)->live()->reorderable(false)->addActionLabel('Add Item')->relationship(),

                ]),
            ]);
    }
}
