<div>
    <form wire:submit="save" class="flex flex-col space-y-4">
        <div class="form-group>
         <label for=" name">Name</label>
            <input type="text" wire:model="form.name" class="form-control" id="name" placeholder="Enter name">
            @error('form.name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" wire:model="form.email" class="form-control" id="email" placeholder="Enter email">
            @error('form.email') <span class="text-red-500">{{ $message }}</span> @enderror

        </div>

        <div class="form-group">
            <label for="phone">Phone number</label>
            <input type="text" wire:model="form.phone_number" class="form-control" id="phone" placeholder="Enter phone">
            @error('form.phone_number') <span class="text-red-500">{{ $message }}</span> @enderror

        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" wire:model="form.password" class="form-control" id="password" placeholder="Password">
            @error('form.password') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirm"> Confirm Password</label>
            <input type="password" wire:model="form.password_confirmation" class="form-control" id="password_confirm" placeholder="Password">
            @error('form.password_confirmation') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-400 w-1/2">Submit</button>

    </form>
</div>