<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataBundleResource\Pages;
use App\Filament\Resources\DataBundleResource\RelationManagers;
use App\Models\DataBundle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataBundleResource extends Resource
{
    protected static ?string $model = DataBundle::class;
    protected static ?string $navigationGroup = 'Data';


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('account_type_id')
                    ->relationship(name: 'accountType', titleAttribute: 'name')
                    ->required(),

                Forms\Components\Select::make('network_id')
                    ->relationship(name: 'network', titleAttribute: 'name')

                    ->required()
                ,
                Forms\Components\Select::make('data_type_id')
                    ->relationship(name: 'dataType', titleAttribute: 'name')

                    ->required()
                ,
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->prefix('₦')
                    ->numeric()
                    ->default(0.00),
                Forms\Components\Select::make('automate_id')
                    ->relationship(name: 'automation', titleAttribute: 'name'),

                Forms\Components\TextInput::make('plan_id')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('accountType.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('dataType.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('network.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('automation.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->numeric()
                    ->prefix('₦')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                ,
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultPaginationPageOption(50);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDataBundles::route('/'),
            'create' => Pages\CreateDataBundle::route('/create'),
            'edit' => Pages\EditDataBundle::route('/{record}/edit'),
        ];
    }
}
