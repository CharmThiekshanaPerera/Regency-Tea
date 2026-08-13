<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Catalogue';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Categories';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->live(onBlur: true)
                ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', str($state)->slug())),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\Select::make('product_group_id')
                ->label('Range group')->relationship('group', 'name')->searchable()->preload()
                ->helperText('Groups the 72 legacy categories into navigable sections.'),
            Forms\Components\Toggle::make('is_visible')->label('Visible')->default(true),
            Forms\Components\FileUpload::make('image_path')->image()->disk('media')->directory('categories'),
            Forms\Components\RichEditor::make('description')->columnSpanFull(),
            Forms\Components\TextInput::make('meta_title')->maxLength(70),
            Forms\Components\Textarea::make('meta_description')->maxLength(160),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('group.name')->badge()->label('Group')->sortable(),
                Tables\Columns\TextColumn::make('products_count')->counts('products')->label('Products'),
                Tables\Columns\IconColumn::make('is_visible')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_group_id')->relationship('group', 'name')->label('Group'),
                Tables\Filters\TernaryFilter::make('is_visible'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
