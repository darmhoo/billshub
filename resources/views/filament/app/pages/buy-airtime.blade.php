<x-filament-panels::page>
    <div class="grid grid-cols-4 gap-4 ">
        <div class="rounded-full">
            <img src="/images/mtn.jpg" width="150px" height="150px" alt="img" class="w-full h-full rounded-full">
        </div>
        <div>
            <img src="/images/airtel.png" width="200px" height="200px" alt="img" class="w-full h-full rounded-full">
        </div>
        <div>
            <img src="/images/glo.jpg" width="200px" height="200px" alt="img" class="w-full h-full rounded-full">

        </div>
        <div>
            <img src="/images/9mobile.jpg" width="200px" height="200px" alt="img" class="w-full h-full rounded-full">

        </div>
    </div>
    <div>
        <form wire:submit="save">
            {{$this->form}}
            <div class="my-5">
                <button type="submit" class="p-3 bg-green-300 text-green-950 rounded-lg w-full">submit</button>
            </div>
        </form>


    </div>

</x-filament-panels::page>