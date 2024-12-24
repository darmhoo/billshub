<?php

namespace App\Livewire;

use App\Livewire\Forms\UserForm;
use Illuminate\Auth\Events\Registered;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class CreateUser extends Component
{
    public UserForm $form;

    #[Title('User Register')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.create-user');
    }

    public function save()
    {
        $user = $this->form->store();
        event(new Registered($user));
        return $this->redirect('/app');
    }
}
