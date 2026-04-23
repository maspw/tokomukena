<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BahanBahanResource\Pages;
use App\Filament\Resources\BahanBahanResource\RelationManagers;
use App\Models\BahanBahan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class BahanBahanResource extends Resource
{
    protected static ?string $model = BahanBahan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_bahan')->required(),
                TextInput::make('satuan')->placeholder('cth: Kg, Liter, Pcs')->required(),
                TextInput::make('stok_qty')->numeric()->required(),
                FileUpload::make('foto_bahan')
                ->label('Foto Bahan')
                ->directory('foto-bahan') 
                ->acceptedFileTypes(['image/jpeg', 'image/png']) // Validasi file
                ->required(), 
                DatePicker::make('tanggal_expired')
                ->label('Tanggal Expired') 
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_bahan')->searchable(),
                TextColumn::make('satuan'),
                TextColumn::make('stok_qty'),
                ImageColumn::make('foto_bahan')->label('Foto'), 
                TextColumn::make('tanggal_expired')->date(),
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
            'index' => Pages\ListBahanBahans::route('/'),
            'create' => Pages\CreateBahanBahan::route('/create'),
            'edit' => Pages\EditBahanBahan::route('/{record}/edit'),
        ];
    }
}
