<?php

require "config.php";
$conn = @new mysqli($servername, $username, $password, $dbname);
if (@$conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DELETE FROM Paczki WHERE id_paczki = ".$_POST["id"];
if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
}
else {
    echo "Error deleting record: " . $conn->error;
}
$conn->close();




?>
