<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbName = 'wedding';

try {
    $bootstrapDsn = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $dbHost, $dbPort);
    $bootstrapPdo = new PDO($bootstrapDsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $bootstrapPdo->exec('CREATE DATABASE IF NOT EXISTS `wedding` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $dbHost, $dbPort, $dbName);
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS rsvps (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(120) NOT NULL,
            email VARCHAR(190) NOT NULL,
            guests INT NULL,
            meal_preference VARCHAR(100) NULL,
            attending TINYINT(1) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );
} catch (Throwable $error) {
    http_response_code(500);
    echo json_encode([
        'message' => 'Error al inicializar MySQL. Verifica conexión y credenciales.',
    ]);
    exit;
}

if ($method === 'POST') {
    $raw = file_get_contents('php://input');
    $payload = json_decode($raw ?: '', true);

    if (!is_array($payload)) {
        http_response_code(400);
        echo json_encode(['message' => 'JSON inválido.']);
        exit;
    }

    $name = trim((string)($payload['name'] ?? ''));
    $email = trim((string)($payload['email'] ?? ''));
    $attending = $payload['attending'] ?? null;
    $guests = $payload['guests'] ?? null;
    $mealPreference = $payload['mealPreference'] ?? null;

    if ($name === '' || $email === '' || !is_bool($attending)) {
        http_response_code(400);
        echo json_encode(['message' => 'Datos incompletos en la confirmación.']);
        exit;
    }

    $guestsValue = is_numeric($guests) ? (int)$guests : null;
    $mealValue = is_string($mealPreference) && trim($mealPreference) !== '' ? trim($mealPreference) : null;

    $stmt = $pdo->prepare(
        'INSERT INTO rsvps (name, email, guests, meal_preference, attending)
         VALUES (:name, :email, :guests, :meal_preference, :attending)'
    );

    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':guests', $guestsValue, $guestsValue === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindValue(':meal_preference', $mealValue, $mealValue === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':attending', $attending ? 1 : 0, PDO::PARAM_INT);
    $stmt->execute();

    http_response_code(201);
    echo json_encode([
        'id' => (int)$pdo->lastInsertId(),
        'message' => 'Confirmación guardada correctamente.'
    ]);
    exit;
}

if ($method === 'GET') {
    $rows = $pdo->query('SELECT * FROM rsvps ORDER BY id DESC')->fetchAll();
    echo json_encode($rows);
    exit;
}

http_response_code(405);
echo json_encode(['message' => 'Método no permitido.']);
