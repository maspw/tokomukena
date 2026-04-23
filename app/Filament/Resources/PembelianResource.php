<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianResource\Pages;
use App\Models\Pembelian;
use App\Models\PembelianBarang;
use App\Models\Barang;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// tambahan
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Filters\SelectFilter;

// DB
use Illuminate\Support\Facades\DB;
// untuk dapat menggunakan action
use Filament\Forms\Components\Actions\Action;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    // merubah nama label menjadi Pembelian
    protected static ?string $navigationLabel = 'Pembelian';

    // tambahan buat grup transaksi
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Wizard
                Wizard::make([
                    Wizard\Step::make('Data Pembelian')
                        ->schema([
                            // section 1
                            Forms\Components\Section::make('Info Pembelian')
                                ->icon('heroicon-m-document-text')
                                ->schema([
                                    TextInput::make('no_pembelian')
                                        ->default(fn () => Pembelian::generateNoPembelian())
                                        ->label('Nomor Pembelian')
                                        ->required()
                                        ->readonly()
                                    ,
                                    DatePicker::make('tgl_pembelian')
                                        ->label('Tanggal Pembelian')
                                        ->default(today())
                                        ->required()
                                    ,
                                    Select::make('supplier_id')
                                        ->label('Supplier')
                                        ->options(Supplier::pluck('nama_supplier', 'id')->toArray())
                                        ->required()
                                        ->placeholder('Pilih Supplier')
                                        ->searchable()
                                    ,
                                    Select::make('status')
                                        ->label('Status')
                                        ->options([
                                            'pending' => 'Pending',
                                            'diterima' => 'Diterima',
                                            'selesai' => 'Selesai',
                                        ])
                                        ->default('pending')
                                    ,
                                    TextInput::make('total_harga')
                                        ->default(0)
                                        ->hidden()
                                    ,
                                    Forms\Components\Textarea::make('catatan')
                                        ->label('Catatan')
                                        ->nullable()
                                        ->columnSpanFull()
                                    ,
                                ])
                                ->collapsible()
                                ->columns(3)
                            ,
                        ]),
                    Wizard\Step::make('Pilih Barang')
                    ->schema([
                            // untuk menambahkan repeater
                            Repeater::make('items')
                            ->relationship('pembelianBarangs')
                            ->schema([
                                Select::make('barang_id')
                                        ->label('Barang')
                                        ->options(Barang::pluck('nama_barang', 'id')->toArray())
                                        ->required()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->reactive()
                                        ->placeholder('Pilih Barang')
                                        ->afterStateUpdated(function ($state, $set) {
                                            $barang = Barang::find($state);
                                            $set('harga_beli', $barang ? $barang->harga_barang : 0);
                                        })
                                        ->searchable()
                                ,
                                TextInput::make('harga_beli')
                                    ->label('Harga Beli')
                                    ->numeric()
                                    ->default(0)
                                    ->reactive()
                                    ->dehydrated()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $jumlah = $get('jumlah') ?? 0;
                                        $set('subtotal', $state * $jumlah);
                                    })
                                ,
                                TextInput::make('jumlah')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->default(1)
                                    ->reactive()
                                    ->live()
                                    ->required()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $harga = $get('harga_beli') ?? 0;
                                        $set('subtotal', $harga * $state);
                                    })
                                ,
                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->default(0)
                                    ->readonly()
                                    ->dehydrated()
                                ,
                                DatePicker::make('tgl')
                                    ->label('Tanggal')
                                    ->default(today())
                                    ->required(),
                            ])
                            ->columns([
                                'md' => 5,
                            ])
                            ->addable()
                            ->deletable()
                            ->reorderable()
                            ->createItemButtonLabel('Tambah Barang')
                            ->minItems(1)
                            ->required()
                            ,

                            // Tombol Proses
                            Forms\Components\Actions::make([
                                Forms\Components\Actions\Action::make('Proses Pembelian')
                                    ->action(function ($get) {
                                        $pembelian = Pembelian::updateOrCreate(
                                            ['no_pembelian' => $get('no_pembelian')],
                                            [
                                                'tgl_pembelian' => $get('tgl_pembelian'),
                                                'supplier_id' => $get('supplier_id'),
                                                'status' => $get('status') ?? 'pending',
                                                'total_harga' => 0,
                                                'catatan' => $get('catatan'),
                                            ]
                                        );

                                        // Simpan data barang
                                        foreach ($get('items') as $item) {
                                            $subtotal = ($item['harga_beli'] ?? 0) * ($item['jumlah'] ?? 0);
                                            
                                            PembelianBarang::updateOrCreate(
                                                [
                                                    'pembelian_id' => $pembelian->id,
                                                    'barang_id' => $item['barang_id']
                                                ],
                                                [
                                                    'harga_beli' => $item['harga_beli'],
                                                    'jumlah' => $item['jumlah'],
                                                    'subtotal' => $subtotal,
                                                    'tgl' => $item['tgl'],
                                                ]
                                            );
                                        }

                                        // Hitung total harga
                                        $totalHarga = PembelianBarang::where('pembelian_id', $pembelian->id)
                                            ->sum('subtotal');

                                        // Update total harga di tabel pembelians
                                        $pembelian->update(['total_harga' => $totalHarga]);
                                    })
                                    ->label('Proses')
                                    ->color('primary'),
                            ])
                    ]),
                    Wizard\Step::make('Review')
                        ->schema([
                            Placeholder::make('Ringkasan Pembelian')
                                    ->content(fn (Get $get) => view('filament.components.pembelian-table', [
                                        'pembelians' => Pembelian::where('no_pembelian', $get('no_pembelian'))->get()
                                ])),
                        ]),
                ])->columnSpan(3)
                // Akhir Wizard
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_pembelian')->label('No Pembelian')->searchable(),
                TextColumn::make('supplier.nama_supplier')
                    ->label('Supplier')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'diterima' => 'info',
                        'selesai' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->formatStateUsing(fn (string|int|null $state): string => rupiah($state))
                    ->sortable()
                    ->alignment('end')
                ,
                TextColumn::make('tgl_pembelian')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Pending',
                        'diterima' => 'Diterima',
                        'selesai' => 'Selesai',
                    ])
                    ->searchable()
                    ->preload(),
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
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}