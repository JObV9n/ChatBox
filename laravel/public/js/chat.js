const messageInput = document.getElementById('message-input');
const chatBox = document.getElementById('chat-box');
const sendButton = document.getElementById('send-button');

sendButton.addEventListener('click', function () {
    const messageText = messageInput.value.trim();
    if (messageText !== '') {
        appendMessage('sent', messageText);
        // messageInput.value = '';
        saveToLocalStorage('sent', messageText);
    }
});

messageInput.addEventListener('keypress', (event) => {
    if (event.key === 'Enter') { //if Enter is also pressed the message is send in the Div
        sendButton.click();
        messageInput.value= '';
    }
});

function appendMessage(type, text) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', type);
    messageDiv.textContent = text;
    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
}

//function to save the  message in local storage
function saveToLocalStorage() {
    let type = 'sent';
    let text = JSON.stringify(messageInput.value.trim());
    let finalText = JSON.parse(text);
    const messageLocal = JSON.parse(localStorage.getItem('local')) || [];
    messageLocal.push({type, finalText});
    localStorage.setItem('local', JSON.stringify(messageLocal));
}


//function to load messages in LocalStorage so that on every refresh or reload the  messages are not deleted
function loadFromLocalStorage() {
    const savedMessages = JSON.parse(localStorage.getItem('local')) || [];
    savedMessages.forEach(function (message) {
        appendMessage(message.type, message.finalText);
    });
}

//EventListener to listen when the window is reloaded or refreshed
window.addEventListener('load', loadFromLocalStorage);
