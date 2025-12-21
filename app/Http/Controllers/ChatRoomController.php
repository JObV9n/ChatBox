<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use Illuminate\Http\Request;

class ChatRoomController extends Controller
{
    
    public function index()
    {
        // Get all public rooms
        $chatRooms = ChatRoom::where('is_private', false)
            ->withCount('users')
            ->latest()
            ->get();

        return response()->json($chatRooms);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_private' => 'boolean',
        ]);

        $chatRoom = ChatRoom::create([
            'name' => $validated['name'],
            'is_private' => $validated['is_private'] ?? false,
            'created_by' => null, // Anonymous
        ]);

        return response()->json($chatRoom, 201);
    }

    /**
     * Display the specified chat room.
     */
    public function show(ChatRoom $chatRoom)
    {
        return response()->json($chatRoom->loadCount('users'));
    }

    /**
     * Update the specified chat room.
     */
    public function update(Request $request, ChatRoom $chatRoom)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'is_private' => 'sometimes|boolean',
        ]);

        $chatRoom->update($validated);

        return response()->json($chatRoom);
    }

    /**
     * Remove the specified chat room.
     */
    public function destroy(ChatRoom $chatRoom)
    {
        $chatRoom->delete();

        return response()->json(null, 204);
    }

    /**
     * Join a chat room.
     */
    public function join(Request $request, ChatRoom $chatRoom)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50',
        ]);

        // For anonymous users, just return success
        // In a real implementation, you might want to track this in session/cache
        return response()->json([
            'message' => 'Joined successfully',
            'room' => $chatRoom,
        ]);
    }

    /**
     * Leave a chat room.
     */
    public function leave(ChatRoom $chatRoom)
    {
        return response()->json(['message' => 'Left successfully']);
    }

    /**
     * Get online users in a chat room.
     */
    public function onlineUsers(ChatRoom $chatRoom)
    {
        return response()->json([
            'count' => $chatRoom->users()->count(),
            'users' => []
        ]);
    }
}
