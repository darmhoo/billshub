<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    //
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required|confirmed|min:8')]
    public $password = '';

    #[Validate('required|string')]
    public $name = '';

    #[Validate('required|string|min:11|max:11')]
    public $phone_number = '';

    public $password_confirmation = '';

    public function store()
    {
        $this->validate();


        // Execution doesn't reach here if validation fails.
        return User::create([
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'name' => $this->name,
            'phone_number' => $this->phone_number,
        ]);
    }
}
