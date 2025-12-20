<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChatRoomController extends Controller
{
    /**
     * Display a listing of the chat rooms.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get public rooms and private rooms the user is a member of
        $chatRooms = ChatRoom::where('is_private', false)
            ->orWhereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('creator:id,name')
            ->withCount('users')
            ->latest()
            ->get();

        return response()->json($chatRooms);
    }

    /**
     * Store a newly created chat room.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_private' => 'boolean',
        ]);

        $chatRoom = ChatRoom::create([
            'name' => $validated['name'],
            'is_private' => $validated['is_private'] ?? false,
            'created_by' => auth()->id(),
        ]);

        // Automatically add creator to the room
        $chatRoom->users()->attach(auth()->id());

        return response()->json($chatRoom->load('creator:id,name'), 201);
    }

    /**
     * Display the specified chat room.
     */
    public function show(ChatRoom $chatRoom)
    {
        Gate::authorize('view', $chatRoom);

        return response()->json(
            $chatRoom->load(['creator:id,name', 'users:id,name'])
        );
    }

    /**
     * Update the specified chat room.
     */
    public function update(Request $request, ChatRoom $chatRoom)
    {
        Gate::authorize('update', $chatRoom);

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
        Gate::authorize('delete', $chatRoom);

        $chatRoom->delete();

        return response()->json(null, 204);
    }

    /**
     * Join a chat room.
     */
    public function join(ChatRoom $chatRoom)
    {
        Gate::authorize('join', $chatRoom);

        $user = auth()->user();

        if (!$chatRoom->hasMember($user)) {
            $chatRoom->users()->attach($user->id);
        }

        return response()->json(['message' => 'Joined successfully']);
    }

    /**
     * Leave a chat room.
     */
    public function leave(ChatRoom $chatRoom)
    {
        $user = auth()->user();
        
        $chatRoom->users()->detach($user->id);

        return response()->json(['message' => 'Left successfully']);
    }

    /**
     * Get online users in a chat room.
     */
    public function onlineUsers(ChatRoom $chatRoom)
    {
        Gate::authorize('view', $chatRoom);

        $users = $chatRoom->users()
            ->select('users.id', 'users.name')
            ->get();

        return response()->json($users);
    }
}
