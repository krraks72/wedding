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

    ensureContentBlocksTable($pdo);
    ensureRsvpsTable($pdo);

    return $pdo;
}

function ensureContentBlocksTable(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS content_blocks (
            content_key VARCHAR(120) PRIMARY KEY,
            content_value TEXT NOT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );
}

function ensureRsvpsTable(PDO $pdo): void
{
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
}

function fetchRsvps(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM rsvps ORDER BY id DESC')->fetchAll();
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
        'about_paragraph_1' => 'Él, un caballero de casco y botas de construcción, que lideraba obras civiles',
        'about_paragraph_2' => 'Ella, una dama de hermosa cabellera y vestido blanco',
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
        'story_1_text' => 'Lorem ipsum dolor sit amet consectetur. Pretium morbi id volutpat ut viverra vel. Non sit massa vitae penatibus sit velit quis massa. Bibendum odio quis feugiat erat sit velit. Magnis mi eros ante quis morbi. Ornare urna ultricies quisque id et.',
        'story_2_date' => '29 DEC, 2022',
        'story_2_title' => 'He proposed, I said Yes',
        'story_2_text' => '2. Lorem ipsum dolor sit amet consectetur. Pretium morbi id volutpat ut viverra vel. Non sit massa vitae penatibus sit velit quis massa. Bibendum odio quis feugiat erat sit velit. Magnis mi eros ante quis morbi. Ornare urna ultricies quisque id et.',
        'story_3_date' => '12 FEB, 2023',
        'story_3_title' => 'Our Engagement Day',
        'story_3_text' => '3. Lorem ipsum dolor sit amet consectetur. Pretium morbi id volutpat ut viverra vel. Non sit massa vitae penatibus sit velit quis massa. Bibendum odio quis feugiat erat sit velit. Magnis mi eros ante quis morbi. Ornare urna ultricies quisque id et.',
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
        'event_1_time' => '2:00 PM - 4:00 PM',
        'event_1_address' => 'Clark Hall Main Bolouward,<br>London',
        'event_1_location_button' => 'See Location 1',
        'event_1_location_url' => '#',
        'event_2_title' => 'The Reception',
        'event_2_date' => 'Monday 11 Jan, 2024',
        'event_2_time' => '4:00 PM - 10:00 PM',
        'event_2_address' => 'Clark Hall Main Bolouward, 2<br>London 2',
        'event_2_location_button' => 'See Location 2',
        'event_2_location_url' => '#',
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
        'image_logo' => 'assets/media/logo.png',
        'image_hero_left' => 'assets/media/banner/banner-img-1.png',
        'image_hero_right' => 'assets/media/banner/banner-img-2.png',
        'image_about_groom' => 'assets/media/about/grrom.png',
        'image_about_bride' => 'assets/media/about/bride.png',
        'image_countdown' => 'assets/media/coming-soon/Image.png',
        'image_story_1' => 'assets/media/story/s-1.png',
        'image_story_2' => 'assets/media/story/s-2.png',
        'image_story_3' => 'assets/media/story/s-3.png',
        'image_gallery_1' => 'assets/media/gallery/Image Frame.png',
        'image_gallery_2' => 'assets/media/gallery/Image-2.png',
        'image_gallery_3' => 'assets/media/gallery/Image-3.png',
        'image_gallery_4' => 'assets/media/gallery/Image-4.png',
        'image_gallery_5' => 'assets/media/gallery/Image-5.png',
        'image_gallery_6' => 'assets/media/gallery/Image.png',
        'image_gallery_7' => 'assets/media/gallery/Image-1.png',
        'image_blog_1' => 'assets/media/blogs/Image.png',
        'image_blog_2' => 'assets/media/blogs/Image-1.png',
        'image_blog_3' => 'assets/media/blogs/Image-2.png',
    ];
}

function imageContentKeys(): array
{
    return [
        'image_logo',
        'image_hero_left',
        'image_hero_right',
        'image_about_groom',
        'image_about_bride',
        'image_countdown',
        'image_story_1',
        'image_story_2',
        'image_story_3',
        'image_gallery_1',
        'image_gallery_2',
        'image_gallery_3',
        'image_gallery_4',
        'image_gallery_5',
        'image_gallery_6',
        'image_gallery_7',
        'image_blog_1',
        'image_blog_2',
        'image_blog_3',
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
