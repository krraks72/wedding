<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes_content_store.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $pdo = getContentPdo();
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
    echo json_encode(fetchRsvps($pdo));
    exit;
}

http_response_code(405);
echo json_encode(['message' => 'Método no permitido.']);
