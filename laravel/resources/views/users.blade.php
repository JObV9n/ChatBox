<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Messenger</title>
    <link rel="stylesheet" href="{{asset('css/chat.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="chat-container">
        <div class="chat-box" id="chat-box">
            <div class="message received">Hello!</div>
            <div class="message sent">Hi there!</div>
        </div>
        <div class="input-box">
            <input type="text" id="message-input" placeholder="Type a message...">
        </div>
    </div>
</div>
<script src="{{asset('js/chat.js')}}"></script>
</body>
</html>
