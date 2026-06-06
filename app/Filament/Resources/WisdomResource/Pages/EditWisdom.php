<?php

namespace App\Filament\Resources\WisdomResource\Pages;

use App\Filament\Resources\WisdomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWisdom extends EditRecord
{
    protected static string $resource = WisdomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
