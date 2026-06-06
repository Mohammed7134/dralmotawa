<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WisdomResource\Pages;
use App\Models\Wisdom;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class WisdomResource extends Resource
{
    protected static ?string $model = Wisdom::class;
    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Textarea::make('text')
                ->required()
                ->columnSpanFull()
                ->rows(4),
            Textarea::make('search_text')
                ->required()
                ->columnSpanFull()
                ->rows(2)
                ->helperText('Used for search indexing. Usually same as text.'),
            Select::make('categories')
                ->multiple()
                ->relationship('categories', 'category_name')
                ->preload()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('text')->limit(80)->searchable('search_text'),
                TextColumn::make('likes')->sortable(),
                TextColumn::make('categories.category_name')->badge()->wrap(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('categories')
                    ->relationship('categories', 'category_name')
                    ->multiple(),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWisdoms::route('/'),
            'create' => Pages\CreateWisdom::route('/create'),
            'edit'   => Pages\EditWisdom::route('/{record}/edit'),
        ];
    }
    public static function afterCreate(Wisdom $record): void
    {
        Cache::forget('gemini_wisdoms_context');
    }
    public static function afterSave(Wisdom $record): void
    {
        Cache::forget('gemini_wisdoms_context');
    }
}
