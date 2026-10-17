const preload = new Image();
preload.src = "resources/icons/lock_open.png";
const button = document.getElementById("lock-button");
const image = document.getElementById("lock-image");
const form = document.getElementById("form");

//data
const formData = document.getElementById("form");
const username = document.getElementById("username");
const password = document.getElementById("password");

form.addEventListener("submit", e => {
    e.preventDefault();
    fetch("api/v1/auth/login.php", {
        method: "POST",
        body: new FormData(formData)
    })
    .then(async response => {
        const data = await response.json();
        if (response.ok) {
            window.location.href = "view.php";
            return;
        }

        switch (response.status) {
            case 400:
                createCustomPopup("Error", "Invalid data.");
                break;
            case 401:
                createCustomPopup("Error", "Credentials are incorrect.");
                break;
            default:
                createCustomPopup("Error", "Unknown error.");
                break;
        }
    })
    .catch(error => {
        createCustomPopup("Error", "fasf");
    })
});

button.addEventListener("mouseover", () => {
    image.src = "resources/icons/lock_open.png"
});

button.addEventListener("mouseleave", () => {
    image.src = "resources/icons/lock.png"
});

function submitForm() {
    if (!username.value.trim() || !password.value.trim()) {
        createCustomPopup("Error", "Please complete all of the fields.")
        return;
    }
    form.requestSubmit();
}

button.addEventListener("click", () => {
    submitForm();
});

username.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        submitForm();
    }
})

password.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        submitForm();
    }
});