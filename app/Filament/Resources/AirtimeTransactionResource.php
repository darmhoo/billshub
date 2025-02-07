<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AirtimeTransactionResource\Pages;
use App\Filament\Resources\AirtimeTransactionResource\RelationManagers;
use App\Models\AirtimeTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AirtimeTransactionResource extends Resource
{
    protected static ?string $model = AirtimeTransaction::class;
    protected static ?string $navigationGroup = 'Airtime';


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListAirtimeTransactions::route('/'),
            'create' => Pages\CreateAirtimeTransaction::route('/create'),
            'edit' => Pages\EditAirtimeTransaction::route('/{record}/edit'),
        ];
    }
}
