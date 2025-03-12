document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("userInput").focus();
});

function handleKeyPress(event) {
    if (event.key === "Enter") {
        sendMessage();
    }
}

function sendMessage() {
    let userInput = document.getElementById("userInput").value.trim();
    if (userInput === "") return;

    // Display user message
    let chatBox = document.getElementById("chatBox");
    chatBox.innerHTML += `<div class="user-message"><b>You:</b> ${userInput}</div>`;
    document.getElementById("userInput").value = "";

    // Show typing animation
    chatBox.innerHTML += `<div class="bot-message typing">AI is typing...</div>`;
    chatBox.scrollTop = chatBox.scrollHeight;

    // Send to backend
    fetch("backend.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ userMessage: userInput })
    })
    .then(response => response.json())
    .then(data => {
        document.querySelector(".typing").remove();  // Remove typing animation
        chatBox.innerHTML += `<div class="bot-message"><b>AI:</b> ${data.reply}</div>`;
        chatBox.scrollTop = chatBox.scrollHeight;
    })
    .catch(error => {
        document.querySelector(".typing").remove();
        chatBox.innerHTML += `<div class="bot-message error">Error: Unable to reach AI.</div>`;
    });
}

