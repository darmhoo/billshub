<x-filament-widgets::widget>
    {{-- Widget content --}}
    <!-- <div class="mt-0 mx-5">Welcome, {{auth()->user()->name}}</div> -->
    <div class="grid grid-rows-1 grid-cols-1 sm:grid-cols-2 gap-5 auto-cols-auto overflow-auto ">
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
                    <a href="fund-wallet">
                        <button class="bg-green-500 text-white p-2 rounded-lg">

                            FUND WALLET
                        </button>
                    </a>


                </div>
                <div class="flex flex-col gap-5 justify-center items-center">
                    <div class="flex gap-2 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-10 ">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                        </svg>

                        <h1 class="text-md font-bold capitalize">{{$user->accountType->name ?? 'N/A'}}</h1>


                    </div>
                    <div>
                        @if ($user->accountType)
                            @if($user->accountType->slug == 'reseller_premium')
                                <button class="bg-green-500 text-white p-2 rounded-lg">
                                    Hurrah! You are a premium user
                                </button>

                            @else
                                {{$this->upgradeAccount}}

                                <x-filament-actions::modals />

                            @endif
                        @endif

                    </div>
                </div>
            </div>
            {{-- Widget content --}}

        </x-filament::section>



        <x-filament::section>
            {{-- Widget content --}}
            <div>Notifications</div>
            @if (count($notifications) == 0)
                <div>
                    No notifications
                </div>
            @else
                @foreach ($notifications as $notification)
                    <div>
                        {{$notification->message}}
                    </div>
                @endforeach
            @endif

        </x-filament::section>
    </div>

</x-filament-widgets::widget>