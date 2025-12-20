<?php

namespace App\Policies;

use App\Models\ChatRoom;
use App\Models\User;

class ChatRoomPolicy
{
    /**
     * Determine if the user can view the chat room.
     */
    public function view(User $user, ChatRoom $chatRoom): bool
    {
        // Public rooms can be viewed by anyone
        if (!$chatRoom->is_private) {
            return true;
        }

        // Private rooms can only be viewed by members
        return $chatRoom->hasMember($user);
    }

    /**
     * Determine if the user can update the chat room.
     */
    public function update(User $user, ChatRoom $chatRoom): bool
    {
        return $user->id === $chatRoom->created_by;
    }

    /**
     * Determine if the user can delete the chat room.
     */
    public function delete(User $user, ChatRoom $chatRoom): bool
    {
        return $user->id === $chatRoom->created_by;
    }

    /**
     * Determine if the user can join the chat room.
     */
    public function join(User $user, ChatRoom $chatRoom): bool
    {
        // Can't join if already a member
        if ($chatRoom->hasMember($user)) {
            return false;
        }

        // Anyone can join public rooms
        if (!$chatRoom->is_private) {
            return true;
        }

        // For private rooms, additional logic could be implemented
        // (e.g., invitation system)
        return false;
    }

    /**
     * Determine if the user can send messages in the chat room.
     */
    public function sendMessage(User $user, ChatRoom $chatRoom): bool
    {
        return $chatRoom->hasMember($user);
    }
}
