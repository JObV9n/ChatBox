<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Services\ZmqPublisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class MessageController extends Controller
{
    public function __construct(
        protected ZmqPublisher $zmqPublisher
    ) {}

    /**
     * Get messages for a chat room.
     */
    public function index(Request $request, ChatRoom $chatRoom)
    {
        $validated = $request->validate([
            'limit' => 'sometimes|integer|min:1|max:100',
            'before' => 'sometimes|integer|exists:messages,id',
        ]);

        $query = $chatRoom->messages()
            ->with('user:id,name')
            ->latest();

        if (isset($validated['before'])) {
            $query->where('id', '<', $validated['before']);
        }

        $messages = $query
            ->limit($validated['limit'] ?? 50)
            ->get()
            ->reverse()
            ->values();

        return response()->json($messages);
    }

    public function store(Request $request, ChatRoom $chatRoom)
    {
        // Rate limiting: 10 messages per minute per IP per room
        $key = 'message:' . $request->ip() . ':' . $chatRoom->id;
        
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => "Too many messages. Please try again in {$seconds} seconds."
            ], 429);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'username' => 'required|string|max:50',
        ]);

        $message = Message::create([
            'chat_room_id' => $chatRoom->id,
            'user_id' => null, // Anonymous user
            'content' => $validated['content'],
            'username' => $validated['username'],
        ]);

        RateLimiter::hit($key, 60);

        // Publish to ZeroMQ
        $this->zmqPublisher->publishMessage($chatRoom->id, $message);

        return response()->json($message, 201);
    }

    /**
     * Poll for new messages (for clients without SSE).
     */
    public function poll(Request $request, ChatRoom $chatRoom)
    {
        $validated = $request->validate([
            'after' => 'sometimes|integer|exists:messages,id',
            'limit' => 'sometimes|integer|min:1|max:100',
        ]);

        $query = $chatRoom->messages()->with('user:id,name');

        if (isset($validated['after'])) {
            $query->where('id', '>', $validated['after']);
        }

        $messages = $query
            ->latest()
            ->limit($validated['limit'] ?? 50)
            ->get()
            ->reverse()
            ->values();

        return response()->json($messages);
    }
}
