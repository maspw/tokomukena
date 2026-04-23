<?php

namespace App\Filament\Resources\BahanBahanResource\Pages;

use App\Filament\Resources\BahanBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBahanBahans extends ListRecords
{
    protected static string $resource = BahanBahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
