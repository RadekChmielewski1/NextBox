<?php
session_start();

if ($_SESSION["rola"] != "ADMIN") {
    header("Location: index.php");
    exit;
}

require_once "config.php";
$conn = @new mysqli($servername, $username, $password, $dbname);
if (@$conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
<html lang="pl">
<head>
    <title>Inteligentny paczkomat</title>
    <link rel="stylesheet" href="style/admin.css">
    <link rel="stylesheet" href="style/panel.css">
    <link rel="stylesheet" href="style/delivery.css">
    <title>NextBox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>
</head>
<body class="bg-dark">
<header class="p-3 mb-3 text-bg-dark">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-evenly mb-md-0">
                <li>
                    <a href="index.php" class="nav-link px-2 text-secondary">Home</a>
                </li>
                <li>
                    <a href="aboutus.php" class="nav-link px-2 text-white">About us</a>
                </li>
                <li>
                    <a href="map.php" class="nav-link px-2 text-white">Find your NextBox</a>
                </li>
                <li>
                    <a href="contact.php" class="nav-link px-2 text-white">Contact</a>
                </li>
            </ul>
            <?php
            if($_SESSION['rola'] == 'KURIER' || $_SESSION['rola'] == 'ADMIN'){
                echo "
                <div class='text-end'>
                    <a class='btn btn-warning me-1' href='delivery.php'>Delivery panel</a>
                </div>
            ";
            }
            if($_SESSION['rola'] == 'ADMIN'){
                echo "
                <div class='text-end'>
                    <a class='btn btn-danger me-0' href='admin.php'>Admin panel</a>
                    <a class='btn btn-success me-1' href='addParcel.php'>Add parcel</a> 
                </div>
                ";
                }
            ?>
            <div class="text-end">
                <a class="btn btn-warning" href="logout.php">Log out</a>
            </div>
        </div>
    </div>
</header>
<main>
    <h1 class="text-center text-light">ADMIN PANEL</h1>
    <div>
        <table class="text-center table table-striped table-hover table-bordered table-dark text-white" id="tabelaAdmina">
            <thead class="thead-warnig text-dark">
            <tr>
                <th scope="col">TRACKING ID</th>
                <th scope="col">TO</th>
                <th scope="col">FROM</th>
                <th scope="col">LOCKER</th>
                <th scope="col">DATE</th>
                <th scope="col">STATUS</th>
                <th scope="col">ACTIONS</th>
            </tr>
            </thead>
            <tbody id="paczkiTable">
            <?php include "admintable.php"; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- MODAL wynikowy usunięcia -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-danger">
            <div class="modal-body text-center text-white p-4">
                <img src="img/rejected.png" alt="empty" width="140" class="mb-4 opacity-75">
                <h3 class="mt-3">Order deleted!</h3>
            </div>
        </div>
    </div>
</div>

<!-- MODAL wynikowy zmiany statusu -->
<div class="modal fade" id="ChangeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-success">
            <div class="modal-body text-center text-white p-4">
                <img src="img/ChangeStatus.png" alt="empty" width="140" class="mb-4 opacity-75">
                <h3 class="mt-3">Order status is changed!</h3>
            </div>
        </div>
    </div>
</div>

<!-- MODAL informacyjny zwrotu do nadawcy -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-info">
            <div class="modal-body text-center text-white p-4">
                <img src="img/delivery-truck.png" alt="empty" width="140" class="mb-4 opacity-75">
                <h3 class="mt-3">Parcel will return to sender</h3>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function reloadTable() {
    fetch("admintable.php")
        .then(response => response.text())
        .then(html => {
            document.getElementById("paczkiTable").innerHTML = html;
        })
        .catch(error => console.error("Błąd przy odświeżaniu tabeli:", error));
}

function deleteParcel(packageId) {
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', packageId);

    fetch('admintable.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                new bootstrap.Modal(document.getElementById('deleteModal')).show();
                setTimeout(() => location.reload(), 1500);
            }
        })
        .catch(error => console.error("Błąd:", error));
}

function changeStatus(packageId, packageStatus) {
    let newStatus;

    if (packageStatus === 'W_PACZKOMACIE') {
        newStatus = 'NADANA';
    } else if (packageStatus === 'NADANA') {
        newStatus = 'W_PACZKOMACIE';
    } else {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'change_status');
    formData.append('id', packageId);
    formData.append('status', newStatus);

    fetch('admintable.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                new bootstrap.Modal(document.getElementById('ChangeModal')).show();
                setTimeout(() => location.reload(), 1500);
            }
        })
        .catch(error => console.error("Błąd:", error));
}

function showReturnModal() {
    new bootstrap.Modal(document.getElementById('returnModal')).show();
}
</script>
</body>
<script>
    $(document).ready(function() {
        $('#tabelaAdmina').DataTable({
            order: [[0, 'asc']],

            // Wyłączenie sortowania dla kolumny z przyciskami (ACTION)
            columnDefs: [
                { orderable: false, targets: 6 }
            ],
            language: {
                search: "Search: ",
                lengthMenu: "Number of _MENU_ packages",
                info: "_START_ out of _END_ from _TOTAL_ packages",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    });
</script>

</html>


<?php $conn->close()?>