const logout_button = document.getElementById("logout-button");

logout_button.addEventListener("click", () => {
    fetch("api/v1/auth/logout.php")
    .then(async response => {
        if (response.ok) {
            window.location.href = "index.php";
        }
    })
    .catch(error => {
        createCustomPopup("Error", "There was an unexpected error.");
    })
}); 