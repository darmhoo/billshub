<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AirtimeBundleResource\Pages;
use App\Filament\Resources\AirtimeBundleResource\RelationManagers;
use App\Models\AirtimeBundle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AirtimeBundleResource extends Resource
{
    protected static ?string $model = AirtimeBundle::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('account_type_id')
                    ->relationship(name: 'accountType', titleAttribute: 'name')
                    ->required(),

                Forms\Components\Select::make('network_id')
                    ->relationship(name: 'network', titleAttribute: 'name')

                    ->required()
                ,
                Forms\Components\Select::make('automate_id')
                    ->relationship(name: 'automation', titleAttribute: 'name'),
                Forms\Components\TextInput::make('discount')
                    ->maxLength(255)
                    ->default(null),
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
                Tables\Columns\TextColumn::make('accountType.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('network.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('automation.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plan_id')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
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
            ]);
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
            'index' => Pages\ListAirtimeBundles::route('/'),
            'create' => Pages\CreateAirtimeBundle::route('/create'),
            'edit' => Pages\EditAirtimeBundle::route('/{record}/edit'),
        ];
    }
}
