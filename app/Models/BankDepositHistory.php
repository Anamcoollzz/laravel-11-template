<?php

namespace App\Models;

use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDepositHistory extends Model
{
    use HasFactory, UserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_deposit_id',
        'per_anum',
        'amount',
        'tax',
        'tax_percentage',
        'estimation',
        'time_period',
        'due_date',
        // 'status',
        'realization',
        'difference',
        'created_by_id',
        'last_updated_by_id',
    ];

    /**
     * Get the bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bankdeposit()
    {
        return $this->belongsTo(BankDeposit::class, 'bank_deposit_id');
    }
}
