<?php

namespace Tests\Feature;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatRoomTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_create_chat_room(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/chat-rooms', [
                'name' => 'Test Room',
                'is_private' => false,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'is_private', 'created_by']);

        $this->assertDatabaseHas('chat_rooms', [
            'name' => 'Test Room',
            'is_private' => false,
            'created_by' => $this->user->id,
        ]);
    }

    public function test_can_list_chat_rooms(): void
    {
        ChatRoom::factory(3)->create(['is_private' => false]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/chat-rooms');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_join_public_room(): void
    {
        $room = ChatRoom::factory()->create(['is_private' => false]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/chat-rooms/{$room->id}/join");

        $response->assertStatus(200);

        $this->assertTrue($room->hasMember($this->user));
    }

    public function test_cannot_join_private_room_without_permission(): void
    {
        $room = ChatRoom::factory()->create(['is_private' => true]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/chat-rooms/{$room->id}/join");

        $response->assertStatus(403);
    }

    public function test_can_send_message_in_joined_room(): void
    {
        $room = ChatRoom::factory()->create(['is_private' => false]);
        $room->users()->attach($this->user->id);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/chat-rooms/{$room->id}/messages", [
                'content' => 'Hello, World!',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'content', 'user_id', 'chat_room_id']);

        $this->assertDatabaseHas('messages', [
            'content' => 'Hello, World!',
            'user_id' => $this->user->id,
            'chat_room_id' => $room->id,
        ]);
    }

    public function test_cannot_send_message_in_non_joined_room(): void
    {
        $room = ChatRoom::factory()->create(['is_private' => false]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/chat-rooms/{$room->id}/messages", [
                'content' => 'Hello, World!',
            ]);

        $response->assertStatus(403);
    }

    public function test_can_get_messages_from_room(): void
    {
        $room = ChatRoom::factory()->create(['is_private' => false]);
        $room->users()->attach($this->user->id);

        Message::factory(10)->create([
            'chat_room_id' => $room->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/chat-rooms/{$room->id}/messages");

        $response->assertStatus(200)
            ->assertJsonCount(10);
    }

    public function test_can_leave_room(): void
    {
        $room = ChatRoom::factory()->create(['is_private' => false]);
        $room->users()->attach($this->user->id);

        $this->assertTrue($room->hasMember($this->user));

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/chat-rooms/{$room->id}/leave");

        $response->assertStatus(200);

        $this->assertFalse($room->fresh()->hasMember($this->user));
    }

    public function test_only_creator_can_delete_room(): void
    {
        $creator = User::factory()->create();
        $room = ChatRoom::factory()->create(['created_by' => $creator->id]);

        // Different user tries to delete
        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/chat-rooms/{$room->id}");

        $response->assertStatus(403);

        // Creator deletes
        $response = $this->actingAs($creator, 'sanctum')
            ->deleteJson("/api/chat-rooms/{$room->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('chat_rooms', ['id' => $room->id]);
    }
}
