function reloadTable() {
    fetch("admintable.php")
        .then(response => response.text())
        .then(html => {
            document.getElementById("paczkiTable").innerHTML = html;
        })
        .catch(error => console.error("Błąd przy odświeżaniu tabeli:", error));
}

function deleteParcel(value) {
    fetch("deleteparcel.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + encodeURIComponent(value)
    })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            reloadTable();
            alert("Paczka usunięta!");
        })
        .catch(error => console.error("Błąd:", error));
}

