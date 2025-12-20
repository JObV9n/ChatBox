<?php

use App\Http\Controllers\ChatRoomController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// public for now file upload route
Route::post('/upload', [FileUploadController::class, 'upload']);

// Chat Room Routes
Route::middleware('auth:sanctum')->group(function () {
    // Chat Rooms
    Route::apiResource('chat-rooms', ChatRoomController::class);
    Route::post('chat-rooms/{chatRoom}/join', [ChatRoomController::class, 'join']);
    Route::post('chat-rooms/{chatRoom}/leave', [ChatRoomController::class, 'leave']);
    Route::get('chat-rooms/{chatRoom}/online-users', [ChatRoomController::class, 'onlineUsers']);
    
    // Messages
    Route::get('chat-rooms/{chatRoom}/messages', [MessageController::class, 'index']);
    Route::post('chat-rooms/{chatRoom}/messages', [MessageController::class, 'store']);
    Route::get('chat-rooms/{chatRoom}/messages/poll', [MessageController::class, 'poll']);
});
