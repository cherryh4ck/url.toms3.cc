const form = document.getElementById("form");
const username = document.getElementById("username");
const password = document.getElementById("password");
const role = document.getElementById("role");

const create = document.getElementById("submit-button");

form.addEventListener("submit", (e) => {
    e.preventDefault();
    if (!username.value.trim() || !password.value.trim()) {
        createCustomPopup("Error", "Please complete all of the necessary fields.");
        return;
    }

    create.disabled = true;
    fetch("api/v1/account/create.php", {
        method: "POST",
        body: new FormData(form)
    })
    .then(async response => {
        const data = await response.json();
        if (response.ok) {
            createCustomPopup("Successful", "Created successfully.");
        }
        else {
            createCustomPopup("Error", data.msg);
        }
    })
    .catch(error => {
        createCustomPopup("Error", "There was an unexpected error.");
    })
    .finally(() => {
        create.disabled = false;
    });
})

create.addEventListener("click", () => {
    form.requestSubmit();
})