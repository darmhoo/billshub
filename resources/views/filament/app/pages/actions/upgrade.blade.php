<div class="flex flex-col gap-5 my-3">
    You are about to upgrade to <span class="text-lg font-bold inline">{{ ucfirst($plans->name) }}</span><br>
    This will cost you {{ '₦' . number_format(auth()->user()->accountType->name == 'bronze' ? 2000 : 3000, 2) }}<br>
</div>