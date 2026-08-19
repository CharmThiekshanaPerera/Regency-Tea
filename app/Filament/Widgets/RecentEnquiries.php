<?php

namespace App\Filament\Widgets;

use App\Models\Enquiry;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentEnquiries extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest enquiries';

    public function table(Table $table): Table
    {
        return $table
            ->query(Enquiry::query()->latest()->limit(8))
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime('j M H:i'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('company'),
                Tables\Columns\TextColumn::make('subject')->limit(45),
                Tables\Columns\IconColumn::make('handled_at')->boolean()
                    ->getStateUsing(fn ($record) => $record->handled_at !== null)->label('Handled'),
            ])
            ->paginated(false);
    }
}
