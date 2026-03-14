<?php

declare(strict_types=1);

require_once __DIR__ . '/includes_content_store.php';
require_once __DIR__ . '/includes_admin_auth.php';

requireAdminAuth();

$error = null;
$rows = [];

try {
    $pdo = getContentPdo();
    $rows = fetchRsvps($pdo);
} catch (Throwable $exception) {
    $error = 'No fue posible cargar la lista de invitados desde la base de datos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de invitados</title>
    <style>
        body { font-family: Arial, sans-serif; background: #fafafa; color:#222; margin:0; }
        .container { max-width: 1150px; margin: 40px auto; padding: 0 20px 40px; }
        .head { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; gap:10px; }
        h1 { margin:0; }
        .actions { display:flex; gap:10px; }
        a.button { display:inline-block; background:#4b3d8f; color:#fff; text-decoration:none; border-radius:8px; padding:10px 14px; }
        a.link { color:#4b3d8f; }
        table { width:100%; border-collapse: collapse; background:#fff; border:1px solid #ddd; }
        th, td { border-bottom:1px solid #eee; padding:10px; text-align:left; font-size:14px; }
        th { background:#f4f4f5; }
        tr:last-child td { border-bottom:0; }
        .status-yes { color:#166534; font-weight:700; }
        .status-no { color:#991b1b; font-weight:700; }
        .empty { padding:20px; background:#fff; border-radius:10px; border:1px solid #ddd; }
        .err { background: #fef2f2; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:16px; }
    </style>
</head>
<body>
<div class="container">
    <div class="head">
        <h1>Panel de gestión de invitados</h1>
        <div class="actions">
            <a class="button" href="admin-export-guests.php">Descargar Excel (.xlsx)</a>
            <a class="link" href="admin-content.php">Volver a contenido</a>
            <a class="link" href="admin-content.php?logout=1">Cerrar sesión</a>
        </div>
    </div>

    <?php if ($error !== null): ?>
        <div class="err"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php elseif ($rows === []): ?>
        <div class="empty">Aún no hay invitados registrados.</div>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>N° invitados</th>
                <th>Comida</th>
                <th>Asiste</th>
                <th>Fecha</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= (int)$row['id'] ?></td>
                    <td><?= htmlspecialchars((string)$row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string)$row['email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $row['guests'] === null ? '-' : (int)$row['guests'] ?></td>
                    <td><?= htmlspecialchars((string)($row['meal_preference'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="<?= ((int)$row['attending']) === 1 ? 'status-yes' : 'status-no' ?>">
                        <?= ((int)$row['attending']) === 1 ? 'Sí' : 'No' ?>
                    </td>
                    <td><?= htmlspecialchars((string)$row['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
