<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RedirectResource\Pages;
use App\Models\Redirect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * The 666 legacy WordPress URLs. Hit counts make dead inbound links visible.
 */
class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-circle';

    protected static ?string $navigationGroup = 'Site';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('from_path')->required()->unique(ignoreRecord: true)
                ->helperText('Old path, no domain. e.g. /2023/10/08/some-post'),
            Forms\Components\TextInput::make('to_path')->required()
                ->helperText('New path. e.g. /media/some-post'),
            Forms\Components\Select::make('status_code')->options([
                301 => '301 — Moved permanently',
                302 => '302 — Temporary',
                410 => '410 — Gone (no replacement)',
            ])->default(301)->required(),
            Forms\Components\TextInput::make('note'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('hits', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('from_path')->searchable()->limit(50),
                Tables\Columns\TextColumn::make('to_path')->searchable()->limit(50)->color('gray'),
                Tables\Columns\TextColumn::make('status_code')->badge()
                    ->color(fn ($state) => $state === 410 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('hits')->sortable()->label('Hits'),
                Tables\Columns\TextColumn::make('last_hit_at')->dateTime('j M Y')->label('Last hit'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_code')
                    ->options([301 => '301', 302 => '302', 410 => '410']),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRedirects::route('/'),
            'create' => Pages\CreateRedirect::route('/create'),
            'edit'   => Pages\EditRedirect::route('/{record}/edit'),
        ];
    }
}
