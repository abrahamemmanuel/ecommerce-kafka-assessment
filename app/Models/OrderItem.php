<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected array $fillable = [
        'order_id',
        'product_id'
    ];

    public function items(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
