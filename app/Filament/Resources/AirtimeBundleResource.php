<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AirtimeBundleResource\Pages;
use App\Filament\Resources\AirtimeBundleResource\RelationManagers;
use App\Models\AirtimeBundle;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class AirtimeBundleResource extends Resource
{
    protected static ?string $model = AirtimeBundle::class;
    protected static ?string $navigationGroup = 'Airtime';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name . ' - ' . $record->automation->name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
        ];
    }
    protected static ?string $navigationIcon = 'heroicon-o-phone';

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
            ->filters([
                //
                TernaryFilter::make('is_active')
                    ->nullable()
                    ->trueLabel('Active')
                    ->falseLabel('Inactive')
                    ->label('Status'),

                SelectFilter::make('network_id')
                    ->relationship('network', 'name')
                    ->label('Network'),

                SelectFilter::make('account_type')
                    ->relationship('accountType', 'name')
                    ->label('Account Type'),
                SelectFilter::make('automation')
                    ->relationship('automation', 'name')
                    ->label('Automation'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['created_from'] && !$data['created_until']) {
                            return null;
                        }

                        return 'Created at ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                    })


            ])

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
