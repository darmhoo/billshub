<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataBundleResource\Pages;
use App\Filament\Resources\DataBundleResource\RelationManagers;
use App\Models\DataBundle;
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

class DataBundleResource extends Resource
{
    protected static ?string $model = DataBundle::class;
    protected static ?string $navigationGroup = 'Data';
    protected static ?string $recordTitleAttribute = 'name';

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name . ' - ' . $record->network->name . ' - ' . $record->automation->name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'price'
        ];
    }

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

                SelectFilter::make('data_type_id')
                    ->relationship('dataType', 'name')
                    ->label('Data Type'),
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
