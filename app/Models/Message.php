<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'message',
        'images',
        'admin_reply',
        'reply_images',
        'replied_at',
        'reply_read',
        'is_read',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeReplied($query)
    {
        return $query->whereNotNull('admin_reply');
    }

    public function isReplied(): bool
    {
        return !is_null($this->admin_reply);
    }
}
