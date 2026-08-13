<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make()->columnSpanFull()->tabs([

                Forms\Components\Tabs\Tab::make('Content')->schema([
                    Forms\Components\TextInput::make('title')->required(),
                    Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)
                        ->helperText('Changing this breaks links — add a redirect if you do.'),
                    Forms\Components\Textarea::make('excerpt')->rows(2)->columnSpanFull(),
                    Forms\Components\RichEditor::make('body')->columnSpanFull(),
                    Forms\Components\FileUpload::make('hero_image_path')->image()->disk('media')->directory('pages'),
                    Forms\Components\DateTimePicker::make('published_at')->label('Published'),
                ])->columns(2),

                Forms\Components\Tabs\Tab::make('Blocks')->schema([
                    Forms\Components\Repeater::make('blocks')
                        ->helperText('Used by the FAQs and Team templates.')
                        ->schema([
                            Forms\Components\TextInput::make('question')->label('Question / Name'),
                            Forms\Components\TextInput::make('role')->label('Role (team only)'),
                            Forms\Components\FileUpload::make('image')->image()->disk('media')->directory('pages'),
                            Forms\Components\RichEditor::make('answer')->label('Answer / Bio')->columnSpanFull(),
                        ])
                        ->columns(3)->collapsed()->columnSpanFull(),
                ]),

                Forms\Components\Tabs\Tab::make('SEO')->schema([
                    Forms\Components\TextInput::make('meta_title')->maxLength(70),
                    Forms\Components\Textarea::make('meta_description')->maxLength(160)->rows(3),
                    Forms\Components\FileUpload::make('og_image_path')->image()->disk('media')->directory('pages')
                        ->label('Social share image'),
                    Forms\Components\Toggle::make('is_indexable')->label('Allow search engines')->default(true),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('template')->badge(),
                Tables\Columns\IconColumn::make('published_at')->boolean()
                    ->getStateUsing(fn ($record) => $record->published_at !== null)->label('Live'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit'   => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
