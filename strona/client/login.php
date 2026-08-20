<?php
session_start();
require_once "config.php";
// login.php
$conn = @new mysqli($servername, $username, $password, $dbname);
if (@$conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login = isset($_POST['username']) ? $_POST['username'] : '';
$haslo = isset($_POST['password']) ? $_POST['password'] : '';

$sql = "select * from Uzytkownicy where login='$login' and haslo='$haslo' ";

if ($result = @$conn->query($sql)) {
    $user = $result->num_rows;
    if($user > 0){
        $row = $result->fetch_assoc();
        $user = $row["login"];
        $_SESSION['zalogowany'] = true;
        $_SESSION['imie'] = $row['imie'];
        $_SESSION['id_uzytkownika'] = $row['id_uzytkownika'];
        $_SESSION['rola'] = $row['rola'];

        $result->free();
        header("Location:panel.php");
    }

}
?>
<?php


$conn->close();
?>

<html>
<head>
    <title>NEXTBOX</title>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.14.1/dist/gsap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Definicja animacji */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px); /* Tekst lekko się uniesie */
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tekst {
            animation: fadeIn 1s ease-out forwards;
        }
    </style>
</head>
<body onload="badLogin()" class="bg-dark">
<div class="h-25"></div>
<h1 class="tekst text-center text-white ">❌ Bad login or password, try again...</h1>
<script>
    function badLogin(){
        setTimeout(() =>{
            window.location.href = "loginpanel.php";
        },2000)
    }


</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>