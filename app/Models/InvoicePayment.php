<?php
/**
 * MediNexa - one payment against an invoice.
 *
 * The legacy schema had a single amount_received column on the invoice, which
 * cannot represent the ordinary case of a deposit now and the balance later. Each
 * payment is its own row, and what is still owed is the difference between the
 * invoice total and the sum of these.
 *
 * MediNexa Healthcare Platform
 * MediNexa - AI-Powered Healthcare Platform
 * Proprietary BCA Major Project.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoicePayment extends Model
{
    use HasFactory;

    protected $table = 'invoice_payment';
    public $timestamps = false;

    protected $fillable = [
        'payment_id',
        'amount',
        'method',
        'reference',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
