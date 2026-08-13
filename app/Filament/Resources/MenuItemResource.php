<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Site';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Navigation';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('menu')->options([
                'main'   => 'Header',
                'footer' => 'Footer',
            ])->required()->default('main'),
            Forms\Components\TextInput::make('label')->required(),
            Forms\Components\TextInput::make('url')->required()
                ->helperText('Relative paths like /about are preferred over absolute URLs.'),
            Forms\Components\Select::make('parent_id')
                ->label('Parent item')
                ->options(fn (?MenuItem $record) => MenuItem::query()
                    ->when($record, fn ($q) => $q->whereKeyNot($record->id))
                    ->orderBy('label')
                    ->pluck('label', 'id'))
                ->searchable(),
            Forms\Components\Toggle::make('is_active')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                Tables\Columns\TextColumn::make('label')->searchable(),
                Tables\Columns\TextColumn::make('url')->color('gray'),
                Tables\Columns\TextColumn::make('menu')->badge(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('menu')->options(['main' => 'Header', 'footer' => 'Footer']),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit'   => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
