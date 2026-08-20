<
<?php
session_start();
require_once "config.php";
// login.php
$conn = @new mysqli($servername, $username, $password, $dbname);
if (@$conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login = isset($_POST['usernameReg']) ? $_POST['usernameReg'] : NULL;
$haslo = isset($_POST['passwordReg']) ? $_POST['passwordReg'] : NULL;

$sql = "select * from Uzytkownicy where login='$login' and haslo='$haslo' ";

if ($result = @$conn->query($sql)) {
    $user = $result->num_rows;
    if($user > 0 or $haslo == NULL or $login == NULL){
        echo "Istnieje juz taki uzytkownik";
        echo "<br><a href='index.php'>Spróbuj ponownie</a>";
    }
    else {
        $login = $_POST['usernameReg'];
        $password = $_POST['passwordReg'];
        $name = $_POST['nameReg'];
        $email = $_POST['emailReg'];
        $surname = $_POST['surnameReg'];
        $sqlin = "INSERT INTO Uzytkownicy (login, haslo, imie, nazwisko, rola) VALUES ('$login', '$password', '$name', '$surname', 'KLIENT')";
        if ($conn->query($sqlin) === TRUE) {
            echo "New record created successfully";
            header('Location: index.php');
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }


}
else{

}
?>
<?php


$conn->close();
?>
