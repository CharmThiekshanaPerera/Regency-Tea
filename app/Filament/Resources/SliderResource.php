<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/** Replaces Slider Revolution — 5 legacy sliders, 36 slides. */
class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Site';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Homepage slider';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)
                ->helperText('The homepage uses the slug "home-hero".'),
            Forms\Components\Toggle::make('is_active')->default(true),

            Forms\Components\Repeater::make('slides')
                ->relationship()
                ->schema([
                    Forms\Components\FileUpload::make('image_path')->image()
                        ->directory('media/slides')->columnSpanFull(),
                    Forms\Components\TextInput::make('subheading'),
                    Forms\Components\TextInput::make('heading'),
                    Forms\Components\Textarea::make('body')->columnSpanFull(),
                    Forms\Components\TextInput::make('cta_label')->label('Button text'),
                    Forms\Components\TextInput::make('cta_url')->label('Button link'),
                    Forms\Components\Toggle::make('is_active')->default(true),
                ])
                ->columns(2)->orderColumn('sort')->collapsed()
                ->itemLabel(fn (array $state) => $state['heading'] ?? 'Slide')
                ->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('slug')->badge()->color('gray'),
            Tables\Columns\TextColumn::make('slides_count')->counts('slides')->label('Slides'),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
        ])->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit'   => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
