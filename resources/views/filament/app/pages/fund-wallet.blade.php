<x-filament-panels::page>
    <div class="w-full sm:flex gap-5">
        <div class="sm:w-2/3 p-5 dark:bg-slate-400 rounded-lg dark:text-purple-950">
            <p>Fund your wallet instantly by paying into the account details below and your account will be credited
                automatically.</p>
            <div class="my-5">
                NOTE: <span class="text-red-600 font-semibold">1% service charge</span> will be deducted from your
                payment.
            </div>

            @if (count($bank_accounts) < 1)
                <div class="bg-red-50 text-red-500 p-3 rounded-lg">
                    <p class="mb-5">You need to get a virtual account from our trusted vendors</p>
                    {{$this->getVirtualAccount() }}
                    <x-filament-actions::modals />
                </div>

            @endif
            <div class="flex flex-col gap-5 my-3">
                @foreach ($bank_accounts as $account)
                    <div>
                        <h3>
                            <span class="font-bold">Bank Name: </span>{{$account['bank_name']}}
                        </h3>
                        <p><span class="font-bold">Account Name: </span>{{$account['account_name']}}</p>
                        <p><span class="font-bold">Account Number: </span>{{$account['account_number']}}</p>

                    </div>

                @endforeach

            </div>
        </div>
        <!-- <div class="sm:w-1/3 flex flex-col gap-5 text-sm p-5 bg-slate-800 text-white dark:bg-transparent rounded-lg">
            <h3>YOU ARE FEW STEPS TO FUNDING YOUR WALLET! 👍💃🕺😎</h3>
            <div>
                1️⃣. AUTOMATED FUNDING 🏦 @ #54 FEE: On this page scroll Up 🔝 & Transfer to either WEMA, MONIEPOINT, or
                STERLING account number. Once your transfer is successful, Wallet will be funded AUTOMATICALLY!
            </div>
            <div>
                2️⃣. MANUAL FUNDING 🔁 @ #0 FEE: Minimum is #2,000 & #200,000 maximum. Transfer to MONIEPOINT:
                5442693497 [Hephzi-gee Solutions/Gbills247]. ℹ️Use your Username as narration if possible & send proof
                of payment to WhatsApp: 08034905635. "Your wallet will be funded upon confirmation"
            </div>
            <div>
                For complaints/further assistance, please click on SUPPORT or WHATSAPP: 08034905635
            </div>
        </div> -->
    </div>
</x-filament-panels::page>