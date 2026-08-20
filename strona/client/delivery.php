<?php
session_start();

if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
    header("Location: index.php");
    exit;
}

$locker_id = $_SESSION['locker_id'] ?? 1; 

require_once "config.php";
$conn = @new mysqli($servername, $username, $password, $dbname);
if (@$conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] === 'confirm_yes') {
        $package_id = intval($_POST['package_id']);
        $locker_id = intval($_POST['locker_id']);
    
                $sql = "UPDATE Paczki SET status = 'W_PACZKOMACIE', id_skrytki = ? WHERE id_paczki = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $locker_id, $package_id);
    
        if ($stmt->execute()) {
             echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
         $stmt->close();
    exit;
}
    
    if ($_POST['action'] === 'confirm_no') {
        echo json_encode(['success' => true]);
        exit;
    }
}

?>
    <html lang="pl" xmlns="http://www.w3.org/1999/html">
    <head>
        <title>Inteligentny paczkomat</title>
        <link rel="stylesheet" href="style/panel.css">
        <link rel="stylesheet" href="style/delivery.css">
        <title>NextBox</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    </head>
    <body class="bg-dark" onload="startTime()">
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
                if($_SESSION['rola'] == 'KURIER'){
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
                <a class='btn btn-warning me-1' href='delivery.php'>Delivery panel</a>
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
        <h1 class="text-center text-light">DELIVERY PANEL</h1>
        <div class="container">
            <h1 class="text-center">LIST OF ORDERS</h1>
                <table class="text-center table table-striped table-hover table-bordered table-dark text-white" id="tabelaZamowien">
                    <thead class="thead-warnig text-dark">
                        <tr>
                            <th>PACKAGE NUMBER</th>
                            <th>ORDER NUMBER</th>
                            <th>SENDER</th>
                            <th>RECIPIENT</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $conn = @new mysqli($servername, $username, $password, $dbname); //połączenie tabeli z baza danych
                    if (@$conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $sql = "
                            SELECT 
                                Paczki.id_paczki,
                                Paczki.nr_zamowienia,
                                Paczki.nadawca,
                                CONCAT(Uzytkownicy.imie, ' ', Uzytkownicy.nazwisko) AS Odbiorca,
                                Paczki.status
                            FROM Paczki 
                            JOIN Uzytkownicy ON Paczki.id_uzytkownika = Uzytkownicy.id_uzytkownika
                            LEFT JOIN Paczkomat ON Paczki.id_skrytki = Paczkomat.id_skrytki    
                            ";
                    $result = @$conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['id_paczki']}</td>";
                            echo "<td>{$row['nr_zamowienia']}</td>";
                            echo "<td>{$row['nadawca']}</td>";
                            echo "<td>{$row['Odbiorca']}</td>";

                            if ($row["status"] == "W_PACZKOMACIE") {
                                echo "<td>IN LOCKER</td>";
                            } else {
                                echo "<td>ON THE WAY</td>";
                            }
                            echo "<td>";
                            if ($row["status"] == "NADANA") {
                                echo   "<button type='button' class='btn btn-warning px-4' 
                                        onclick='openLocker(" . $locker_id . ")'>
                                    Open
                                </button>";
                                    }
                            else{
                                echo "<button class='btn btn-danger px-3' onclick='showSupport()'>Support</button>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }

                    
                    ?>
                    </tbody>
                </table>
        </div>
    </main>

    <!-- MODAL POTWIERDZENIA Dostarczenia -->
    <div class="modal fade" id="confirmDeliveryModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="modal-body text-center text-white p-4">
                    <h4 class="mb-4">Did you put the parcel into the box?</h4>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-success btn-lg px-5" onclick="confirmYes()">YES</button>
                        <button type="button" class="btn btn-danger btn-lg px-5" onclick="confirmNo()">NO</button>
                    </div>
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">← BACK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SUKCESU dostarczenia -->
    <div class="modal fade" id="deliveryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-success">
                <div class="modal-body text-center text-white p-4">
                    <h3 class="mt-3">Thank you!</h3>
                    <p>Move to the next order!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PONOWNEGO OTWARCIA by dostarczyc -->
    <div class="modal fade" id="noDeliveryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-danger">
                <div class="modal-body text-center text-white p-4">
                    <h3 class="mt-3">Box will open again</h3>
                    <p>Please put the order into the box this time!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SUPPORTU - Wizytówka kontaktowa -->
    <div class="modal fade" id="supportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-warning">
                <div class="modal-body text-center text-white p-4">
                    <h1 class="mt-3">Need Help?</h1>
                    <h5>Contact our support team:</h5>
                    <p>Hotline: <a href="tel:+48787329887">+48 787 329 887</a></p>
                    <p>Support: <a href="tel:+48393441282">+48 393 441 282</a></p>
                    <p>Support: <a href="mailto:rc311594@student.polsl.pl">rc311594@student.polsl.pl</a></p>
                </div>
            </div>
        </div>
    </div>
    <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    <script src="scripts/delivery.js"></script>
    <script>
        initPanel("<?php echo $ipArduino ?? ''; ?>");
    </script>
    </body>
    <script>
        $(document).ready(function() {
            $('#tabelaZamowien').DataTable({
                order: [[0, 'asc']],

                // Wyłączenie sortowania dla kolumny z przyciskami (ACTION)
                columnDefs: [
                    { orderable: false, targets: 5 }
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
    <script src="scripts/controlPanel.js" defer></script>
    </html>


<?php $conn->close()?>