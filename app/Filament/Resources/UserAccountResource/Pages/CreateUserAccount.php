<?php

namespace App\Filament\Resources\UserAccountResource\Pages;

use App\Filament\Resources\UserAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserAccount extends CreateRecord
{
    protected static string $resource = UserAccountResource::class;
}
