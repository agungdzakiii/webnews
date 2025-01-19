<?php

namespace App\Filament\Resources\BannerAdvertisementsResource\Pages;

use App\Filament\Resources\BannerAdvertisementsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBannerAdvertisements extends ListRecords
{
    protected static string $resource = BannerAdvertisementsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
