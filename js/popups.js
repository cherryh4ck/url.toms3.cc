const popup = document.getElementById("popup");
const popup_title = document.getElementById("popup-title");
const popup_body_content = document.getElementById("popup-body-content");
const popup_actions = popup ? popup.querySelector(".popup-actions") : document.querySelector(".popup-actions");
let popup_open = false;
let popup_body_button = document.getElementById("popup-button");

function openExternalWindow(url) {
    window.open(url);
}

function openPopup() {
    popup_open = true;
    popup.style.display = "flex";
}

function closePopup() {
    popup.style.display = "none";
    if (popup_body_button) {
        popup_body_button.removeEventListener("click", openExternalWindow, false);
    }
    popup_body_content.replaceChildren();
    if (popup_actions) {
        popup_actions.innerHTML = '<button id="popup-button" class="popup-button" onclick="closePopup()">OK</button>';
        popup_body_button = document.getElementById("popup-button");
    } else if (popup_body_button) {
        const newButton = popup_body_button.cloneNode(true);
        popup_body_button.parentNode.replaceChild(newButton, popup_body_button);
        popup_body_button = newButton;
    }
    popup_open = false;
}

function createCustomPopup(title, text) {
    if (popup_open) return;
    popup_title.textContent = title;
    const body_text = document.createElement("p");
    body_text.innerHTML = text;
    popup_body_content.appendChild(body_text);
    openPopup();
} 

function createConfirmPopup(title, text, onConfirm, confirmText = "Delete", cancelText = "Cancel") {
    if (popup_open) return;
    popup_title.textContent = title;
    const body_text = document.createElement("p");
    body_text.innerHTML = text;
    popup_body_content.appendChild(body_text);

    if (popup_actions) {
        popup_actions.replaceChildren();

        const cancelButton = document.createElement("button");
        cancelButton.className = "popup-button";
        cancelButton.textContent = cancelText;
        cancelButton.onclick = closePopup;

        const confirmButton = document.createElement("button");
        confirmButton.className = "popup-button";
        confirmButton.textContent = confirmText;
        confirmButton.addEventListener("click", () => {
            closePopup();
            if (typeof onConfirm === "function") {
                onConfirm();
            }
        });

        popup_actions.appendChild(cancelButton);
        popup_actions.appendChild(confirmButton);
    }

    openPopup();
}

function openDonatePopup() {
    popup_title.textContent = "Donations";
    const body_text = document.createElement("p");
    body_text.innerHTML = 'Donation links:<br><a href="https://steamcommunity.com/tradeoffer/new/?partner=1781797079&token=1fTVqckz" target="_blank">Steam Trade</a><br><a href="https://paypal.me/tomatito32k" target="_blank">PayPal</a>';
    popup_body_content.appendChild(body_text);
    openPopup();
}

function openDiscordPopup() {
    popup_title.textContent = "Warning!";
    const body_text = document.createElement("p");
    body_text.innerHTML = 'Please note that the server may <b>not</b> be suitable for all users.<br><br>By clicking <b>OK</b>, you agree to proceed.';
    popup_body_content.appendChild(body_text);
    popupController = new AbortController();
    popup_body_button.addEventListener("click", () => {
        openExternalWindow("https://discord.gg/sACFWrPDkF");
    });
    openPopup();
}