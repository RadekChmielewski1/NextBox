<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['id'] ?? null;

if (!$userId) {
    echo json_encode(['error' => 'Brak ID użytkownika']);
    exit;
}

// Dane logowania do bazy
$host = 'localhost';
$db   = 'twoja_baza';
$user = 'root';
$pass = 'haslo';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

    // Przygotowanie bezpiecznego zapytania (ochrona przed SQL Injection)
    $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($user ?: ['error' => 'Nie znaleziono użytkownika']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Błąd połączenia: ' . $e->getMessage()]);
}
?><?php
