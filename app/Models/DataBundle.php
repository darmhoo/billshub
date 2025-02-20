<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataBundle extends Model
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

    public function dataType(): BelongsTo
    {
        return $this->belongsTo(DataType::class, "data_type_id", "id");
    }
}