<x-filament-panels::page>
    <div class="grid sm:grid-cols-6 grid-cols-4 gap-4 sm:px-20 justify-center">
        <div class="rounded-full">
            <img src="/images/electricity/WhatsApp Image 2025-02-26 at 10.43.51 PM (1).jpeg" alt="img" width="50px"
                height="50px" class="w-1/2 max-h-[80%] ">
        </div>
        <div>
            <img src="/images/electricity/WhatsApp Image 2025-02-26 at 10.43.51 PM.jpeg" alt="img" width="50px"
                height="50px" class="w-1/2 max-h-[75%] rounded-full">
        </div>
        <div>
            <img src="/images/electricity/WhatsApp Image 2025-02-26 at 10.43.52 PM (1).jpeg" alt="img" width="50px"
                height="50px" class="w-1/2 rounded-full">

        </div>
        <div>
            <img src="/images/electricity/WhatsApp Image 2025-02-26 at 10.43.52 PM (2).jpeg" alt="img" width="50px"
                height="50px" class="w-1/2 ">

        </div>
        <div>
            <img src="/images/electricity/WhatsApp Image 2025-02-26 at 10.43.52 PM (3).jpeg" alt="img" width="50px"
                height="50px" class="w-1/2 rounded-full">
        </div>

        <div>
            <img src="/images/electricity/WhatsApp Image 2025-02-26 at 11.54.44 PM (1).jpeg" alt="img" width="50px"
                height="50px" class="w-1/2 rounded-full">
        </div>
        <div>
            <img src="/images/electricity/WhatsApp Image 2025-02-27 at 12.04.17 AM.jpeg" alt="img" width="50px"
                height="50px" class="w-1/2 max-h-[100%]  rounded-full">
        </div>
        <div>
            <img src="/images/electricity/WhatsApp Image 2025-02-26 at 11.54.44 PM.jpeg" alt="img" width="50px"
                height="50px" class="w-1/2 rounded-full max-h-[100%] ">
        </div>
        <div>
            <img src="/images/electricity/WhatsApp Image 2025-02-26 at 10.43.53 PM (2).jpeg" alt="img" width="50px"
                height="50px" class="w-1/2 rounded-full">
        </div>
        <div>
            <img src="/images/electricity/WhatsApp Image 2025-02-26 at 10.43.53 PM.jpeg" alt="img" width="50px"
                height="50px" class="w-1/2 rounded-full">
        </div>
        <div>
            <img src="/images/electricity/WhatsApp Image 2025-02-26 at 10.43.54 PM.jpeg" alt="img" width="50px"
                height="50px" class="w-1/2 rounded-full">
        </div>
        <div>
            <img src="/images/electricity/id4ac23B5q_1740504126139.png" alt="img" width="40px" height="40px"
                class="w-1/2 rounded-full max-h-[80%] ">
        </div>
    </div>
    <div class="w-full sm:flex gap-5">
        <div class="sm:w-3/5">
            <form wire:submit="save">
                {{$this->form}}

            </form>


        </div>
        @if($verifiedAccount)


            <div class="sm:w-2/5">
                <div class="px-4 sm:px-0">
                    <h3 class="text-base/7 font-semibold text-gray-900">Account</h3>
                    <p class="mt-1 max-w-2xl text-sm/6 text-gray-500">Personal details and application.</p>
                </div>
                <div class="mt-6 border-t border-gray-100">
                    <dl class="divide-y divide-gray-100">
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm/6 font-medium text-gray-900">Account Name</dt>
                            <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{$verifiedAccount['content']['Customer_Name']}}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm/6 font-medium text-gray-900">Address</dt>
                            <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{$verifiedAccount['content']['Address']}}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm/6 font-medium text-gray-900">Meter Type</dt>
                            <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{$verifiedAccount['content']['Meter_Type']}}
                            </dd>
                        </div>

                    </dl>
                </div>
            </div>

        @endif

    </div>


</x-filament-panels::page>