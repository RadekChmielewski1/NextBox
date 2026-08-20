<?php
session_start();

if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
    header("Location: index.php");
    exit;
}

require_once "config.php";
$conn = @new mysqli($servername, $username, $password, $dbname);
if (@$conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obsługa AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] === 'confirm_yes') {
        $package_id = intval($_POST['package_id']);
        $locker_id = intval($_POST['locker_id']);
        
        $stmt = $conn->prepare("UPDATE Paczki SET status = 'ODEBRANA' WHERE id_paczki = ?");
        $stmt->bind_param("i", $package_id);
        $stmt->execute();
        
        if ($locker_id) {
            $stmt2 = $conn->prepare("UPDATE Paczkomat SET status = 'WOLNA' WHERE id_skrytki = ?");
            $stmt2->bind_param("i", $locker_id);
            $stmt2->execute();
        }
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($_POST['action'] === 'confirm_no') {
        echo json_encode(['success' => true]);
        exit;
    }
}
?>

<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>NextBox - Inteligentny paczkomat</title>
    <link rel="stylesheet" href="style/panel.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-dark">
<header class="p-3 mb-3 text-bg-dark">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-evenly mb-md-0">
                <li><a href="index.php" class="nav-link px-2 text-secondary">Home</a></li>
                <li><a href="aboutus.php" class="nav-link px-2 text-white">About us</a></li>
                <li><a href="map.php" class="nav-link px-2 text-white">Find your NextBox</a></li>
                <li><a href="contact.php" class="nav-link px-2 text-white">Contact</a></li>
            </ul>
            <?php
            if($_SESSION['rola'] == 'KURIER' || $_SESSION['rola'] == 'ADMIN'){
                echo "<div class='text-end'><a class='btn btn-warning me-1' href='delivery.php'>Delivery panel</a></div>";
            }
            if($_SESSION['rola'] == 'ADMIN'){
                echo "<div class='text-end'><a class='btn btn-danger me-1' href='admin.php'>Admin panel</a></div>";
            }
            ?>
            <div class="text-end">
                <a class="btn btn-warning" href="logout.php">Log out</a>
            </div>
        </div>
    </div>
</header>

<h1 class="text-center text-white">Good morning <?php echo $_SESSION['imie'];?> ! ☕</h1>

<main class="d-flex justify-content-center align-items-center mx-auto">
    <div class="row w-50 main-box align-items-center">
        <div class="row row-cols-1 row-cols-md-3 mb-3 text-center" id="grid">
