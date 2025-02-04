<?php

namespace App\Filament\Resources\OnboardingMessageResource\Pages;

use App\Filament\Resources\OnboardingMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOnboardingMessage extends EditRecord
{
    protected static string $resource = OnboardingMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
