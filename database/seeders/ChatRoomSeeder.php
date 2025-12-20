<?php

namespace Database\Seeders;

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users
        $users = User::factory(5)->create();

        // Create public chat rooms
        $publicRooms = [
            ['name' => 'General Chat', 'is_private' => false],
            ['name' => 'Technology', 'is_private' => false],
            ['name' => 'Random', 'is_private' => false],
        ];

        foreach ($publicRooms as $roomData) {
            $room = ChatRoom::create([
                'name' => $roomData['name'],
                'is_private' => $roomData['is_private'],
                'created_by' => $users->random()->id,
            ]);

            // Add random users to each room
            $room->users()->attach(
                $users->random(rand(2, 5))->pluck('id')
            );
        }

        // Create private chat rooms
        $privateRooms = [
            ['name' => 'Private Group 1', 'is_private' => true],
            ['name' => 'Private Group 2', 'is_private' => true],
        ];

        foreach ($privateRooms as $roomData) {
            $room = ChatRoom::create([
                'name' => $roomData['name'],
                'is_private' => $roomData['is_private'],
                'created_by' => $users->random()->id,
            ]);

            // Add specific users to private rooms
            $room->users()->attach(
                $users->random(2)->pluck('id')
            );
        }

        $this->command->info('Chat rooms created successfully!');
    }
}
