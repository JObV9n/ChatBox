<?php

use App\Http\Controllers\ChatRoomController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ZmqStreamController;
use App\Http\Controllers\UserInfoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// User Info
Route::get('/user-info', [UserInfoController::class, 'info']);

// File upload route
Route::post('/upload', [FileUploadController::class, 'upload']);

// Chat Room Routes (Anonymous access with room codes)
Route::apiResource('chat-rooms', ChatRoomController::class);
Route::post('chat-rooms/{chatRoom}/join', [ChatRoomController::class, 'join']);
Route::post('chat-rooms/{chatRoom}/leave', [ChatRoomController::class, 'leave']);
Route::get('chat-rooms/{chatRoom}/online-users', [ChatRoomController::class, 'onlineUsers']);

// Messages
Route::get('chat-rooms/{chatRoom}/messages', [MessageController::class, 'index']);
Route::post('chat-rooms/{chatRoom}/messages', [MessageController::class, 'store']);
Route::get('chat-rooms/{chatRoom}/messages/poll', [MessageController::class, 'poll']);

// SSE Stream for real-time messages via ZMQ
Route::get('chat-rooms/{chatRoom}/stream', [ZmqStreamController::class, 'stream']);
