<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('order_number')
                    ->label('Order number'),
                TextEntry::make('user.name')
                    ->label('Customer'),
                TextEntry::make('user.email')
                    ->label('Email'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('total')
                    ->money('PHP', divideBy: 100),
                TextEntry::make('placed_at')
                    ->dateTime(),
                RepeatableEntry::make('items')
                    ->schema([
                        TextEntry::make('product_name')
                            ->label('Product'),
                        TextEntry::make('sku')
                            ->label('SKU'),
                        TextEntry::make('quantity')
                            ->numeric(),
                        TextEntry::make('unit_price')
                            ->money('PHP', divideBy: 100),
                        TextEntry::make('subtotal')
                            ->money('PHP', divideBy: 100),
                    ])
                    ->columns(5)
                    ->columnSpanFull(),
            ]);
    }
}
