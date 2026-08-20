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

// Pobranie listy użytkowników
$uzytkownicy = [];
$result = $conn->query("SELECT id_uzytkownika, imie, nazwisko, login FROM Uzytkownicy ORDER BY nazwisko, imie");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $uzytkownicy[] = $row;
    }
}

// Pobranie wolnych skrytek
$skrytki = [];
$result = $conn->query("SELECT id_skrytki, rozmiar, status FROM Paczkomat");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $skrytki[] = $row;
    }
}

// Obsługa formularza
$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_uzytkownika = $_POST['id_uzytkownika'] ?? '';
    $nadawca = $_POST['nadawca'] ?? '';
    $id_skrytki = $_POST['id_skrytki'] ?? null;
    $status = $_POST['status'] ?? 'NADANA';
    
    // Generowanie unikalnego nr_zamowienia (10 cyfr)
    $nr_zamowienia = mt_rand(1000000000, 9999999999);
    
    // Generowanie 6-cyfrowego kodu odbioru
    $kod_odbioru = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    
    // Walidacja
    if (empty($id_uzytkownika) || empty($nadawca)) {
        $message = "Please fill in all required fields!";
        $messageType = "danger";
    } else {
        // Jeśli skrytka pusta, ustaw NULL
        if (empty($id_skrytki)) {
            $id_skrytki = null;
        }
        
        // Wstawienie do bazy danych
        if ($id_skrytki === null) {
            $stmt = $conn->prepare("INSERT INTO Paczki (nr_zamowienia, id_uzytkownika, id_skrytki, status, nadawca, kod_odbioru, data_nadania) VALUES (?, ?, NULL, ?, ?, ?, NOW())");
            $stmt->bind_param("iisss", $nr_zamowienia, $id_uzytkownika, $status, $nadawca, $kod_odbioru);
        } else {
            $stmt = $conn->prepare("INSERT INTO Paczki (nr_zamowienia, id_uzytkownika, id_skrytki, status, nadawca, kod_odbioru, data_nadania) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("iiisss", $nr_zamowienia, $id_uzytkownika, $id_skrytki, $status, $nadawca, $kod_odbioru);
            
            // Aktualizuj status skrytki na ZAJETA jeśli paczka jest W_PACZKOMACIE
            if ($status == 'W_PACZKOMACIE') {
                $conn->query("UPDATE Paczkomat SET status = 'ZAJETA' WHERE id_skrytki = $id_skrytki");
            }
        }
        
        if ($stmt->execute()) {
            $message = "Parcel added successfully!<br>Order number: <strong>" . $nr_zamowienia . "</strong><br>Pickup code: <strong>" . $kod_odbioru . "</strong>";
            $messageType = "success";
        } else {
            $message = "Error adding parcel: " . $stmt->error;
            $messageType = "danger";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Parcel - NextBox</title>
    <link rel="stylesheet" href="style/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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

<main class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card bg-secondary text-white">
                <div class="card-header text-center">
                    <h3>Add New Parcel</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="id_uzytkownika" class="form-label">Recipient (TO)</label>
                            <select class="form-select" id="id_uzytkownika" name="id_uzytkownika" required>
                                <option value="">Select recipient...</option>
                                <?php foreach ($uzytkownicy as $u): ?>
                                    <option value="<?php echo $u['id_uzytkownika']; ?>">
                                        <?php echo $u['imie'] . ' ' . $u['nazwisko'] . ' (' . $u['login'] . ')'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nadawca" class="form-label">Sender (FROM)</label>
                            <input type="text" class="form-control" id="nadawca" name="nadawca" placeholder="e.g. Allegro, Zalando, CCC..." required>
                        </div>

                        <div class="mb-3">
                            <label for="id_skrytki" class="form-label">Locker (optional)</label>
                            <select class="form-select" id="id_skrytki" name="id_skrytki">
                                <option value="">No locker assigned</option>
                                <?php foreach ($skrytki as $s): ?>
                                    <option value="<?php echo $s['id_skrytki']; ?>" <?php echo $s['status'] == 'ZAJETA' ? 'disabled' : ''; ?>>
                                        Locker <?php echo $s['id_skrytki'] . ' (Size: ' . $s['rozmiar'] . ') - ' . $s['status']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="NADANA" selected>SENT (NADANA)</option>
                                <option value="W_PACZKOMACIE">IN LOCKER (W_PACZKOMACIE)</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                Add Parcel
                            </button>
                            <a href="admin.php" class="btn btn-outline-light">
                                Back to Admin Panel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

<?php //if ($messageType == "success"): ?>
<!--<script>-->
<!--    setTimeout(() => {-->
<!--        window.location.href = 'admin.php';-->
<!--    }, 4000);-->
<!--</script>-->
<?php //endif; ?>

</body>
</html>

<?php $conn->close(); ?>