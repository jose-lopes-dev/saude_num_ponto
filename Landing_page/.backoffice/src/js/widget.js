document.addEventListener("DOMContentLoaded", function () {

    const btn = document.getElementById("chat-btn");
    const box = document.getElementById("chatbot-box");

    btn.addEventListener("click", function () {
        box.style.display = (box.style.display === "flex") ? "none" : "flex";
    });

});
