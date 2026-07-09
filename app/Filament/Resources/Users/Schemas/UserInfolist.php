<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                IconEntry::make('is_admin')
                    ->label('Administrator')
                    ->boolean(),
                TextEntry::make('email_verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                RepeatableEntry::make('orders')
                    ->schema([
                        TextEntry::make('order_number')
                            ->label('Order'),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('total')
                            ->money('PHP', divideBy: 100),
                        TextEntry::make('placed_at')
                            ->dateTime(),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }
}
