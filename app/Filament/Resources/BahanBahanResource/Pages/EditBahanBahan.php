<?php

namespace App\Filament\Resources\BahanBahanResource\Pages;

use App\Filament\Resources\BahanBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBahanBahan extends EditRecord
{
    protected static string $resource = BahanBahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
