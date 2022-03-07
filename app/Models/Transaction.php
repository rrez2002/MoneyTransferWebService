<?php

namespace App\Models;

use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'description',
        'destinationFirstname',
        'destinationLastname',
        'destinationNumber',

        'reasonDescription',

        'deposit',

        'sourceFirstName',
        'sourceLastName',

        'message',

        'status',
        'trackId',
    ];

    protected $casts = [
        'type' => TransactionTypeEnum::class,
        'status' => TransactionStatusEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne
     */
    public function detail(): HasOne
    {
        return $this->hasOne(TransactionDetail::class);
    }
}
