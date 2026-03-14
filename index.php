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
    'Nov 6, 2023' => $content['hero_date'],
    'About Us' => $content['about_label'],
    'Let’s know' => $content['about_title'],
    'John William' => $content['about_person_1'],
    'Sophie Alex' => $content['about_person_2'],
    'Lorem ipsum dolor sit amet consectetur. Cursus dictumst commodo cursus dignissim nunc ut.' => $content['about_paragraph'],
    'save the date' => $content['countdown_label'],
    'We are getting married' => $content['countdown_title'],
    'Days' => $content['count_days'],
    'Hrs' => $content['count_hours'],
    'Min' => $content['count_minutes'],
    'Sec' => $content['count_seconds'],
    'Clark Hall Main Bolouward, London' => $content['location'],
    'OUR STORY' => $content['story_label'],
    'Tale Of Love' => $content['story_title'],
    '22 JAN, 2021' => $content['story_1_date'],
    'How we meet' => $content['story_1_title'],
    'Lorem ipsum dolor sit amet consectetur. Pretium morbi id volutpat ut viverra vel. Non sit massa vitae penatibus sit velit quis massa. Bibendum odio quis feugiat erat sit velit. Magnis mi eros ante quis morbi. Ornare urna ultricies quisque id et.' => $content['story_text'],
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
    'When & Where' => $content['events_title'],
    'The Ceremony' => $content['event_1_title'],
    'Sunday 10 Jan, 2024' => $content['event_1_date'],
    '2:00 PM - 4:00 PM' => $content['event_time'],
    'Clark Hall Main Bolouward,<br>London' => $content['event_address_2_lines'],
    'See Location' => $content['event_location_button'],
    'The Reception' => $content['event_2_title'],
    'Monday 11 Jan, 2024' => $content['event_2_date'],
    'Tuesday 12 Jan, 2024' => $content['event_3_date'],
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
