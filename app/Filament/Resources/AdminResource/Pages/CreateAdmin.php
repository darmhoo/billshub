<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $permissions = $data['permissions'];
        unset($data['permissions']);
        $admin = static::getModel()::create($data);
        $admin->syncPermissions($permissions);
        $admin->assignRole('admin');
        $admin->email_verified_at = now();
        $admin->save();
        return $admin;
    }


}
