<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationApp extends Model
{
    protected $table = 'notifications_app';

    protected $fillable = [
        'user_id',
        'type',
        'titre',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data'    => 'array',
        'read_at' => 'datetime',
    ];

    // ── Relations ──
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ──
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ── Helpers ──
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }
}
