<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnquiryResource\Pages;
use App\Models\Enquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EnquiryResource extends Resource
{
    protected static ?string $model = Enquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $navigationGroup = 'Site';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Enquiries';

    public static function getNavigationBadge(): ?string
    {
        $new = static::getModel()::whereNull('handled_at')->count();

        return $new > 0 ? (string) $new : null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->disabled(),
            Forms\Components\TextInput::make('email')->disabled(),
            Forms\Components\TextInput::make('company')->disabled(),
            Forms\Components\TextInput::make('country')->disabled(),
            Forms\Components\TextInput::make('subject')->disabled()->columnSpanFull(),
            Forms\Components\Textarea::make('message')->disabled()->rows(8)->columnSpanFull(),
            Forms\Components\DateTimePicker::make('handled_at')->label('Marked handled'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime('j M Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('company')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('country')->badge()->toggleable(),
                Tables\Columns\TextColumn::make('subject')->limit(40),
                Tables\Columns\IconColumn::make('handled_at')->boolean()
                    ->getStateUsing(fn ($record) => $record->handled_at !== null)->label('Handled'),
            ])
            ->filters([
                Tables\Filters\Filter::make('unhandled')
                    ->label('Needs a reply')
                    ->query(fn ($q) => $q->whereNull('handled_at')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('handled')
                    ->label('Mark handled')->icon('heroicon-o-check')
                    ->visible(fn ($record) => $record->handled_at === null)
                    ->action(fn ($record) => $record->update(['handled_at' => now()])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEnquiries::route('/'),
            'create' => Pages\CreateEnquiry::route('/create'),
            'edit'   => Pages\EditEnquiry::route('/{record}/edit'),
        ];
    }
}
