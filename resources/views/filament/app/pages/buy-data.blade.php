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
    <div class="sm:flex w-full sm:gap-5">
        <div class="sm:w-3/5">
            <form wire:submit="save">
                {{$this->form}}
                <div class="my-5">
                    <button type="submit" class="p-3 bg-green-300 text-green-950 rounded-lg w-full">submit</button>
                </div>
            </form>

        </div>

        <div class="sm:px-10 px-3 py-5 sm:w-2/5">
            <h3 class="font-bold my-2">Data Balance Code: </h3>
            <ul class="flex flex-col gap-5">
                <li class="flex justify-normal items-center gap-2">
                    <img src="/images/mtn.jpg" width="40px" height="40px" alt="" class="rounded-full">
                    <div>
                        <div>SME: *461*4#</div>
                        <div>CG or GIFTING: *460*260# / *323#</div>
                    </div>
                </li>
                <li class="flex justify-normal items-center gap-2">
                    <img src="/images/airtel.png" width="40px" height="40px" alt="" class="rounded-full">

                    CG or GIFTING: *323#
                </li>
                <li class="flex justify-normal items-center gap-2">
                    <img src="/images/glo.jpg" width="40px" height="40px" alt="" class="rounded-full">

                    CG or GIFTING: *323#
                </li>


                <li class="flex justify-normal items-center gap-2">
                    <img src="/images/9mobile.jpg" width="40px" height="40px" alt="" class="rounded-full">

                    CG or GIFTING: *323#
                </li>
            </ul>
        </div>
    </div>
</x-filament-panels::page>