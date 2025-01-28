<x-filament-panels::page>
    <div class="grid grid-cols-4 gap-4 sm:px-20">
        <div class="rounded-full">
            <img src="/images/mtn.jpg" alt="img" width="50px" height="50px" class="w-full max-h-[100%] rounded-full">
        </div>
        <div>
            <img src="/images/airtel.png" alt="img" width="50px" height="50px" class="w-full max-h-[95%] rounded-full">
        </div>
        <div>
            <img src="/images/glo.jpg" alt="img" width="50px" height="50px" class="w-full rounded-full">

        </div>
        <div>
            <img src="/images/9mobile.jpg" alt="img" width="50px" height="50px" class="w-full rounded-full">

        </div>
    </div>
    <div class="sm:flex w-full sm:gap-5">
        <div class="sm:w-3/5">
            <form wire:submit="save">
                {{$this->form}}
                <div class="my-5">
                    <button type="submit" class="p-3 bg-green-300 text-green-950 rounded-lg w-full">submit</button>
                </div>
            </form>

        </div>

        <div class="px-10 py-5 sm:w-2/5">
            <h3>Data Balance code</h3>
            <ul class="flex flex-col gap-5">
                <li>
                    🟡MTN (SME): *461*4#
                </li>
                <li>
                    🟢GLO (CG / GIFTING): *323#
                </li>
                <li>
                    🟡MTN (CG / GIFTING): *460*260# / *323*4#
                </li>
                <li>
                    🔴AIRTEL (CG / GIFTING): *323#
                </li>
                <li>
                    🟢9MOBILE: *323#
                </li>
            </ul>
        </div>
    </div>
</x-filament-panels::page>