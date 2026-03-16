<?php

declare(strict_types=1);

require_once __DIR__ . '/includes_content_store.php';
require_once __DIR__ . '/includes_admin_auth.php';

adminSessionStart();

$defaults = defaultContentBlocks();
$imageKeys = array_flip(imageContentKeys());
$message = null;
$error = null;
$loginError = null;

$sectionMeta = [
    'home' => [
        'title' => 'Plantilla Home (index.php)',
        'desc' => 'Contenido principal de la página de inicio.',
    ],
    'invitation' => [
        'title' => 'Plantilla Invitación (invitation.php)',
        'desc' => 'Contenido mostrado en la página de invitación.',
    ],
    'blog_detail' => [
        'title' => 'Plantilla Detalle de Blog (blog-detail.php)',
        'desc' => 'Contenido del detalle de blog y formulario de comentarios.',
    ],
    'images_home' => [
        'title' => 'Imágenes Home',
        'desc' => 'Imágenes usadas en la página principal.',
    ],
];

$guideByKey = [
    'meta_description' => 'SEO: meta description del Home.',
    'page_title' => 'Etiqueta <title> del Home.',
    'hero_paragraph' => 'Párrafo principal del hero. Puedes usar <br> para saltos.',
    'event_1_location_url' => 'URL del mapa o ubicación del evento 1.',
    'event_2_location_url' => 'URL del mapa o ubicación del evento 2.',
    'invitation_meta_description' => 'SEO: meta description de la invitación.',
    'invitation_page_title' => 'Etiqueta <title> de la invitación.',
    'blog_detail_meta_description' => 'SEO: meta description del detalle de blog.',
    'blog_detail_page_title' => 'Etiqueta <title> del detalle de blog.',
];

$guideByPrefix = [
    'blog_detail_comment_' => 'Comentario precargado en el detalle del blog.',
    'blog_detail_form_' => 'Formulario "Leave a comment" en el detalle del blog.',
    'blog_detail_reply_' => 'Bloque de respuesta en el detalle del blog.',
    'blog_detail_image_' => 'Imagen del detalle del blog.',
    'blog_detail_' => 'Contenido general del detalle del blog.',
    'invitation_' => 'Contenido de la invitación.',
    'event_1_' => 'Evento 1 en Home.',
    'event_2_' => 'Evento 2 en Home.',
    'events_' => 'Encabezado de sección de eventos en Home.',
    'blog_1_' => 'Tarjeta de blog 1 en Home.',
    'blog_2_' => 'Tarjeta de blog 2 en Home.',
    'blog_3_' => 'Tarjeta de blog 3 en Home.',
    'blogs_' => 'Encabezado de sección blog en Home.',
    'rsvp_' => 'Sección RSVP en Home.',
    'story_' => 'Sección historia en Home.',
    'countdown_' => 'Sección contador en Home.',
    'about_' => 'Sección About Us en Home.',
    'hero_' => 'Sección hero en Home.',
    'nav_' => 'Texto del menú principal en Home.',
    'footer_' => 'Pie de página del Home.',
    'image_' => 'Imagen usada en el Home.',
];

function guideForKey(string $key, array $guideByKey, array $guideByPrefix): string
{
    if (isset($guideByKey[$key])) {
        return $guideByKey[$key];
    }

    foreach ($guideByPrefix as $prefix => $guide) {
        if (str_starts_with($key, $prefix)) {
            return $guide;
        }
    }

    return sprintf('Campo de contenido para %s.', $key);
}

function sectionForKey(string $key): string
{
    if (str_starts_with($key, 'invitation_')) {
        return 'invitation';
    }
    if (str_starts_with($key, 'blog_detail_')) {
        return 'blog_detail';
    }
    if (str_starts_with($key, 'image_')) {
        return 'images_home';
    }
    return 'home';
}

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: admin-content.php?login=1');
    exit;
}

