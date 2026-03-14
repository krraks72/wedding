<?php

declare(strict_types=1);

function getContentPdo(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dbHost = getenv('DB_HOST') ?: '127.0.0.1';
    $dbPort = getenv('DB_PORT') ?: '3306';
    $dbUser = getenv('DB_USER') ?: 'root';
    $dbPass = getenv('DB_PASS') ?: '';
    $dbName = 'wedding';

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
        'CREATE TABLE IF NOT EXISTS content_blocks (
            content_key VARCHAR(120) PRIMARY KEY,
            content_value TEXT NOT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    return $pdo;
}

function defaultContentBlocks(): array
{
    return [
        'meta_description' => 'Audiobeats HTML5 Template',
        'page_title' => 'Blessed wedding template',
        'nav_about' => 'ABOUT US',
        'nav_story' => 'STORY',
        'nav_gallery' => 'GALLERY',
        'nav_rsvp' => 'RSVP',
        'nav_events' => 'EVENTS',
        'nav_invitation' => 'INVITATION',
        'nav_blogs' => 'Blogs',
        'hero_title' => 'All You<br>Need<br>Is Love',
        'hero_couple' => 'John and Sofie',
        'hero_paragraph' => 'Lorem ipsum dolor sit amet consectetur. Velit vulputate lacus risus scelerisque faucibus eu. Sollicitudin justo imperdiet vitae mattis ipsum arcu nullam odio. Leo sed quis.',
        'hero_date_label' => 'Save The Date',
        'hero_date' => 'Nov 6, 2023',
        'about_label' => 'About Us',
        'about_title' => 'Let’s know',
        'about_person_1' => 'John William',
        'about_person_2' => 'Sophie Alex',
        'about_paragraph' => 'Lorem ipsum dolor sit amet consectetur. Cursus dictumst commodo cursus dignissim nunc ut.',
        'countdown_label' => 'save the date',
        'countdown_title' => 'We are getting married',
        'count_days' => 'Days',
        'count_hours' => 'Hrs',
        'count_minutes' => 'Min',
        'count_seconds' => 'Sec',
        'location' => 'Clark Hall Main Bolouward, London',
        'story_label' => 'OUR STORY',
        'story_title' => 'Tale Of Love',
        'story_1_date' => '22 JAN, 2021',
        'story_1_title' => 'How we meet',
        'story_text' => 'Lorem ipsum dolor sit amet consectetur. Pretium morbi id volutpat ut viverra vel. Non sit massa vitae penatibus sit velit quis massa. Bibendum odio quis feugiat erat sit velit. Magnis mi eros ante quis morbi. Ornare urna ultricies quisque id et.',
        'story_2_date' => '29 DEC, 2022',
        'story_2_title' => 'He proposed, I said Yes',
        'story_3_date' => '12 FEB, 2023',
        'story_3_title' => 'Our Engagement Day',
        'rsvp_title' => 'Will You Attend?',
        'rsvp_paragraph' => 'Please reserve before Jan 5th, 2024',
        'rsvp_guest_label' => 'Number Of Guests',
        'rsvp_guest_two' => 'Two',
        'rsvp_guest_three' => 'Three',
        'rsvp_guest_four' => 'Four',
        'rsvp_meal_label' => 'Meal Preferences',
        'rsvp_meal_1' => 'Buffet Style',
        'rsvp_meal_2' => 'Food Stations',
        'rsvp_meal_3' => 'Themed Cuisine',
        'rsvp_meal_4' => 'Dessert Only',
        'rsvp_attend_yes' => 'Yes, I will be there',
        'rsvp_attend_no' => 'Sorry, I can’t come',
        'rsvp_button' => 'Send An Inquiry',
        'events_label' => 'OUR Wedding',
        'events_title' => 'When & Where',
        'event_1_title' => 'The Ceremony',
        'event_1_date' => 'Sunday 10 Jan, 2024',
        'event_time' => '2:00 PM - 4:00 PM',
        'event_address_2_lines' => 'Clark Hall Main Bolouward,<br>London',
        'event_location_button' => 'See Location',
        'event_2_title' => 'The Reception',
        'event_2_date' => 'Monday 11 Jan, 2024',
        'event_3_date' => 'Tuesday 12 Jan, 2024',
        'blogs_label' => 'OUR Blog',
        'blogs_title' => 'latest news',
        'blog_1_date' => '10 JAN, 2024',
        'blog_1_title' => 'Two Hearts Become One: John and Sofie Celebrate Their Marriage',
        'blog_1_text' => 'Lorem ipsum dolor sit amet consectetur. Pretium morbi id volutpat ut viverra vel. Non sit massa vitae penatibus sit velit quis massa.',
        'blog_2_date' => '11 JAN, 2024',
        'blog_2_title' => 'Love in Bloom: John and Sofie Embark on Their New Chapter',
        'blog_3_date' => '12 JAN, 2024',
        'blog_3_title' => "Celebrating Love's Journey: John and Sofie Say 'Forever'",
        'blog_read_more' => 'Read More',
        'footer_thanks' => 'Thank You',
    ];
}

function seedContentBlocks(PDO $pdo, array $defaults): void
{
    $stmt = $pdo->prepare('INSERT IGNORE INTO content_blocks (content_key, content_value) VALUES (:k, :v)');
    foreach ($defaults as $key => $value) {
        $stmt->execute([':k' => $key, ':v' => $value]);
    }
}

function fetchContentBlocks(PDO $pdo): array
{
    $rows = $pdo->query('SELECT content_key, content_value FROM content_blocks')->fetchAll();
    $content = [];
    foreach ($rows as $row) {
        $content[$row['content_key']] = $row['content_value'];
    }
    return $content;
}
