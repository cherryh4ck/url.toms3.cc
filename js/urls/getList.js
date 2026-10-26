const previous = document.getElementById("previous-page"); 
const next = document.getElementById("next-page");

const table = document.getElementById("urls");
let page = 0;
let maxOffset = 0;
let requestId = 0;

const button = document.createElement("button");
button.width = 16;
button.height = 16;
button.draggable = false;
button.innerHTML = "<img src='resources/icons/bomb.png'>Delete"; 

function appendRow(item) {
    const row = document.createElement("tr");

    const aliasCell = document.createElement("td");
    aliasCell.textContent = item.alias;

    const urlCell = document.createElement("td");
    urlCell.textContent = item.url;

    const actionsCell = document.createElement("td");
    const deleteButton = button.cloneNode(true);
    deleteButton.dataset.alias = item.alias;
    deleteButton.addEventListener("click", () => {
        const data = new FormData();
        data.append("alias", deleteButton.dataset.alias);
        fetch("api/v1/redirect/delete.php", {
            method: "POST",
            body: data
        })
        .then(async response => {
            const data = await response.json();
            if (response.ok) {
                row.remove();
                calculateMaxOffset();
            }
            else {
                createCustomPopup("Error", data.msg);
            }
        })
        .catch(error => {
            createCustomPopup("Error", "There was an unexpected error.");
        })
    });
    actionsCell.appendChild(deleteButton);

    row.append(aliasCell, urlCell, actionsCell);
    table.appendChild(row);
}

function calculateMaxOffset() {
    fetch("api/v1/redirect/count.php")
    .then(async response => {
        const data = await response.json();
        if (response.ok) {
            maxOffset = Math.ceil(data.total / 3) - 1;
            if (maxOffset < 1) maxOffset = 0;
            console.log(maxOffset);
            if (page > maxOffset) page = maxOffset;
            loadPage(page);
        }
        else {
            createCustomPopup("Error", "Error calculating max offset. Contact an administrator.");
        }
    })
    .catch(error => {
        createCustomPopup("Error", "There was an unexpected error.");
    })
}

function loadPage(page) {
    const currentRequest = ++requestId;
    previous.disabled = true;
    next.disabled = true;

    table.querySelectorAll("tr:has(td)").forEach(row => row.remove());
    const data = new FormData();
    data.append("offset", page);
    
    fetch("api/v1/redirect/get.php", {
        method: "POST",
        body: data
    })
    .then(async response => {
        const data = await response.json();
        if (currentRequest !== requestId) return;
        if (response.ok) {
            data.forEach(appendRow)
        }
        else {
            createCustomPopup("Error", data.msg);
        }
    })
    .catch(error => {
        createCustomPopup("Error", "There was an unexpected error.");
    })
    .finally(() => {
        if (currentRequest !== requestId) return;
        if (page == 0) {
            previous.disabled = true;
        } else {
            previous.disabled = false;
        }

        if (page >= maxOffset) {
            next.disabled = true;
        }
        else {
            next.disabled = false;
        }
    });
}

next.addEventListener("click", () => {
    page = page + 1;
    loadPage(page);
});

previous.addEventListener("click", () => {
    if (page > 0) {
        page = page - 1;
        loadPage(page);
    }
});

calculateMaxOffset();