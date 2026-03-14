<?php

declare(strict_types=1);

require_once __DIR__ . '/includes_content_store.php';

$defaults = defaultContentBlocks();
$message = null;
$error = null;

try {
    $pdo = getContentPdo();
    seedContentBlocks($pdo, $defaults);

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        $posted = $_POST['content'] ?? [];
        if (is_array($posted)) {
            $stmt = $pdo->prepare('UPDATE content_blocks SET content_value = :value WHERE content_key = :key');
            foreach ($defaults as $key => $defaultValue) {
                $value = isset($posted[$key]) ? trim((string)$posted[$key]) : $defaultValue;
                if ($value === '') {
                    $value = $defaultValue;
                }
                $stmt->execute([':value' => $value, ':key' => $key]);
            }
            $message = 'Contenido actualizado correctamente.';
        }
    }

    $content = array_merge($defaults, fetchContentBlocks($pdo));
} catch (Throwable $exception) {
    $error = 'No fue posible conectar con la base de datos para administrar el contenido.';
    $content = $defaults;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de contenido</title>
    <style>
        body { font-family: Arial, sans-serif; background: #fafafa; color: #222; margin: 0; }
        .container { max-width: 1100px; margin: 40px auto; padding: 0 20px 40px; }
        .head { display:flex; justify-content:space-between; align-items:center; margin-bottom: 24px; }
        h1 { margin:0; }
        a { color: #4b3d8f; }
        .msg { padding: 12px; border-radius: 8px; margin-bottom: 16px; }
        .ok { background: #ecfdf3; color:#166534; }
        .err { background: #fef2f2; color:#991b1b; }
        .grid { display:grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 16px; }
        .field { background:#fff; border:1px solid #ddd; border-radius:10px; padding:12px; }
        label { font-weight:700; display:block; margin-bottom:8px; }
        textarea { width:100%; min-height:90px; border:1px solid #ccc; border-radius:6px; padding:8px; font-size:14px; }
        .actions { margin-top: 20px; }
        button { background:#4b3d8f; color:#fff; border:0; border-radius:8px; padding:12px 18px; cursor:pointer; }
        .hint { font-size: 13px; color:#555; margin-top: 8px; }
    </style>
</head>
<body>
<div class="container">
    <div class="head">
        <h1>Formularios de administración de contenido</h1>
        <a href="index.php">Ver sitio</a>
    </div>

    <?php if ($message !== null): ?>
        <div class="msg ok"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if ($error !== null): ?>
        <div class="msg err"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="grid">
            <?php foreach ($defaults as $key => $defaultValue): ?>
                <div class="field">
                    <label for="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?></label>
                    <textarea id="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>" name="content[<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>]"><?= htmlspecialchars((string)($content[$key] ?? $defaultValue), ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="actions">
            <button type="submit">Guardar contenido</button>
            <p class="hint">Si un campo se deja vacío, se restaura el valor por defecto.</p>
        </div>
    </form>
</div>
</body>
</html>
