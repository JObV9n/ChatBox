<!doctype html>
<html lang="en">
<head>
        <title>Chats</title>
    @extends('layouts.head')
</head>

<body>
{{--navbar--}}
@include('layouts.navbar')
<div class="container">
{{--chatUser container--}}
    <div class="chat-container-user">
        <div class="chats">
            <div class="chatUser active">
                <div class="chatUserIcon">
                    <img src="#" alt="">
                </div>
                <div class="chatUserDetails">
                    <span class="chatUsername">Me</span>
                    <span class="lastMessageTime">Today</span>
                </div>
            </div>
            <div class="chatUser">
                <div class="chatUserIcon">P</div>
                <div class="chatUserDetails">
                    <span class="chatUsername">Peter</span>
                    <span class="lastMessageTime">Today</span>
                </div>
            </div>
            <div class="chatUser">
                <div class="chatUserIcon">M</div>
                <div class="chatUserDetails">
                    <span class="chatUsername">Maggie</span>
                    <span class="lastMessageTime">Today</span>
                </div>
            </div>
            <div class="chatUser">
                <div class="chatUserIcon">E</div>
                <div class="chatUserDetails">
                    <span class="chatUsername">Eric</span>
                    <span class="lastMessageTime">Today</span>
                </div>
            </div>
            <div class="chatUser">
                <div class="chatUserIcon">P</div>
                <div class="chatUserDetails">
                    <span class="chatUsername">Paul</span>
                    <span class="lastMessageTime">Today</span>
                </div>
            </div>
            <div class="chatUser">
                <div class="chatUserIcon">M</div>
                <div class="chatUserDetails">
                    <span class="chatUsername">Maggie</span>
                    <span class="lastMessageTime">Today</span>
                </div>
            </div>
            <div class="chatUser">
                <div class="chatUserIcon">E</div>
                <div class="chatUserDetails">
                    <span class="chatUsername">Eric</span>
                    <span class="lastMessageTime">Today</span>
                </div>
            </div>
            <div class="chatUser">
                <div class="chatUserIcon">P</div>
                <div class="chatUserDetails">
                    <span class="chatUsername">Paul</span>
                    <span class="lastMessageTime">Today</span>
                </div>
            </div>
        </div>

    </div>
{{--chatbox container--}}
    <div class="chat-container">
        <div class="chat-box" id="chat-box">
            <div class="message received">Hello Sachin!</div>
            <div class="message sent">Hi there!</div>
        </div>
        <div class="input-box">
            <label for="message-input">Text Message</label>
            <input type="text" id="message-input" placeholder="Type a message...">
            <button id="send-button">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>
<script src="{{asset('js/chat.js')}}"> </script>
</body>
</html>
