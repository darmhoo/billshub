<x-filament-panels::page>
    <div class="grid grid-cols-4 gap-4 sm:px-20 justify-center">
        <div class="rounded-full">
            <img src="/images/mtn.jpg" alt="img" width="50px" height="50px" class="w-1/2 max-h-[100%] rounded-full">
        </div>
        <div>
            <img src="/images/airtel.png" alt="img" width="50px" height="50px" class="w-1/2 max-h-[95%] rounded-full">
        </div>
        <div>
            <img src="/images/glo.jpg" alt="img" width="50px" height="50px" class="w-1/2 rounded-full">

        </div>
        <div>
            <img src="/images/9mobile.jpg" alt="img" width="50px" height="50px" class="w-1/2 rounded-full">

        </div>
    </div>
    <div>
        <form wire:submit="save">
            {{$this->form}}

        </form>

        {{$this->showOtp()}}

        <x-filament::modal>
            <x-slot name="trigger">
                <x-filament::button>
                    Open modal
                </x-filament::button>
            </x-slot>

            {{-- Modal content --}}
        </x-filament::modal>
        <x-filament-actions::modals />


    </div>

</x-filament-panels::page>