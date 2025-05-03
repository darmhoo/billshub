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
        @if ($this->insufficient)
            <div class="text-red-500">
                <p>Insufficient balance. Please fund your account.</p>
            </div>

        @else
            <div class="text-green-500">
                <p>Balance: {{ auth()->user()->balance }}</p>
            </div>

        @endif
        <form wire:submit="create">
            {{$this->form}}
        </form>


    </div>

</x-filament-panels::page>