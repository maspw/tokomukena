<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Supplier';

    protected static ?string $navigationGroup = 'Masterdata';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Supplier')
                    ->icon('heroicon-m-building-office')
                    ->schema([
                        Forms\Components\TextInput::make('kode_supplier')
                            ->label('Kode Supplier')
                            ->default(fn () => Supplier::getKodeSupplier())
                            ->required()
                            ->readonly()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('nama_supplier')
                            ->label('Nama Supplier')
                            ->required(),

                        Forms\Components\TextInput::make('alamat')
                            ->label('Alamat')
                            ->required(),

                        Forms\Components\TextInput::make('telepon')
                            ->label('Telepon')
                            ->tel()
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->nullable(),

                        Forms\Components\TextInput::make('pic')
                            ->label('PIC (Person In Charge)')
                            ->nullable(),

                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_supplier')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_supplier')
                    ->label('Nama Supplier')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(30),

                Tables\Columns\TextColumn::make('telepon')
                    ->label('Telepon'),

                Tables\Columns\TextColumn::make('pic')
                    ->label('PIC'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
