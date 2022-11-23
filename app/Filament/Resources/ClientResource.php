<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
    




class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('country_id')
                        ->label('Country')
                        ->options(Country::all()->pluck('name','id')->toArray())
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),
                Select::make('state_id')
                        ->label('State')
                        ->options(function(callable $get) {
                            $country = Country::find($get('country_id'));
                            if (!$country) {
                                return State::all()->pluck('name','id');
                            }
                            return $country->states->pluck('name', 'id');
                        })
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),
                Select::make('city_id')
                        ->label('City')
                        ->options(function(callable $get) {
                            $state = State::find($get('state_id'));
                            if (!$state) {
                                return City::all()->pluck('name','id');
                            }
                            return $state->cities->pluck('name', 'id');
                        })
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                //     ->relationship('country','name')->required(),
                // Select::make('state_id')
                //     ->relationship('state','name')->required(),
                // Select::make('city_id')
                //     ->relationship('city','name')->required(),


                Select::make('business_type_id')
                    ->relationship('business_type','name')->required(),
                Forms\Components\TextInput::make('business_name')
                    // ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('first_name')
                    // ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    // ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    // ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('zip_code')
                    // ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    // ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('email')
                    ->email()
                    // ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('country.name'),
                Tables\Columns\TextColumn::make('business_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('first_name')->searchable(),
                Tables\Columns\TextColumn::make('business_type.name')->sortable(),
                // Tables\Columns\TextColumn::make('state.name'),
                Tables\Columns\TextColumn::make('city.name'),
                // Tables\Columns\TextColumn::make('last_name'),
                // Tables\Columns\TextColumn::make('address'),
                // Tables\Columns\TextColumn::make('zip_code'),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }    
}
