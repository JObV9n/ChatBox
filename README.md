# ChatBox
Real-time Chat Application with Laravel and ZeroMQ

## Overview

ChatBox is a real-time chat room application built with Laravel 11, Vue 3, and ZeroMQ. It supports public and private chat rooms, real-time messaging without WebSockets, and user authentication.

## Features

- ✅ User authentication with Laravel Sanctum
- ✅ Public and private chat rooms
- ✅ Real-time messaging using ZeroMQ PUB/SUB pattern
- ✅ Message polling for clients (alternative to SSE)
- ✅ Persistent message storage
- ✅ Room membership management
- ✅ Rate limiting for message sending
- ✅ Authorization policies for room access
- ✅ Vue 3 frontend with Tailwind CSS

## Technology Stack

### Backend
- Laravel 11+
- PHP 8.3+
- Laravel Sanctum (authentication)
- ZeroMQ (php-zmq extension)
- MySQL/PostgreSQL/SQLite

### Frontend
- Vue 3
- Axios
- Tailwind CSS
- Vite

## Prerequisites

1. PHP 8.3 or higher
2. Composer
3. Node.js and npm/bun
4. ZeroMQ library
5. PHP ZMQ extension (php-zmq)

### Installing ZeroMQ

#### Windows
```bash
# Install via Chocolatey
choco install zeromq

# Or download from: https://zeromq.org/download/
```

#### Linux (Ubuntu/Debian)
```bash
sudo apt-get install libzmq3-dev
sudo pecl install zmq-beta
```

#### macOS
```bash
brew install zeromq
pecl install zmq-beta
```

After installing, enable the extension in your `php.ini`:
```ini
extension=zmq
```

## Installation

1. **Clone the repository**
```bash
git clone <repository-url>
cd ChatBox
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node dependencies**
```bash
npm install
# or
bun install
```

4. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure database**
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chatbox
DB_USERNAME=root
DB_PASSWORD=
```

6. **Configure ZeroMQ endpoints (optional)**
Add to `.env`:
```env
ZMQ_PUBLISHER_ENDPOINT=tcp://127.0.0.1:5555
ZMQ_SUBSCRIBER_ENDPOINT=tcp://127.0.0.1:5555
```

7. **Run migrations**
```bash
php artisan migrate
```

8. **Build frontend assets**
```bash
npm run build
# or for development
npm run dev
```

## Running the Application

You need to run multiple processes:

### 1. Laravel Application
```bash
php artisan serve
```

### 2. Queue Worker (if using queues)
```bash
php artisan queue:work
```

### 3. ZeroMQ Subscriber
```bash
php artisan zmq:subscribe
```

### 4. Frontend Development Server (optional)
```bash
npm run dev
```

### Using Supervisor (Production)

Create a supervisor configuration file `/etc/supervisor/conf.d/chatbox.conf`:

```ini
[program:chatbox-zmq-subscriber]
process_name=%(program_name)s
command=php /path/to/chatbox/artisan zmq:subscribe
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/chatbox/storage/logs/zmq-subscriber.log

[program:chatbox-queue-worker]
process_name=%(program_name)s
command=php /path/to/chatbox/artisan queue:work --tries=3
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/chatbox/storage/logs/queue-worker.log
```

Then:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start chatbox-zmq-subscriber:*
sudo supervisorctl start chatbox-queue-worker:*
```

## API Endpoints

### Authentication
- `POST /api/register` - Register new user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user

### Chat Rooms
- `GET /api/chat-rooms` - List all accessible chat rooms
- `POST /api/chat-rooms` - Create new chat room
- `GET /api/chat-rooms/{id}` - Get chat room details
- `PUT /api/chat-rooms/{id}` - Update chat room
- `DELETE /api/chat-rooms/{id}` - Delete chat room
- `POST /api/chat-rooms/{id}/join` - Join a chat room
- `POST /api/chat-rooms/{id}/leave` - Leave a chat room
- `GET /api/chat-rooms/{id}/online-users` - Get online users

### Messages
- `GET /api/chat-rooms/{id}/messages` - Get messages (with pagination)
- `POST /api/chat-rooms/{id}/messages` - Send a message
- `GET /api/chat-rooms/{id}/messages/poll` - Poll for new messages

## Usage

1. Register/Login to the application
2. Navigate to `/chat` route
3. Create a new chat room or join an existing one
4. Start sending messages!

## Message Flow

1. User sends message via HTTP POST request
2. Controller validates and stores message in database
3. Message is published to ZeroMQ PUB socket with topic `chat.room.{roomId}`
4. ZMQ subscriber process receives the message
5. Subscriber can forward to connected clients or store for polling
6. Clients poll for new messages every 2 seconds (configurable)

## Architecture

### ZeroMQ Pattern
- **Publisher**: Laravel application publishes messages to ZMQ
- **Subscriber**: Long-running process subscribes to topics
- **Topic Format**: `chat.room.{roomId}`
- **Message Format**: JSON

### Authorization
- Public rooms: Anyone can view and join
- Private rooms: Only members can view and send messages
- Room creators: Can update and delete their rooms

### Rate Limiting
- 10 messages per minute per user per room
- Prevents spam and abuse

## Security Considerations

- All routes protected with `auth:sanctum` middleware
- Input validation on all message content
- XSS protection via Laravel's blade escaping
- CSRF protection enabled
- Rate limiting on message sending
- Authorization policies for room access

## Testing

```bash
php artisan test
```

## Troubleshooting

### ZMQ Extension Not Found
- Ensure php-zmq is installed: `php -m | grep zmq`
- Check php.ini has `extension=zmq`

### Messages Not Appearing
- Verify ZMQ subscriber is running: `php artisan zmq:subscribe`
- Check logs in `storage/logs/`

### Permission Denied Errors
- Ensure storage and cache directories are writable:
```bash
chmod -R 775 storage bootstrap/cache
```

## Future Enhancements

- Server-Sent Events (SSE) support
- File upload in messages
- Message reactions and threads
- User typing indicators
- Message search functionality
- Multiple ZMQ subscribers for scalability
- Redis integration for better performance
- WebSocket support option

## License

This project is open-sourced software licensed under the MIT license.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request. 
