<template>
    <div class="chat-app min-h-screen bg-gray-100">
        <!-- Top Navbar -->
        <div class="bg-white shadow-md border-b">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <h1 class="text-2xl font-bold text-blue-600">ChatBox</h1>
                    <span class="text-sm text-gray-500">Anonymous Chat</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-sm">
                        <span class="text-gray-600">User:</span>
                        <strong class="text-blue-600">{{ username || 'Anonymous' }}</strong>
                    </div>
                    <div class="text-sm border-l pl-4">
                        <span class="text-gray-600">IP:</span>
                        <strong class="text-gray-800">{{ userIp || 'Loading...' }}</strong>
                    </div>
                    <div v-if="deviceInfo" class="text-sm border-l pl-4">
                        <span class="text-gray-600">Device:</span>
                        <strong class="text-gray-800">{{ deviceInfo.type }}</strong>
                        <span class="text-gray-500 ml-1">({{ deviceInfo.browser }})</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container mx-auto p-4">
            <div class="grid grid-cols-12 gap-4 h-[calc(100vh-8rem)]">
                <!-- Sidebar - Chat Rooms List -->
                <div class="col-span-3 bg-white rounded-lg shadow-lg p-4 overflow-y-auto">
                    <div class="mb-4">
                        <h2 class="text-xl font-bold mb-2">Chat Rooms</h2>
                        <button 
                            @click="showCreateRoom = true"
                            class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                        >
                            Create Room
                        </button>
                    </div>
                    
                    <div class="space-y-2">
                        <div 
                            v-for="room in chatRooms" 
                            :key="room.id"
                            @click="selectRoom(room)"
                            :class="[
                                'p-3 rounded cursor-pointer transition',
                                currentRoom?.id === room.id 
                                    ? 'bg-blue-100 border-l-4 border-blue-500' 
                                    : 'hover:bg-gray-100'
                            ]"
                        >
                            <div class="font-semibold">{{ room.name }}</div>
                            <div class="text-xs text-gray-500">
                                <span v-if="room.is_private" class="text-red-500">🔒 Private</span>
                                <span v-else class="text-green-500">🌐 Public</span>
                                · {{ room.users_count }} users
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Chat Area -->
                <div class="col-span-9 bg-white rounded-lg shadow-lg flex flex-col">
                    <div v-if="currentRoom" class="flex flex-col h-full">
                        <!-- Chat Header -->
                        <div class="border-b p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-xl font-bold">{{ currentRoom.name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ currentRoom.users_count }} users online
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    <span class="text-sm text-gray-600 px-4 py-2 bg-gray-100 rounded">
                                        Chatting as: <strong>{{ username }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Messages Area -->
                        <div 
                            ref="messagesContainer"
                            class="flex-1 overflow-y-auto p-4 space-y-3"
                        >
                            <div 
                                v-for="message in messages" 
                                :key="message.id"
                                :class="[
                                    'flex',
                                    message.username === username ? 'justify-end' : 'justify-start'
                                ]"
                            >
                                <div 
                                    :class="[
                                        'max-w-md px-4 py-2 rounded-lg',
                                        message.username === username
                                            ? 'bg-blue-500 text-white'
                                            : 'bg-gray-200'
                                    ]"
                                >
                                    <div class="text-xs opacity-75 mb-1">
                                        {{ message.username }} · {{ formatTime(message.created_at) }}
                                    </div>
                                    <div>{{ message.content }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Message Input -->
                        <div class="border-t p-4">
                            <form @submit.prevent="sendMessage" class="flex gap-2">
                                <input 
                                    v-model="newMessage"
                                    type="text"
                                    placeholder="Type your message..."
                                    class="flex-1 border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :disabled="sending"
                                />
                                <button 
                                    type="submit"
                                    :disabled="!newMessage.trim() || sending"
                                    class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 disabled:bg-gray-300 disabled:cursor-not-allowed"
                                >
                                    Send
                                </button>
                            </form>
                        </div>
                    </div>
                    <div v-else class="flex items-center justify-center h-full text-gray-500">
                        Select a chat room to start messaging
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Room Modal -->
        <div 
            v-if="showCreateRoom"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
            @click.self="showCreateRoom = false"
        >
            <div class="bg-white rounded-lg p-6 w-96">
                <h3 class="text-xl font-bold mb-4">Create New Room</h3>
                <form @submit.prevent="createRoom">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Room Name</label>
                        <input 
                            v-model="newRoomName"
                            type="text"
                            class="w-full border rounded px-3 py-2"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input 
                                v-model="newRoomPrivate"
                                type="checkbox"
                                class="mr-2"
                            />
                            <span class="text-sm">Private Room</span>
                        </label>
                    </div>
                    <div class="flex gap-2">
                        <button 
                            type="submit"
                            class="flex-1 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                        >
                            Create
                        </button>
                        <button 
                            type="button"
                            @click="showCreateRoom = false"
                            class="flex-1 bg-gray-300 px-4 py-2 rounded hover:bg-gray-400"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ChatApp',
    
    data() {
        return {
            chatRooms: [],
            currentRoom: null,
            messages: [],
            newMessage: '',
            sending: false,
            showCreateRoom: false,
            newRoomName: '',
            newRoomPrivate: false,
            username: '',
            showUsernamePrompt: true,
            eventSource: null,
            userIp: '',
            deviceInfo: null,
        };
    },

    mounted() {
        this.loadChatRooms();
        this.promptForUsername();
        this.fetchUserIp();
    },

    beforeUnmount() {
        this.closeEventSource();
    },

    methods: {
        async fetchUserIp() {
            try {
                const response = await axios.get('/api/user-info');
                this.userIp = response.data.ip;
                this.deviceInfo = response.data.device;
                console.log('Device Info:', response.data);
            } catch (error) {
                console.error('Failed to fetch user info:', error);
                this.userIp = 'Unknown';
            }
        },

        promptForUsername() {
            const savedUsername = localStorage.getItem('chatUsername');
            if (savedUsername) {
                this.username = savedUsername;
                this.showUsernamePrompt = false;
            } else {
                const name = prompt('Enter your username:');
                if (name && name.trim()) {
                    this.username = name.trim();
                    localStorage.setItem('chatUsername', this.username);
                    this.showUsernamePrompt = false;
                } else {
                    this.username = 'Anonymous_' + Math.random().toString(36).substring(7);
                    this.showUsernamePrompt = false;
                }
            }
        },

        async loadChatRooms() {
            try {
                const response = await axios.get('/api/chat-rooms');
                this.chatRooms = response.data;
            } catch (error) {
                console.error('Failed to load chat rooms:', error);
            }
        },

        async selectRoom(room) {
            this.closeEventSource();
            this.currentRoom = room;
            this.messages = [];
            await this.loadMessages();
            this.connectToStream();
            this.scrollToBottom();
        },

        async loadMessages() {
            if (!this.currentRoom) return;

            try {
                const response = await axios.get(`/api/chat-rooms/${this.currentRoom.id}/messages`);
                this.messages = response.data;
            } catch (error) {
                console.error('Failed to load messages:', error);
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim() || this.sending || !this.username) return;

            this.sending = true;
            try {
                await axios.post(
                    `/api/chat-rooms/${this.currentRoom.id}/messages`,
                    { 
                        content: this.newMessage,
                        username: this.username
                    }
                );
                
                this.newMessage = '';
            } catch (error) {
                console.error('Failed to send message:', error);
                alert(error.response?.data?.message || 'Failed to send message');
            } finally {
                this.sending = false;
            }
        },

        async createRoom() {
            try {
                const response = await axios.post('/api/chat-rooms', {
                    name: this.newRoomName,
                    is_private: this.newRoomPrivate,
                });
                
                this.chatRooms.unshift(response.data);
                this.showCreateRoom = false;
                this.newRoomName = '';
                this.newRoomPrivate = false;
                this.selectRoom(response.data);
            } catch (error) {
                console.error('Failed to create room:', error);
                alert('Failed to create room');
            }
        },

        async joinRoom() {
            try {
                await axios.post(`/api/chat-rooms/${this.currentRoom.id}/join`, {
                    username: this.username
                });
                await this.loadMessages();
            } catch (error) {
                console.error('Failed to join room:', error);
                alert('Failed to join room');
            }
        },

        async leaveRoom() {
            try {
                await axios.post(`/api/chat-rooms/${this.currentRoom.id}/leave`);
                this.messages = [];
                this.closeEventSource();
            } catch (error) {
                console.error('Failed to leave room:', error);
            }
        },

        connectToStream() {
            if (!this.currentRoom) return;

            // Close existing connection
            this.closeEventSource();

            // Create new EventSource connection to ZMQ stream via SSE
            this.eventSource = new EventSource(`/api/chat-rooms/${this.currentRoom.id}/stream`);

            this.eventSource.onmessage = (event) => {
                try {
                    const message = JSON.parse(event.data);
                    
                    // Check if message already exists (avoid duplicates)
                    const exists = this.messages.some(m => m.id === message.id);
                    if (!exists) {
                        this.messages.push(message);
                        this.$nextTick(() => this.scrollToBottom());
                    }
                } catch (error) {
                    console.error('Failed to parse message:', error);
                }
            };

            this.eventSource.onerror = (error) => {
                console.error('EventSource error:', error);
                // Reconnect after 3 seconds
                this.closeEventSource();
                setTimeout(() => {
                    if (this.currentRoom) {
                        this.connectToStream();
                    }
                }, 3000);
            };
        },

        closeEventSource() {
            if (this.eventSource) {
                this.eventSource.close();
                this.eventSource = null;
            }
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },

        formatTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
        },
    }
};
</script>
