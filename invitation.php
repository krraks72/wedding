<?php

declare(strict_types=1);

require_once __DIR__ . '/includes_content_store.php';

$defaults = defaultContentBlocks();

try {
    $pdo = getContentPdo();
    seedContentBlocks($pdo, $defaults);
    $content = array_merge($defaults, fetchContentBlocks($pdo));
} catch (Throwable $exception) {
    $content = $defaults;
}

$templatePath = __DIR__ . '/invitation-template.html';
$template = file_get_contents($templatePath);

if ($template === false) {
    http_response_code(500);
    echo 'No se pudo cargar la plantilla de invitacion.';
    exit;
}

$replaceMap = [
    '__INVITATION_META_DESCRIPTION__' => htmlspecialchars($content['invitation_meta_description'], ENT_QUOTES, 'UTF-8'),
    '__INVITATION_PAGE_TITLE__' => htmlspecialchars($content['invitation_page_title'], ENT_QUOTES, 'UTF-8'),
    '__IMAGE_LOGO__' => htmlspecialchars($content['image_logo'], ENT_QUOTES, 'UTF-8'),
    '__NAV_ABOUT__' => $content['nav_about'],
    '__NAV_STORY__' => $content['nav_story'],
    '__NAV_GALLERY__' => $content['nav_gallery'],
    '__NAV_RSVP__' => $content['nav_rsvp'],
    '__NAV_EVENTS__' => $content['nav_events'],
    '__NAV_INVITATION__' => $content['nav_invitation'],
    '__NAV_BLOGS__' => $content['nav_blogs'],
    '__INVITATION_TITLE__' => $content['invitation_title'],
    '__INVITATION_LABEL__' => $content['invitation_label'],
    '__INVITATION_TEXT__' => $content['invitation_text'],
    '__INVITATION_DATE__' => $content['invitation_date'],
    '__INVITATION_COUNTDOWN_DATE__' => $content['invitation_countdown_date'],
    '__INVITATION_LOCATION__' => $content['invitation_location'],
    '__INVITATION_BUTTON__' => $content['invitation_button'],
];

echo strtr($template, $replaceMap);