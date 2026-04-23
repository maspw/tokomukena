<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use App\Models\Pembelian;
use App\Models\PembelianBarang;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

// untuk notifikasi
use Filament\Notifications\Notification;

class CreatePembelian extends CreateRecord
{
    protected static string $resource = PembelianResource::class;

    // penanganan kalau status masih kosong
    protected function beforeCreate(): void
    {
        $this->data['status'] = $this->data['status'] ?? 'pending';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-generate nomor jika kosong
        if (empty($data['no_pembelian'])) {
            $data['no_pembelian'] = Pembelian::generateNoPembelian();
        }

        return $data;
    }

    // tambahan untuk simpan
    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('terima')
                ->label('Terima Barang')
                ->color('success')
                ->action(fn () => $this->terimaBarang())
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Penerimaan Barang')
                ->modalDescription('Apakah Anda yakin ingin menerima barang? Stok barang akan otomatis bertambah.')
                ->modalButton('Ya, Terima'),
        ];
    }

    // penanganan terima barang
    protected function terimaBarang()
    {
        $pembelian = $this->record ?? Pembelian::latest()->first();

        // Update status pembelian jadi "diterima" (trigger akan menambah stok)
        $pembelian->update(['status' => 'diterima']);

        // Notifikasi sukses
        Notification::make()
            ->title('Barang Berhasil Diterima!')
            ->body('Stok barang telah otomatis bertambah.')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}