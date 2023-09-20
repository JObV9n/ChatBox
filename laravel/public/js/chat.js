
const messageInput = document.getElementById('message-input');
const chatBox = document.getElementById('chat-box');
const sendButton= document.getElementById('send-button');
sendButton.addEventListener('click', function(event) {
        const messageText = messageInput.value.trim();
        if (messageText !== '') {
            appendMessage('sent', messageText);
            messageInput.value = '';
        }
});

messageInput.addEventListener('keypress',(event)=>{
    if (event.key === 'Enter'){ //if Enter is also pressed the message is send in the Div
        sendButton.click();
    }
});
function appendMessage(type, text) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', type);
    messageDiv.textContent = text;
    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
}
