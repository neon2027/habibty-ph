<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('user.name')
                    ->label('Customer')
                    ->disabled()
                    ->dehydrated(false),
                Select::make('status')
                    ->options([
                        Order::STATUS_PAID => 'Paid',
                        Order::STATUS_PREPARING => 'Preparing',
                        Order::STATUS_COMPLETED => 'Completed',
                        Order::STATUS_CANCELLED => 'Cancelled',
                    ])
                    ->required(),
            ]);
    }
}
