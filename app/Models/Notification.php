<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'link',
        'image',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Boot the model and set default type if not provided
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($notification) {
            if (empty($notification->type)) {
                $notification->type = 'order';
            }
        });
    }

    /**
     * Get the appropriate link for this notification
     */
    public function getRedirectUrl(): string
    {
        // If explicit link is set, use it
        if ($this->link) {
            return $this->link;
        }

        // Determine link based on type
        return match($this->type) {
            'new_order' => route('admin.orders.index'),
            'order' => route('orders.history'),
            'order_status' => route('orders.history'),
            'order_cancelled' => route('orders.history'),
            'payment_approved' => route('orders.history'),
            'payment_rejected' => route('orders.history'),
            'message' => route('admin.messages.index'),
            'message_reply' => route('messages.index'),
            'welcome' => route('home'),
            'verification' => route('verification.notice'),
            'verification_success' => route('profile'),
            'login' => route('profile'),
            'announcement' => route('home'),
            default => route('home'),
        };
    }
}
