<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Customer;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Order Details')
                    ->description("Details of the order")
                    ->schema([
                        Select::make('customer_id')
                        ->label('Customer')
                        ->options(Customer::query()->active()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                        Textarea::make('description')
                    ])->columns(2),

                Section::make('Order Items')
                ->schema([

                ])

              
                TextInput::make('item_id')
                    ->required()
                    ->numeric(),
                TextInput::make('quantity')
                    ->required(),
                TextInput::make('price')
                    ->required(),
                TextInput::make('total')
                    ->required(),
            ]);
    }
}
