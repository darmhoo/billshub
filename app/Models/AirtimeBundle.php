<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AirtimeBundle extends Model
{
    //
    protected $guarded = [];

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class, 'network_id', 'id');
    }

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, "account_type_id", "id");
    }

    public function automation(): BelongsTo
    {
        return $this->belongsTo(Automation::class, "automate_id", "id");
    }
}