if (!isAdminAuthenticated()) {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['login_action'])) {
        [$validUser, $validPass] = adminCredentials();
        $user = trim((string)($_POST['username'] ?? ''));
        $pass = (string)($_POST['password'] ?? '');

        if (hash_equals($validUser, $user) && hash_equals($validPass, $pass)) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: admin-content.php');
            exit;
        }

        $loginError = 'Credenciales inválidas.';
    }

    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login administrador</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f4f5; margin:0; min-height:100vh; display:grid; place-items:center; }
            .card { width:100%; max-width:420px; background:#fff; border-radius:12px; border:1px solid #ddd; padding:24px; }
            h1 { margin-top:0; font-size:24px; }
            label { display:block; margin-top:12px; margin-bottom:6px; font-weight:700; }
            input { width:95%; padding:10px; border:1px solid #ccc; border-radius:8px; }
            button { margin-top:16px; width:100%; border:0; border-radius:8px; padding:12px; background:#4b3d8f; color:white; font-weight:700; cursor:pointer; }
            .err { background:#fef2f2; color:#991b1b; padding:10px; border-radius:8px; margin-bottom:12px; }
            .hint { font-size:12px; color:#555; margin-top:12px; }
        </style>
    </head>
    <body>
    <form method="post" class="card">
        <h1>Panel administrador</h1>
        <?php if ($loginError !== null): ?>
            <div class="err"><?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <label for="username">Usuario</label>
        <input id="username" name="username" required>

        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" required>

        <input type="hidden" name="login_action" value="1">
        <button type="submit">Ingresar</button>
        <p class="hint">Credenciales por defecto: <strong>admin / admin123</strong>. Puedes cambiarlas con variables de entorno <code>ADMIN_USER</code> y <code>ADMIN_PASS</code>.</p>
    </form>
    </body>
    </html>
    <?php
    exit;
}


function imageResolutionLabel(string $imagePath): ?string
{
    $normalizedPath = ltrim($imagePath, '/');
    $fullPath = __DIR__ . '/' . $normalizedPath;

    if (!is_file($fullPath)) {
        return null;
    }

    $imageInfo = @getimagesize($fullPath);
    if ($imageInfo === false) {
        return null;
    }

    $width = (int)($imageInfo[0] ?? 0);
    $height = (int)($imageInfo[1] ?? 0);

    if ($width <= 0 || $height <= 0) {
        return null;
    }

    return sprintf('%d x %d px', $width, $height);
}

try {
    $pdo = getContentPdo();
    seedContentBlocks($pdo, $defaults);

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        $posted = $_POST['content'] ?? [];
        if (is_array($posted)) {
            $stmt = $pdo->prepare('UPDATE content_blocks SET content_value = :value WHERE content_key = :key');
            foreach ($defaults as $key => $defaultValue) {
                $value = isset($posted[$key]) ? trim((string)$posted[$key]) : $defaultValue;

                if (isset($imageKeys[$key]) && isset($_FILES['image_upload']['error'][$key]) && $_FILES['image_upload']['error'][$key] === UPLOAD_ERR_OK) {
                    $originalName = (string)($_FILES['image_upload']['name'][$key] ?? '');
                    $tmpPath = (string)($_FILES['image_upload']['tmp_name'][$key] ?? '');
                    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

                    if ($tmpPath !== '' && in_array($extension, $allowed, true)) {
                        $uploadDir = __DIR__ . '/assets/media/uploads';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0775, true);
                        }

                        $fileName = sprintf('%s-%s.%s', $key, bin2hex(random_bytes(6)), $extension);
                        $destination = $uploadDir . '/' . $fileName;
                        if (move_uploaded_file($tmpPath, $destination)) {
                            $value = 'assets/media/uploads/' . $fileName;
                        }
                    }
                }

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
        .head { display:flex; justify-content:space-between; align-items:center; margin-bottom: 24px; gap: 10px; }
        h1 { margin:0; }
        a { color: #4b3d8f; }
        .msg { padding: 12px; border-radius: 8px; margin-bottom: 16px; }
        .ok { background: #ecfdf3; color:#166534; }
        .err { background: #fef2f2; color:#991b1b; }
        .section-block { background:#fff; border:1px solid #e1deef; border-radius:12px; padding:16px; margin-bottom:18px; }
        .section-grid { display:grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 16px; margin-top:14px; }
        .section { background: #f7f5ff; border:1px dashed #c9c3e6; border-radius:10px; padding:12px 14px; }
        .section h2 { margin:0 0 4px; font-size:18px; }
        .section p { margin:0; font-size:13px; color:#4b4b62; }
        .section-nav { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:18px; }
        .section-nav a { background:#fff; border:1px solid #ddd; padding:6px 10px; border-radius:999px; text-decoration:none; font-size:13px; color:#3d3d4f; }
        .section-nav a:hover { border-color:#4b3d8f; color:#4b3d8f; }
        .field { background:#fff; border:1px solid #ddd; border-radius:10px; padding:12px; }
        label { font-weight:700; display:block; margin-bottom:8px; }
        textarea { width:95%; min-height:90px; border:1px solid #ccc; border-radius:6px; padding:8px; font-size:14px; }
        .guide { margin-top:8px; font-size:12px; color:#5a5a6a; background:#f3f4f6; border-radius:6px; padding:6px 8px; }
        .guide code { background:#e7e7f4; padding:1px 4px; border-radius:4px; }
        .actions { margin-top: 20px; }
        button { background:#4b3d8f; color:#fff; border:0; border-radius:8px; padding:12px 18px; cursor:pointer; }
        .hint { font-size: 13px; color:#555; margin-top: 8px; }
        .links { display:flex; gap:10px; }
        .image-meta { margin-top: 8px; font-size: 12px; color:#444; }
    </style>
</head>
<body>
<div class="container">
    <div class="head">
        <h1>Formularios de administración de contenido</h1>
        <div class="links">
            <a href="admin-guests.php">Ver invitados</a>
            <a href="index.php">Ver sitio</a>
            <a href="admin-content.php?logout=1">Cerrar sesión</a>
        </div>
    </div>

    <?php if ($message !== null): ?>
        <div class="msg ok"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if ($error !== null): ?>
        <div class="msg err"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php
    $sectionBuckets = [];
    $sectionOrder = [];
    $sectionIds = [
        'home' => 'section-home',
        'invitation' => 'section-invitation',
        'blog_detail' => 'section-blog-detail',
        'images_home' => 'section-images-home',
    ];
    foreach ($defaults as $key => $defaultValue) {
        $sectionKey = sectionForKey($key);
        if (!isset($sectionBuckets[$sectionKey])) {
            $sectionBuckets[$sectionKey] = [];
            $sectionOrder[] = $sectionKey;
        }
        $sectionBuckets[$sectionKey][] = [$key, $defaultValue];
    }
    ?>
    <div class="section-nav">
        <?php foreach ($sectionOrder as $sectionKey): ?>
            <?php
            $sectionInfo = $sectionMeta[$sectionKey] ?? ['title' => 'Contenido'];
            $sectionId = $sectionIds[$sectionKey] ?? ('section-' . $sectionKey);
            ?>
            <a href="#<?= htmlspecialchars($sectionId, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($sectionInfo['title'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endforeach; ?>
    </div>
    <form method="post" enctype="multipart/form-data">
        <?php foreach ($sectionOrder as $sectionKey): ?>
            <?php $sectionInfo = $sectionMeta[$sectionKey] ?? ['title' => 'Contenido', 'desc' => '']; ?>
            <?php $sectionId = $sectionIds[$sectionKey] ?? ('section-' . $sectionKey); ?>
            <div class="section-block" id="<?= htmlspecialchars($sectionId, ENT_QUOTES, 'UTF-8') ?>">
                <div class="section">
                    <h2><?= htmlspecialchars($sectionInfo['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <?php if ($sectionInfo['desc'] !== ''): ?>
                        <p><?= htmlspecialchars($sectionInfo['desc'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>
                <div class="section-grid">
                    <?php foreach ($sectionBuckets[$sectionKey] as [$key, $defaultValue]): ?>
                        <div class="field">
                            <label for="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?></label>
                            <textarea id="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>" name="content[<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>]"><?= htmlspecialchars((string)($content[$key] ?? $defaultValue), ENT_QUOTES, 'UTF-8') ?></textarea>
                            <div class="guide">
                                Gu&iacute;a: <?= htmlspecialchars(guideForKey($key, $guideByKey, $guideByPrefix), ENT_QUOTES, 'UTF-8') ?>
                                &nbsp;Etiqueta: <code><?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?></code>
                            </div>
                            <?php if (isset($imageKeys[$key])): ?>
                                <?php
                                $currentImagePath = (string)($content[$key] ?? $defaultValue);
                                $resolutionLabel = imageResolutionLabel($currentImagePath);
                                ?>
                                <input type="file" name="image_upload[<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>]" accept=".jpg,.jpeg,.png,.gif,.webp,.svg" style="margin-top:8px; width:100%;">
                                <div class="image-meta">
                                    Resoluci&oacute;n recomendada: <?= htmlspecialchars($resolutionLabel ?? 'No disponible', ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="actions">
            <button type="submit">Guardar contenido</button>
            <p class="hint">Si un campo se deja vacío, se restaura el valor por defecto.</p>
        </div>
    </form>
</div>
</body>
</html>
