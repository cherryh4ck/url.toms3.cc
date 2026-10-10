const preload = new Image();
preload.src = "resources/icons/door_open.png";
const button = document.getElementById("door-button");
const image = document.getElementById("door-image");

button.addEventListener("mouseover", () => {
    image.src = "resources/icons/door_open.png"
});

button.addEventListener("mouseleave", () => {
    image.src = "resources/icons/door.png"
});