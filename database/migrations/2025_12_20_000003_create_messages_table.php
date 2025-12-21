<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_room_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('username', 50)->nullable();
            $table->text('content');
            $table->timestamps();
            
            $table->index('chat_room_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