<div class=' col-xs-4 col-sm-4 col-md-4 cell gridstrap-cell-hidden card rounded-3 shadow-sm bg-dark text-white '>
                            <h3>Locker: 1</h3>
                            <div class='card-header py-3 bg-warning text-black'>
                                <h4 class='my-0 fw-normal'>" In box "</h4>
                            </div>
                            <div class='card-body'>
                                <ul class='list-unstyled mb-4'>
                                    <li class='fw-bold'>Shipper:</li>
                                    <li>"Allegro"</li>
                                    <li class='fw-bold'>Time left:</li>
                                    <li>5 hours</li>
                                    <li class='fw-bold'>Pick Up code:</li>
                                    <li>"123 432"</li>
                                </ul>
                                <button type='button'
                                        class='w-100 btn btn-lg btn-outline-warning' 
                                        onclick='openLocker(1)'>
                                    Collect
                                </button>
                            </div>
                        </div>
            <div class=' col-xs-4 col-sm-4 col-md-4 cell gridstrap-cell-hidden card rounded-3 shadow-sm bg-dark text-white '>
                            <h3>Locker: 2</h3>
                            <div class='card-header py-3 bg-warning text-black'>
                                <h4 class='my-0 fw-normal'>" In Box "</h4>
                            </div>
                            <div class='card-body'>
                                <ul class='list-unstyled mb-4'>
                                    <li class='fw-bold'>Shipper:</li>
                                    <li>"Botland"</li>
                                    <li class='fw-bold'>Time left:</li>
                                    <li>7 hours</li>
                                    <li class='fw-bold'>Pick Up code:</li>
                                    <li>"123 123"</li>
                                </ul>
                                <button type='button'
                                        class='w-100 btn btn-lg btn-outline-warning' 
                                        onclick='openLocker(2)'>
                                    Collect
                                </button>
                            </div>
                        </div>
            <div class=' col-xs-4 col-sm-4 col-md-4 cell gridstrap-cell-hidden card rounded-3 shadow-sm bg-dark text-white '>
                            <h3>Locker: 3</h3>
                            <div class='card-header py-3 bg-warning text-black'>
                                <h4 class='my-0 fw-normal'>" In box "</h4>
                            </div>
                            <div class='card-body'>
                                <ul class='list-unstyled mb-4'>
                                    <li class='fw-bold'>Shipper:</li>
                                    <li>"OLX"</li>
                                    <li class='fw-bold'>Time left:</li>
                                    <li>12 hours</li>
                                    <li class='fw-bold'>Pick Up code:</li>
                                    <li>"333 222"</li>
                                </ul>
                                <button type='button'
                                        class='w-100 btn btn-lg btn-outline-warning' 
                                        onclick='openLocker(3)'>
                                    Collect
                                </button>
                            </div>
                        </div>
            <?php
            $sql = "SELECT * FROM Paczki WHERE id_uzytkownika = '$_SESSION[id_uzytkownika]' AND status != 'ODEBRANA'";
            $result = @$conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $locker_id = $row['id_skrytki'] ?? 0;
                    echo "
                        <div class=' col-xs-4 col-sm-4 col-md-4 cell gridstrap-cell-hidden card rounded-3 shadow-sm bg-dark text-white '>
                            <h3>Locker: " . ($locker_id ?: 'N/A') . "</h3>
                            <div class='card-header py-3 bg-warning text-black'>
                                <h4 class='my-0 fw-normal'>" . $row['status'] . "</h4>
                            </div>
                            <div class='card-body'>
                                <ul class='list-unstyled mb-4'>
                                    <li class='fw-bold'>Shipper:</li>
                                    <li>" . $row['nadawca'] . "</li>
                                    <li class='fw-bold'>Time left:</li>
                                    <li>-- hours</li>
                                    <li class='fw-bold'>Pick Up code:</li>
                                    <li>" . $row['kod_odbioru'] . "</li>
                                </ul>
                                <button type='button'
                                        class='w-100 btn btn-lg btn-outline-warning' 
                                        onclick='collectPackage(" . $row['id_paczki'] . ", " . $locker_id . ")'>
                                    Collect
                                </button>
                            </div>
                        </div>";
                }
            } else {
                echo "
                    <div class='empty-state d-flex flex-column justify-content-center align-items-center text-center text-white vh-100 w-100'>
                        <img src='img/rejected.png'
                            alt='empty' 
                            width='140' 
                            class='mb-4 opacity-75'>

                    <h1 class='fw-bold mb-3'>No parcels waiting for you</h1>

                    <p class='text-secondary mb-4' style='max-width: 400px'>
                        Once a package is delivered to your locker, it will appear here automatically.
                    </p>

                    <a href='map.php' class='btn btn-warning btn-lg px-4'>
                         Find your nearest NextBox
                    </a>
     </div>
";
                   }
            ?>
        </div>
    </div>
</main>

<!-- MODAL POTWIERDZENIA -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
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

<!-- MODAL SUKCESU -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-success">
            <div class="modal-body text-center text-white p-4">
                <h3 class="mt-3">Thank you!</h3>
                <p>Your parcel has been collected.</p>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PONOWNEGO OTWARCIA -->
<div class="modal fade" id="unsuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-danger">
            <div class="modal-body text-center text-white p-4">
                <h3 class="mt-3">Box will open again</h3>
                <p>Please collect your parcel.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="scripts/panel.js"></script>
<script src="scripts/controlPanel.js" defer></script>
<script>
    initDeliveryPanel("<?php echo $ipArduino ?? ''; ?>");
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('grid');
        var sortable = Sortable.create(el, {
            animation: 250,
            easing: "cubic-bezier(0.65, 0, 0.35, 1)", // Płynniejszy, nowoczesny ruch
        });
    });
</script>
</body>
</html>

<?php $conn->close(); ?>