<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Catalogue';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make()->columnSpanFull()->tabs([

                Forms\Components\Tabs\Tab::make('Details')->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()->maxLength(255)->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', str($state)->slug())),

                    Forms\Components\TextInput::make('slug')
                        ->required()->unique(ignoreRecord: true)
                        ->helperText('Changing this breaks existing links — add a redirect if you do.'),

                    Forms\Components\Select::make('brand_id')
                        ->relationship('brand', 'name')->searchable()->preload(),

                    Forms\Components\Select::make('categories')
                        ->relationship('categories', 'name')
                        ->multiple()->searchable()->preload(),

                    Forms\Components\Select::make('attributeValues')
                        ->label('Attributes')
                        ->relationship('attributeValues', 'value')
                        ->multiple()->searchable()->preload(),

                    Forms\Components\RichEditor::make('short_description')->columnSpanFull(),
                    Forms\Components\RichEditor::make('description')->columnSpanFull(),
                    Forms\Components\Textarea::make('technical_specs')
                        ->label('Technical specs')
                        ->helperText('Export case pack, e.g. "1g x 20 envelope tea bags x 24 inner cartons per MC"')
                        ->columnSpanFull(),

                    Forms\Components\DateTimePicker::make('published_at')->label('Published'),
                    Forms\Components\TextInput::make('sort')->numeric()->default(0),
                ])->columns(2),

                Forms\Components\Tabs\Tab::make('Pack sizes')->schema([
                    Forms\Components\Repeater::make('variants')
                        ->relationship()
                        ->label('Pack sizes / item codes')
                        ->schema([
                            Forms\Components\TextInput::make('pack_size')->label('Pack size'),
                            Forms\Components\TextInput::make('sku')->label('Item Code'),
                            Forms\Components\Select::make('pack_format')->options([
                                'tea_bags'  => 'Tea bags',
                                'loose_tea' => 'Loose tea',
                                'pyramid'   => 'Pyramid bags',
                                'sachets'   => 'Sachets',
                                'sticks'    => 'Sticks',
                                'other'     => 'Other',
                            ]),
                            Forms\Components\TextInput::make('pack_quantity')->numeric()->label('Count'),
                            Forms\Components\TextInput::make('pack_weight_g')->numeric()->label('Weight (g)'),
                            Forms\Components\Textarea::make('technical_specs')->columnSpanFull(),

                            // Commerce fields — hidden until a price list exists.
                            Forms\Components\Fieldset::make('Pricing')
                                ->visible(fn () => config('regency.commerce_enabled'))
                                ->schema([
                                    Forms\Components\TextInput::make('price_cents')->numeric()->label('Price (cents)'),
                                    Forms\Components\TextInput::make('currency')->maxLength(3),
                                    Forms\Components\TextInput::make('stock_qty')->numeric(),
                                    Forms\Components\Toggle::make('is_purchasable'),
                                ]),
                        ])
                        ->columns(3)
                        ->orderColumn('sort')
                        ->collapsible()
                        ->itemLabel(fn (array $state) => $state['pack_size'] ?? $state['sku'] ?? 'Pack size'),
                ]),

                Forms\Components\Tabs\Tab::make('Images')->schema([
                    Forms\Components\FileUpload::make('primary_image_path')
                        ->label('Main image')->image()->directory('media/products')->imageEditor(),
                    Forms\Components\FileUpload::make('gallery')
                        ->label('Gallery')->image()->multiple()->reorderable()
                        ->directory('media/products')->columnSpanFull(),
                ]),

                Forms\Components\Tabs\Tab::make('SEO')->schema([
                    Forms\Components\TextInput::make('meta_title')->maxLength(70)
                        ->helperText('Leave blank to auto-generate: "{title} — {brand} | Regency Teas"'),
                    Forms\Components\Textarea::make('meta_description')->maxLength(160)->rows(3),
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
                Tables\Columns\ImageColumn::make('primary_image_path')->label('')->square(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('brand.name')->badge()->sortable(),
                Tables\Columns\TextColumn::make('variants_count')->counts('variants')->label('Packs'),
                Tables\Columns\TextColumn::make('categories.name')->badge()->limitList(2)->label('Categories'),
                Tables\Columns\IconColumn::make('published_at')->boolean()
                    ->getStateUsing(fn ($record) => $record->published_at !== null)->label('Live'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('brand')->relationship('brand', 'name'),
                Tables\Filters\SelectFilter::make('categories')->relationship('categories', 'name'),
                Tables\Filters\TernaryFilter::make('published_at')->label('Published')->nullable(),
                Tables\Filters\Filter::make('no_image')
                    ->label('Missing image')
                    ->query(fn ($q) => $q->whereNull('primary_image_path')),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
