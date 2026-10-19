<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /** status => [label, bootstrap colour] */
    public const STATUSES = [
        self::STATUS_PENDING => ['Menunggu Pembayaran', 'warning'],
        self::STATUS_PAID => ['Dibayar', 'info'],
        self::STATUS_PROCESSING => ['Diproses', 'primary'],
        self::STATUS_SHIPPED => ['Dikirim', 'secondary'],
        self::STATUS_COMPLETED => ['Selesai', 'success'],
        self::STATUS_CANCELLED => ['Dibatalkan', 'danger'],
    ];

    protected $fillable = [
        'order_number', 'user_id', 'total_amount', 'status', 'recipient_name',
        'phone', 'shipping_address', 'notes', 'payment_proof', 'paid_at',
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status][0] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUSES[$this->status][1] ?? 'secondary';
    }

    public function getPaymentProofUrlAttribute(): ?string
    {
        return $this->payment_proof ? Storage::disk('public')->url($this->payment_proof) : null;
    }

    public function canBeCancelled(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /** Cancels the order and returns the reserved stock to the shelf (idempotent). */
    public function cancelAndRestock(): void
    {
        if ($this->status === self::STATUS_CANCELLED) {
            return;
        }

        DB::transaction(function () {
            foreach ($this->items()->whereNotNull('product_id')->get() as $item) {
                Product::whereKey($item->product_id)->increment('stock', $item->quantity);
            }

            $this->update(['status' => self::STATUS_CANCELLED]);
        });
    }

    public static function generateNumber(): string
    {
        do {
            $number = 'TBMJ-' . now()->format('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
        } while (static::where('order_number', $number)->exists());

        return $number;
    }
}
