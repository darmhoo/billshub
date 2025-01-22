<x-filament-panels::page>
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