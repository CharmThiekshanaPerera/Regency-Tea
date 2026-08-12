<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Media Centre';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required()->live(onBlur: true)
                ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', str($state)->slug())),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\Select::make('categories')->relationship('categories', 'name')
                ->multiple()->preload(),
            Forms\Components\DateTimePicker::make('published_at')->label('Publish date'),
            Forms\Components\FileUpload::make('featured_image_path')->image()
                ->directory('media/posts')->columnSpanFull(),
            Forms\Components\Textarea::make('excerpt')->rows(3)->columnSpanFull(),
            Forms\Components\RichEditor::make('body')->columnSpanFull(),
            Forms\Components\TextInput::make('meta_title')->maxLength(70),
            Forms\Components\Textarea::make('meta_description')->maxLength(160),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image_path')->label(''),
                Tables\Columns\TextColumn::make('title')->searchable()->limit(60)->wrap(),
                Tables\Columns\TextColumn::make('categories.name')->badge(),
                Tables\Columns\TextColumn::make('published_at')->date('j M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categories')->relationship('categories', 'name'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
