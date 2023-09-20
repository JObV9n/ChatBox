<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Messenger</title>
    <link rel="stylesheet" href="{{asset('css/chat.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<div class="container">
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
