<x-filament-widgets::widget class="grid grid-rows-1 grid-cols-1 sm:grid-cols-2 gap-5 auto-cols-auto overflow-auto ">
    <x-filament::section>
        <div class="flex justify-between items-center py-3">
            <div class="flex flex-col gap-2">
                <h1 class="text-5xl font-bold">{{'₦' . number_format($user->wallet_balance, 2)}}</h1>
                <p class="text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-6 inline-block text-green-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3" />
                    </svg>
                    Wallet Balance</p>
                <button class="bg-green-500 text-white p-2 rounded-lg">
                    FUND WALLET
                </button>

            </div>
            <div class="flex flex-col gap-5 justify-center items-center">
                <div>
                    <h1 class="text-md font-bold capitalize">{{$user->accountType->name}}</h1>
                   

                </div>
                <div>
                    @if ($user->accountType->slug == 'reseller_premium')
                        <button class="bg-green-500 text-white p-2 rounded-lg">
                            Hurrah! You are a premium user
                        </button>

                    @else
                        <button class="bg-yellow-500 text-white p-2 rounded-lg text-sm">
                            Upgrade Account
                        </button>

                    @endif

                </div>
            </div>
        </div>
        {{-- Widget content --}}

    </x-filament::section>



    <x-filament::section>
        {{-- Widget content --}}
        <div>Notifications</div>
    </x-filament::section>
</x-filament-widgets::widget>