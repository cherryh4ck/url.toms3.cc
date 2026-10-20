// TODO: POR TERMINARr
const table = document.getElementById("urls");
let page = 0;

const button = document.createElement("button");
button.width = 16;
button.height = 16;
button.draggable = false;
button.innerHTML = "<img src='resources/icons/bomb.png'>Delete"; 

function loadPage(page) {
    table.querySelectorAll("tr:has(td)").forEach(row => row.remove());
    const data = new FormData();
    data.append("offset", page);
    
    fetch("api/v1/redirect/get.php", {
        method: "POST",
        body: data
    })
    .then(async response => {
        const data = await response.json();
        if (response.ok) {
            console.log(data);
        }
        else {
            createCustomPopup("Error", data.msg);
        }
    })
    .catch(error => {
        createCustomPopup("Error", "There was an unexpected error.");
    })
    .finally(() => {
        
    });
}

loadPage(page);