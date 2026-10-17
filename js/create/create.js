const form = document.getElementById("form");
const url0 = document.getElementById("url");
const alias = document.getElementById("alias");
const token = document.getElementById("alias");

const create = document.getElementById("submit-button");

form.addEventListener("submit", (e) => {
    e.preventDefault();
    if (!url0.value.trim()) {
        createCustomPopup("Error", "Please complete all of the necessary fields.");
        return;
    }

    if(!url0.value.startsWith("https://")) {
        createCustomPopup("Error", "URL is invalid.");
        return;
    }

    fetch("api/v1/redirect/create.php", {
        method: "POST",
        body: new FormData(form)
    })
    .then(async response => {
        const data = await response.json();
        //if (response.ok) {
        //    window.location.href = "view.php";
        //    return;
        //}
    })
    .catch(error => {
        createCustomPopup("Error", "There was an unexpected error.");
    })
})

create.addEventListener("click", () => {
    form.requestSubmit();
})