<?php

namespace App\Filament\Resources\WisdomResource\Pages;

use App\Filament\Resources\WisdomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWisdoms extends ListRecords
{
    protected static string $resource = WisdomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
