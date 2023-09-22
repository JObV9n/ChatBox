<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chats</title>
    <link rel="stylesheet" href={{asset('css/chat.css')}}>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
@yield('nav-content')
</body>
</html>
