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

function buildCountdownDate(string $date, string $timeRange): string
{
    $timeRange = trim($timeRange);
    if ($timeRange === '') {
        return $date;
    }
    $parts = explode('-', $timeRange, 2);
    $startTime = trim($parts[0] ?? '');
    if ($startTime === '') {
        return $date;
    }
    return trim($date . ' ' . $startTime);
}

function parseCountdownDate(string $rawDate): ?DateTimeImmutable
{
    $rawDate = trim($rawDate);
    if ($rawDate === '') {
        return null;
    }
    try {
        return new DateTimeImmutable($rawDate);
    } catch (Throwable $exception) {
        return null;
    }
}

function calculateCountdownParts(?DateTimeImmutable $target, ?DateTimeImmutable $now = null): array
{
    $now = $now ?? new DateTimeImmutable('now');
    if (!$target) {
        return [
            'days' => '0',
            'hours' => '00',
            'minutes' => '00',
            'seconds' => '00',
        ];
    }

    $secondsLeft = max(0, $target->getTimestamp() - $now->getTimestamp());
    $days = intdiv($secondsLeft, 86400);
    $hours = intdiv($secondsLeft % 86400, 3600);
    $minutes = intdiv($secondsLeft % 3600, 60);
    $seconds = $secondsLeft % 60;

    return [
        'days' => (string)$days,
        'hours' => str_pad((string)$hours, 2, '0', STR_PAD_LEFT),
        'minutes' => str_pad((string)$minutes, 2, '0', STR_PAD_LEFT),
        'seconds' => str_pad((string)$seconds, 2, '0', STR_PAD_LEFT),
    ];
}

$rawCountdownDate = (string)($content['countdown_date'] ?? '');
if (trim($rawCountdownDate) === '') {
    $rawCountdownDate = buildCountdownDate(
        (string)($content['event_1_date'] ?? ''),
        (string)($content['event_1_time'] ?? '')
    );
}

$countdownTarget = parseCountdownDate($rawCountdownDate);
$countdownParts = calculateCountdownParts($countdownTarget);

$templatePath = __DIR__ . '/index-content-template.html';
$template = file_get_contents($templatePath);
if ($template === false) {
    http_response_code(500);
    echo 'No se pudo cargar la plantilla del sitio.';
    exit;
}

