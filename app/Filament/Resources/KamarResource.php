<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KamarResource\Pages;
use App\Filament\Resources\KamarResource\RelationManagers;
use App\Models\Kamar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KamarResource extends Resource
{
    protected static ?string $model = Kamar::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('no_kamar')
                ->default(fn () => Kamar::getNoKamar())
                ->readonly()
                ->required(),
            Forms\Components\TextInput::make('nama_kamar')->required(),
            Forms\Components\TextInput::make('lantai_kamar')->numeric()->required(),
            Forms\Components\FileUpload::make('foto_kamar')->directory('kamar'),
            Forms\Components\TextInput::make('harga_kamar')->numeric()->required(),
            Forms\Components\Select::make('status_kamar')
                ->options([
                    'Kosong' => 'Kosong',
                    'Terisi' => 'Terisi',
                ])->required(),
        ]);
}
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_kamar'),
            Tables\Columns\TextColumn::make('nama_kamar')->searchable(),
            Tables\Columns\TextColumn::make('lantai_kamar'),
            Tables\Columns\ImageColumn::make('foto_kamar'),
            Tables\Columns\TextColumn::make('harga_kamar')->money('IDR'),
            Tables\Columns\BadgeColumn::make('status_kamar')
                ->colors([
                    'success' => 'Kosong',
                    'danger' => 'Terisi',
                ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListKamars::route('/'),
            'create' => Pages\CreateKamar::route('/create'),
            'edit' => Pages\EditKamar::route('/{record}/edit'),
        ];
    }
}
