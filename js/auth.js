const preload = new Image();
preload.src = "resources/icons/lock_open.png";
const button = document.getElementById("lock-button");
const image = document.getElementById("lock-image");
const form = document.getElementById("form");

form.addEventListener("submit", e => {
    e.preventDefault();
    alert("big dope");
});

button.addEventListener("mouseover", () => {
    image.src = "resources/icons/lock_open.png"
});

button.addEventListener("mouseleave", () => {
    image.src = "resources/icons/lock.png"
});

button.addEventListener("click", () => {
    form.requestSubmit();
});