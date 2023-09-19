const messageInput = document.getElementById('message-input');
const chatBox = document.getElementById('chat-box');

messageInput.addEventListener('keyup', function(event) {
    if (event.key === 'Enter') {
        const messageText = messageInput.value.trim();
        if (messageText !== '') {
            appendMessage('sent', messageText);
            messageInput.value = '';
        }
    }
});

function appendMessage(type, text) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', type);
    messageDiv.textContent = text;
    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
}
