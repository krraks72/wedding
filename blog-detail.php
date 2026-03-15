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

$templatePath = __DIR__ . '/blog-detail-template.html';
$template = file_get_contents($templatePath);
if ($template === false) {
    http_response_code(500);
    echo 'No se pudo cargar la plantilla del blog.';
    exit;
}

$replaceMap = [
    '__BLOG_DETAIL_META_DESCRIPTION__' => htmlspecialchars($content['blog_detail_meta_description'], ENT_QUOTES, 'UTF-8'),
    '__BLOG_DETAIL_PAGE_TITLE__' => htmlspecialchars($content['blog_detail_page_title'], ENT_QUOTES, 'UTF-8'),
    '__IMAGE_LOGO__' => htmlspecialchars($content['image_logo'], ENT_QUOTES, 'UTF-8'),
    '__NAV_ABOUT__' => $content['nav_about'],
    '__NAV_STORY__' => $content['nav_story'],
    '__NAV_GALLERY__' => $content['nav_gallery'],
    '__NAV_RSVP__' => $content['nav_rsvp'],
    '__NAV_EVENTS__' => $content['nav_events'],
    '__NAV_INVITATION__' => $content['nav_invitation'],
    '__NAV_BLOGS__' => $content['nav_blogs'],
    '__BLOG_DETAIL_HEADING__' => $content['blog_detail_heading'],
    '__BLOG_DETAIL_TITLE__' => $content['blog_detail_title'],
    '__BLOG_DETAIL_AUTHOR__' => $content['blog_detail_author'],
    '__BLOG_DETAIL_COMMENTS__' => $content['blog_detail_comments'],
    '__BLOG_DETAIL_DATE__' => $content['blog_detail_date'],
    '__BLOG_DETAIL_IMAGE_MAIN__' => htmlspecialchars($content['blog_detail_image_main'], ENT_QUOTES, 'UTF-8'),
    '__BLOG_DETAIL_PARAGRAPH_1__' => $content['blog_detail_paragraph_1'],
    '__BLOG_DETAIL_QUOTE__' => $content['blog_detail_quote'],
    '__BLOG_DETAIL_PARAGRAPH_2__' => $content['blog_detail_paragraph_2'],
    '__BLOG_DETAIL_IMAGE_1__' => htmlspecialchars($content['blog_detail_image_1'], ENT_QUOTES, 'UTF-8'),
    '__BLOG_DETAIL_IMAGE_2__' => htmlspecialchars($content['blog_detail_image_2'], ENT_QUOTES, 'UTF-8'),
    '__BLOG_DETAIL_COMMENTS_HEADING__' => $content['blog_detail_comments_heading'],
    '__BLOG_DETAIL_COMMENT_1_IMAGE__' => htmlspecialchars($content['blog_detail_comment_1_image'], ENT_QUOTES, 'UTF-8'),
    '__BLOG_DETAIL_COMMENT_1_NAME__' => $content['blog_detail_comment_1_name'],
    '__BLOG_DETAIL_COMMENT_1_DATE__' => $content['blog_detail_comment_1_date'],
    '__BLOG_DETAIL_COMMENT_1_TEXT__' => $content['blog_detail_comment_1_text'],
    '__BLOG_DETAIL_COMMENT_2_IMAGE__' => htmlspecialchars($content['blog_detail_comment_2_image'], ENT_QUOTES, 'UTF-8'),
    '__BLOG_DETAIL_COMMENT_2_NAME__' => $content['blog_detail_comment_2_name'],
    '__BLOG_DETAIL_COMMENT_2_DATE__' => $content['blog_detail_comment_2_date'],
    '__BLOG_DETAIL_COMMENT_2_TEXT__' => $content['blog_detail_comment_2_text'],
    '__BLOG_DETAIL_COMMENT_3_IMAGE__' => htmlspecialchars($content['blog_detail_comment_3_image'], ENT_QUOTES, 'UTF-8'),
    '__BLOG_DETAIL_COMMENT_3_NAME__' => $content['blog_detail_comment_3_name'],
    '__BLOG_DETAIL_COMMENT_3_DATE__' => $content['blog_detail_comment_3_date'],
    '__BLOG_DETAIL_COMMENT_3_TEXT__' => $content['blog_detail_comment_3_text'],
    '__BLOG_DETAIL_REPLY_AVATAR__' => htmlspecialchars($content['blog_detail_reply_avatar'], ENT_QUOTES, 'UTF-8'),
    '__BLOG_DETAIL_REPLY_LABEL__' => $content['blog_detail_reply_label'],
    '__BLOG_DETAIL_REPLY_PLACEHOLDER__' => $content['blog_detail_reply_placeholder'],
    '__BLOG_DETAIL_REPLY_POST__' => $content['blog_detail_reply_post'],
    '__BLOG_DETAIL_FORM_HEADING__' => $content['blog_detail_form_heading'],
    '__BLOG_DETAIL_FORM_COMMENT_PLACEHOLDER__' => $content['blog_detail_form_comment_placeholder'],
    '__BLOG_DETAIL_FORM_NAME_PLACEHOLDER__' => $content['blog_detail_form_name_placeholder'],
    '__BLOG_DETAIL_FORM_EMAIL_PLACEHOLDER__' => $content['blog_detail_form_email_placeholder'],
    '__BLOG_DETAIL_FORM_SUBJECT_PLACEHOLDER__' => $content['blog_detail_form_subject_placeholder'],
    '__BLOG_DETAIL_FORM_BUTTON__' => $content['blog_detail_form_button'],
    '__FOOTER_THANKS__' => $content['footer_thanks'],
];

echo strtr($template, $replaceMap);
