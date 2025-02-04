<?php

namespace App\Filament\Resources\OnboardingMessageResource\Pages;

use App\Filament\Resources\OnboardingMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOnboardingMessages extends ListRecords
{
    protected static string $resource = OnboardingMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