$replaceMap = [
    'content="Audiobeats HTML5 Template"' => 'content="' . htmlspecialchars($content['meta_description'], ENT_QUOTES, 'UTF-8') . '"',
    '<title>Blessed wedding template</title>' => '<title>' . htmlspecialchars($content['page_title'], ENT_QUOTES, 'UTF-8') . '</title>',
    'src="assets/media/logo.png"' => 'src="' . htmlspecialchars($content['image_logo'], ENT_QUOTES, 'UTF-8') . '"',
    'src="./assets/media/logo.png"' => 'src="' . htmlspecialchars($content['image_logo'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/banner/banner-img-1.png"' => 'src="' . htmlspecialchars($content['image_hero_left'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/banner/banner-img-2.png"' => 'src="' . htmlspecialchars($content['image_hero_right'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/about/grrom.png"' => 'src="' . htmlspecialchars($content['image_about_groom'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/about/bride.png"' => 'src="' . htmlspecialchars($content['image_about_bride'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/coming-soon/Image.png"' => 'src="' . htmlspecialchars($content['image_countdown'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/story/s-1.png"' => 'src="' . htmlspecialchars($content['image_story_1'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/story/s-2.png"' => 'src="' . htmlspecialchars($content['image_story_2'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/story/s-3.png"' => 'src="' . htmlspecialchars($content['image_story_3'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/gallery/Image Frame.png"' => 'src="' . htmlspecialchars($content['image_gallery_1'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/gallery/Image-2.png"' => 'src="' . htmlspecialchars($content['image_gallery_2'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/gallery/Image-3.png"' => 'src="' . htmlspecialchars($content['image_gallery_3'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/gallery/Image-4.png"' => 'src="' . htmlspecialchars($content['image_gallery_4'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/gallery/Image-5.png"' => 'src="' . htmlspecialchars($content['image_gallery_5'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/gallery/Image.png"' => 'src="' . htmlspecialchars($content['image_gallery_6'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/gallery/Image-1.png"' => 'src="' . htmlspecialchars($content['image_gallery_7'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/blogs/Image.png"' => 'src="' . htmlspecialchars($content['image_blog_1'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/blogs/Image-1.png"' => 'src="' . htmlspecialchars($content['image_blog_2'], ENT_QUOTES, 'UTF-8') . '"',
    'src="assets/media/blogs/Image-2.png"' => 'src="' . htmlspecialchars($content['image_blog_3'], ENT_QUOTES, 'UTF-8') . '"',
    '__NAV_ABOUT__' => $content['nav_about'],
    '__NAV_STORY__' => $content['nav_story'],
    '__NAV_GALLERY__' => $content['nav_gallery'],
    '__NAV_RSVP__' => $content['nav_rsvp'],
    '__NAV_EVENTS__' => $content['nav_events'],
    '__NAV_INVITATION__' => $content['nav_invitation'],
    '__NAV_BLOGS__' => $content['nav_blogs'],
    'ABOUT US' => $content['nav_about'],
    'STORY' => $content['nav_story'],
    'GALLERY' => $content['nav_gallery'],
    'RSVP' => $content['nav_rsvp'],
    'EVENTS' => $content['nav_events'],
    'INVITATION' => $content['nav_invitation'],
    'Blogs' => $content['nav_blogs'],
    'All You<br>Need<br>Is Love' => $content['hero_title'],
    'John and Sofie' => $content['hero_couple'],
    'Lorem ipsum dolor sit amet consectetur. Velit vulputate lacus risus scelerisque faucibus eu. Sollicitudin justo imperdiet vitae mattis ipsum arcu nullam odio. Leo sed quis.' => $content['hero_paragraph'],
    'Save The Date' => $content['hero_date_label'],
    'JUN 10, 2026' => $content['hero_date'],
    'About Us' => $content['about_label'],
    'Let’s know' => $content['about_title'],
    'John William' => $content['about_person_1'],
    'Sophie Alex' => $content['about_person_2'],
    'Él, un caballero de casco y botas de construcción, que lideraba obras civiles' => $content['about_paragraph_1'],
    'Ella, una dama de hermosa cabellera y vestido blanco' => $content['about_paragraph_2'],
    'save the date' => $content['countdown_label'],
    'We are getting married' => $content['countdown_title'],
    '__COUNTDOWN_DATE__' => htmlspecialchars($rawCountdownDate, ENT_QUOTES, 'UTF-8'),
    '__COUNTDOWN_DAYS__' => $countdownParts['days'],
    '__COUNTDOWN_HOURS__' => $countdownParts['hours'],
    '__COUNTDOWN_MINUTES__' => $countdownParts['minutes'],
    '__COUNTDOWN_SECONDS__' => $countdownParts['seconds'],
    '__COUNT_DAYS_LABEL__' => htmlspecialchars($content['count_days'], ENT_QUOTES, 'UTF-8'),
    '__COUNT_HOURS_LABEL__' => htmlspecialchars($content['count_hours'], ENT_QUOTES, 'UTF-8'),
    '__COUNT_MINUTES_LABEL__' => htmlspecialchars($content['count_minutes'], ENT_QUOTES, 'UTF-8'),
    '__COUNT_SECONDS_LABEL__' => htmlspecialchars($content['count_seconds'], ENT_QUOTES, 'UTF-8'),
    'Clark Hall Main Bolouward, London' => $content['location'],
    'OUR STORY' => $content['story_label'],
    'Tale Of Love' => $content['story_title'],
    '22 JAN, 2021' => $content['story_1_date'],
    'How we meet' => $content['story_1_title'],
    'Lorem ipsum dolor sit amet consectetur. Pretium morbi id volutpat ut viverra vel. Non sit massa vitae penatibus sit velit quis massa. Bibendum odio quis feugiat erat sit velit. Magnis mi eros ante quis morbi. Ornare urna ultricies quisque id et.' => $content['story_1_text'],
    '2. Lorem ipsum dolor sit amet consectetur. Pretium morbi id volutpat ut viverra vel. Non sit massa vitae penatibus sit velit quis massa. Bibendum odio quis feugiat erat sit velit. Magnis mi eros ante quis morbi. Ornare urna ultricies quisque id et.' => $content['story_2_text'],
    '3. Lorem ipsum dolor sit amet consectetur. Pretium morbi id volutpat ut viverra vel. Non sit massa vitae penatibus sit velit quis massa. Bibendum odio quis feugiat erat sit velit. Magnis mi eros ante quis morbi. Ornare urna ultricies quisque id et.' => $content['story_3_text'],
    '29 DEC, 2022' => $content['story_2_date'],
    'He proposed, I said Yes' => $content['story_2_title'],
    '12 FEB, 2023' => $content['story_3_date'],
    'Our Engagement Day' => $content['story_3_title'],
    'Will You Attend?' => $content['rsvp_title'],
    'Please reserve before Jan 5th, 2024' => $content['rsvp_paragraph'],
    'Number Of Guests' => $content['rsvp_guest_label'],
    'Two' => $content['rsvp_guest_two'],
    'Three' => $content['rsvp_guest_three'],
    'Four' => $content['rsvp_guest_four'],
    'Meal Preferences' => $content['rsvp_meal_label'],
    'Buffet Style' => $content['rsvp_meal_1'],
    'Food Stations' => $content['rsvp_meal_2'],
    'Themed Cuisine' => $content['rsvp_meal_3'],
    'Dessert Only' => $content['rsvp_meal_4'],
    'Yes, I will be there' => $content['rsvp_attend_yes'],
    'Sorry, I can’t come' => $content['rsvp_attend_no'],
    'Send An Inquiry' => $content['rsvp_button'],
    'OUR Wedding' => $content['events_label'],
    '__EVENT_1_LOCATION_URL__' => htmlspecialchars($content['event_1_location_url'], ENT_QUOTES, 'UTF-8'),
    '__EVENT_2_LOCATION_URL__' => htmlspecialchars($content['event_2_location_url'], ENT_QUOTES, 'UTF-8'),
    'When & Where' => $content['events_title'],
    'The Ceremony' => $content['event_1_title'],
    'Sunday 10 Jan, 2026' => $content['event_1_date'],
    '2:00 PM' => $content['event_1_time'],
    'Clark Hall Main Bolouward,<br>London' => $content['event_1_address'],
    'See Location 1' => $content['event_1_location_button'],
    'The Reception' => $content['event_2_title'],
    'Monday 11 Jan, 2026' => $content['event_2_date'],
    '4:00 PM' => $content['event_2_time'],
    'Clark Hall Main Bolouward, 2<br>London 2' => $content['event_2_address'],
    'See Location 2' => $content['event_2_location_button'],
    'OUR Blog' => $content['blogs_label'],
    'latest news' => $content['blogs_title'],
    '10 JAN, 2024' => $content['blog_1_date'],
    'Two Hearts Become One: John and Sofie Celebrate Their Marriage' => $content['blog_1_title'],
    'Lorem ipsum dolor sit amet consectetur. Pretium morbi id volutpat ut viverra vel. Non sit massa vitae penatibus sit velit quis massa.' => $content['blog_1_text'],
    '11 JAN, 2024' => $content['blog_2_date'],
    'Love in Bloom: John and Sofie Embark on Their New Chapter' => $content['blog_2_title'],
    '12 JAN, 2024' => $content['blog_3_date'],
    "Celebrating Love's Journey: John and Sofie Say 'Forever'" => $content['blog_3_title'],
    'Read More' => $content['blog_read_more'],
    'Thank You' => $content['footer_thanks'],
];

echo strtr($template, $replaceMap);
