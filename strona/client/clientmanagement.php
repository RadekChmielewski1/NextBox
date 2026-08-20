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
        <link rel="stylesheet" href="style/admin.css">
        <link rel="stylesheet" href="style/panel.css">
        <link rel="stylesheet" href="style/delivery.css">
        <title>NextBox</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
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
        <h1 class="text-center text-light">CLIENT MANAGEMENT</h1>
    </main>
    <div class="w-75 container">
    <table class="text-center table table-striped table-hover table-bordered table-dark text-white" id="tabelaUzytkownikow">
        <thead class="thead-warnig text-dark">
        <tr>
            <th>User ID</th>
            <th>Login</th>
            <th>Name</th>
            <th>Surname</th>
            <th>Type</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT Uzytkownicy.id_uzytkownika, Uzytkownicy.login, Uzytkownicy.imie, Uzytkownicy.nazwisko, Uzytkownicy.rola FROM Uzytkownicy";
        $result = @$conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id_uzytkownika']}</td>";
                echo "<td>{$row['login']}</td>";
                echo "<td>{$row['imie']}</td>";
                echo "<td>{$row['nazwisko']}</td>";
                echo "<td>{$row['rola']}</td>";
                echo
                    '<td>
                        <div  class="d-flex gap-2 justify-content-center">
                                <button class="btn btn-warning rounded-pill px-3" type="button">
                                    <i class="bi bi-lock-fill" style="color: white;"></i>
                                </button>
                                <button class="btn btn-danger rounded-pill px-3" type="button">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                                <button class="btn btn-danger rounded-pill px-3" type="button" >
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                        </div>
                    </td>';
                echo "</tr>";
            }
        }
        ?>
        </tbody>
    </table>
    </div>

    <!-- MODAL USUWANIA UZYTKOWNIKA -->
    <div class="modal fade" id="deleteModal" tabindex="1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="modal-body text-center text-white p-4">
                    <h4 class="mb-4">Did you collect your parcel?</h4>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-success btn-lg px-5" onclick="confirmYes()">YES</button>
                        <button type="button" class="btn btn-danger btn-lg px-5" onclick="confirmNo()">NO</button>
                    </div>
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">← BACK</button>
                </div>
            </div>
        </div>
    </div>
    </body>
    <script src="scripts/usermanagement.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabelaUzytkownikow').DataTable({
                order: [[0, 'asc']],
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