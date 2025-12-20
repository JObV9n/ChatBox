<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'user_id',
        'content',
    ];

    /**
     * Get the user that owns the message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the chat room that owns the message.
     */
    public function chatRoom(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class);
    }

    /**
     * Get the message formatted for ZeroMQ broadcasting.
     */
    public function toZmqMessage(): array
    {
        return [
            'id' => $this->id,
            'chat_room_id' => $this->chat_room_id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'content' => $this->content,
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
