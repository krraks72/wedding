-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-03-2026 a las 16:44:21
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `wedding`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `content_blocks`
--

CREATE TABLE `content_blocks` (
  `content_key` varchar(120) NOT NULL,
  `content_value` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `content_blocks`
--

INSERT INTO `content_blocks` (`content_key`, `content_value`, `updated_at`) VALUES
('about_label', 'NOSOTROS', '2026-03-14 00:04:55'),
('about_paragraph_1', 'Él, un caballero de casco y botas de construcción, que lideraba obras civiles', '2026-03-13 23:57:47'),
('about_paragraph_2', 'Ella, una dama de hermosa cabellera y vestido blanco', '2026-03-13 23:57:47'),
('about_person_1', 'Juan Diaz', '2026-03-13 23:44:55'),
('about_person_2', 'Sara Lemus', '2026-03-13 23:44:55'),
('about_title', '¡Conócenos!', '2026-03-14 00:04:55'),
('blog_1_date', '10 junio, 2026', '2026-03-14 01:00:04'),
('blog_1_text', 'Un momento emotivo que tradicionalmente sigue al primer baile de los recién casados.', '2026-03-16 21:18:03'),
('blog_1_title', 'Dos corazones se convierten en uno: John y Sofie celebran su matrimonio.', '2026-03-16 21:18:03'),
('blog_2_date', '11 junio, 2026', '2026-03-14 01:00:04'),
('blog_2_title', 'Dinámicas diseñadas para conocerse mejor, como preguntar \"¿Cuál es la actividad favorita de tu pareja?\" para ver qué tan alineados están.', '2026-03-16 21:18:03'),
('blog_3_date', '12 junio, 2026', '2026-03-14 01:00:04'),
('blog_3_title', 'Salir a cenar o preparar una cena especial en casa con un cielo abierto o decoración diferente.\r\nAventura al Aire Libre: Realizar caminatas a ríos, picnics o incluso acampar juntos para desconectarse de la tecnología.', '2026-03-16 21:18:03'),
('blog_detail_author', 'EMILLIA WILLIAMS', '2026-03-15 16:08:37'),
('blog_detail_comment_1_date', '10 JAN, 2024', '2026-03-15 16:08:37'),
('blog_detail_comment_1_image', 'assets/media/users/image.png', '2026-03-15 16:08:37'),
('blog_detail_comment_1_name', 'Isabella Garcia', '2026-03-15 16:08:37'),
('blog_detail_comment_1_text', 'Lorem ipsum dolor sit amet consectetur. Ut ipsum vitae elementum dolor maecenas at convallis. Nulla eu vehicula commodo nunc pellentesque urna molestie amet arcu. Senectus nibh a maecenas sem ipsum id lacus.', '2026-03-15 16:08:37'),
('blog_detail_comment_2_date', '10 JAN, 2024', '2026-03-15 16:08:37'),
('blog_detail_comment_2_image', 'assets/media/users/image-1.png', '2026-03-15 16:08:37'),
('blog_detail_comment_2_name', 'Isabella Garcia', '2026-03-15 16:08:37'),
('blog_detail_comment_2_text', 'Lorem ipsum dolor sit amet consectetur. Ut ipsum vitae elementum dolor maecenas at convallis. Nulla eu vehicula commodo nunc pellentesque urna molestie amet arcu. Senectus nibh a maecenas sem ipsum id lacus.', '2026-03-15 16:08:37'),
('blog_detail_comment_3_date', '10 JAN, 2024', '2026-03-15 16:08:37'),
('blog_detail_comment_3_image', 'assets/media/users/image-2.png', '2026-03-15 16:08:37'),
('blog_detail_comment_3_name', 'Isabella Garcia', '2026-03-15 16:08:37'),
('blog_detail_comment_3_text', 'Lorem ipsum dolor sit amet consectetur. Ut ipsum vitae elementum dolor maecenas at convallis. Nulla eu vehicula commodo nunc pellentesque urna molestie amet arcu. Senectus nibh a maecenas sem ipsum id lacus.', '2026-03-15 16:08:37'),
('blog_detail_comments', 'COMMENTS 30', '2026-03-15 16:08:37'),
('blog_detail_comments_heading', 'Comments', '2026-03-15 16:08:37'),
('blog_detail_date', '10 JAN, 2024', '2026-03-15 16:08:37'),
('blog_detail_form_button', 'Send Message', '2026-03-15 16:08:37'),
('blog_detail_form_comment_placeholder', 'Write Your Comments...', '2026-03-15 16:08:37'),
('blog_detail_form_email_placeholder', 'Your email', '2026-03-15 16:08:37'),
('blog_detail_form_heading', 'LEAVE A COMMENT', '2026-03-15 16:08:37'),
('blog_detail_form_name_placeholder', 'Your Name', '2026-03-15 16:08:37'),
('blog_detail_form_subject_placeholder', 'Subject', '2026-03-15 16:08:37'),
('blog_detail_heading', 'Blog Detail', '2026-03-15 16:08:37'),
('blog_detail_image_1', 'assets/media/blogs/bd-2.png', '2026-03-15 16:08:37'),
('blog_detail_image_2', 'assets/media/blogs/bd-3.png', '2026-03-15 16:08:37'),
('blog_detail_image_main', 'assets/media/blogs/bd-1.png', '2026-03-15 16:08:37'),
('blog_detail_meta_description', 'Invitación bida de Juan y Sara', '2026-03-16 19:45:56'),
('blog_detail_page_title', 'Blessed wedding template', '2026-03-15 16:08:37'),
('blog_detail_paragraph_1', 'Lorem ipsum dolor sit amet consectetur. Ut ipsum vitae elementum dolor maecenas at convallis. Nulla eu vehicula commodo nunc pellentesque urna molestie amet arcu. Senectus nibh a maecenas sem ipsum id lacus. Pharetra commodo et vitae bibendum aliquet in amet. Sagittis ullamcorper diam varius non sed euismod vel. Ante luctus non eget mauris proin. Ut ipsum vitae elementum dolor maecenas at convallis. Nulla eu vehicula commodo nunc pellentesque urna molestie amet arcu. Senectus nibh.', '2026-03-15 16:08:37'),
('blog_detail_paragraph_2', 'Lorem ipsum dolor sit amet consectetur. Ut ipsum vitae elementum dolor maecenas at convallis. Nulla eu vehicula commodo nunc pellentesque urna molestie amet arcu. Senectus nibh a maecenas sem ipsum id lacus. Pharetra commodo et vitae bibendum aliquet in amet. Sagittis ullamcorper diam varius non sed euismod vel. Ante luctus non eget mauris proin. Ut ipsum vitae elementum dolor maecenas at convallis. Nulla eu vehicula commodo nunc pellentesque urna molestie amet arcu. Senectus nibh.', '2026-03-15 16:08:37'),
('blog_detail_quote', 'â€œLorem ipsum dolor sit amet consectetur. Ut ipsum vitae elementum dolor maecenas at convallis. Nulla eu vehicula commodo nunc pellentesque urna molestie amet arcu.â€', '2026-03-15 16:08:37'),
('blog_detail_reply_avatar', 'assets/media/users/image-2.png', '2026-03-15 16:08:37'),
('blog_detail_reply_label', 'Reply', '2026-03-15 16:08:37'),
('blog_detail_reply_placeholder', 'Write your comment', '2026-03-15 16:08:37'),
('blog_detail_reply_post', 'Post', '2026-03-15 16:08:37'),
('blog_detail_title', 'John and Sofie Embark on Their New Chapter', '2026-03-15 16:08:37'),
('blog_read_more', 'Leer más', '2026-03-14 01:04:33'),
('blogs_label', 'Nuestro BLOG', '2026-03-14 01:02:33'),
('blogs_title', 'Últimas Noticias', '2026-03-14 01:00:04'),
('count_days', 'Days', '2026-03-16 15:55:55'),
('count_hours', 'Hrs', '2026-03-16 15:55:55'),
('count_minutes', 'Min', '2026-03-16 15:55:55'),
('count_seconds', 'Sec', '2026-03-16 15:55:55'),
('countdown_date', '2026-06-10 18:00:00', '2026-03-16 15:55:55'),
('countdown_label', 'Llegó el día', '2026-03-13 23:44:55'),
('countdown_title', 'De nuestra Boda', '2026-03-14 00:04:55'),
('event_1_address', 'Bogotá, Colombia', '2026-03-16 21:07:27'),
('event_1_date', 'Domingo 14 junio, 2026', '2026-03-14 00:22:15'),
('event_1_location_button', 'Ver lugar', '2026-03-14 01:00:03'),
('event_1_location_url', 'https://maps.app.goo.gl/FhqZg94NzZKx5Qih9', '2026-03-14 22:32:38'),
('event_1_time', '2:00 PM', '2026-03-15 19:35:56'),
('event_1_title', 'La Ceremonia', '2026-03-14 00:22:15'),
('event_2_address', 'Cajicá, Cundinamarca', '2026-03-14 01:00:03'),
('event_2_date', 'Domingo 14 junio, 2026', '2026-03-14 01:00:03'),
('event_2_location_button', 'Ver lugar', '2026-03-14 01:00:04'),
('event_2_location_url', 'https://maps.app.goo.gl/FhqZg94NzZKx5Qih9', '2026-03-14 22:32:38'),
('event_2_time', '4:00 PM', '2026-03-15 19:35:56'),
('event_2_title', 'La Recepción', '2026-03-14 01:00:03'),
('events_label', 'Nuestra Boda', '2026-03-14 00:22:15'),
('events_title', 'Donde y Cuando', '2026-03-14 00:22:15'),
('footer_thanks', 'Muchas Gracias', '2026-03-14 00:12:22'),
('hero_couple', 'Juan y Sara', '2026-03-13 23:41:32'),
('hero_date', 'Nov 6, 2026', '2026-03-13 23:41:32'),
('hero_date_label', 'Llegó el día', '2026-03-13 23:41:32'),
('hero_paragraph', 'Esta es la pequeña historia de amor de Juan y Sara, jovenes enamorados que desean formar una Familia...', '2026-03-13 23:36:55'),
('hero_title', 'Todo lo que<br>se necesita<br>es AMOR', '2026-03-13 23:41:32'),
('image_about_bride', 'assets/media/about/bride.png', '2026-03-14 12:52:40'),
('image_about_groom', 'assets/media/about/grrom.png', '2026-03-14 12:52:40'),
('image_blog_1', 'assets/media/blogs/Image.png', '2026-03-14 22:00:03'),
('image_blog_2', 'assets/media/blogs/Image-1.png', '2026-03-14 12:52:40'),
('image_blog_3', 'assets/media/blogs/Image-2.png', '2026-03-14 12:52:40'),
('image_countdown', 'assets/media/coming-soon/Image.png', '2026-03-14 12:52:40'),
('image_gallery_1', 'assets/media/gallery/Image Frame.png', '2026-03-14 12:52:40'),
('image_gallery_2', 'assets/media/gallery/Image-2.png', '2026-03-14 12:52:40'),
('image_gallery_3', 'assets/media/gallery/Image-3.png', '2026-03-14 12:52:40'),
('image_gallery_4', 'assets/media/gallery/Image-4.png', '2026-03-14 12:52:40'),
('image_gallery_5', 'assets/media/gallery/Image-5.png', '2026-03-14 12:52:40'),
('image_gallery_6', 'assets/media/gallery/Image.png', '2026-03-14 12:52:40'),
('image_gallery_7', 'assets/media/gallery/Image-1.png', '2026-03-14 12:52:40'),
('image_hero_left', 'assets/media/banner/banner-img-1.png', '2026-03-14 12:52:40'),
('image_hero_right', 'assets/media/banner/banner-img-2.png', '2026-03-14 12:52:40'),
('image_logo', 'assets/media/logo.png', '2026-03-14 12:52:40'),
('image_story_1', 'assets/media/story/s-1.png', '2026-03-14 22:02:40'),
('image_story_2', 'assets/media/story/s-2.png', '2026-03-14 12:52:40'),
('image_story_3', 'assets/media/story/s-3.png', '2026-03-14 12:52:40'),
('invitation_button', 'ASISTENCIA', '2026-03-15 16:14:12'),
('invitation_countdown_date', '2026-04-10 18:00:00', '2026-03-16 19:34:57'),
('invitation_date', '10 junio, 2026', '2026-03-16 21:07:27'),
('invitation_label', 'El día Esperado', '2026-03-16 21:07:27'),
('invitation_location', 'Bogotá, Colombia', '2026-03-16 21:07:27'),
('invitation_meta_description', 'Invitación boda de Juan y Sara', '2026-03-16 21:07:27'),
('invitation_page_title', 'Blessed wedding template', '2026-03-15 16:08:37'),
('invitation_text', 'Nos casamos', '2026-03-16 21:07:27'),
('invitation_title', 'Juan y Sara', '2026-03-16 21:07:27'),
('location', 'Bogotá, Colombia', '2026-03-14 00:12:22'),
('meta_description', 'Invitación boda de Juan y Sara', '2026-03-16 20:26:20'),
('nav_about', 'NOSOTROS', '2026-03-13 23:34:53'),
('nav_blogs', 'Blogs', '2026-03-13 23:32:10'),
('nav_events', 'EVENTOS', '2026-03-13 23:41:32'),
('nav_gallery', 'GALERIA', '2026-03-13 23:34:53'),
('nav_invitation', 'INVITACION', '2026-03-13 23:41:32'),
('nav_rsvp', 'ASISTENCIA', '2026-03-13 23:34:53'),
('nav_story', 'HISTORIA', '2026-03-13 23:34:53'),
('page_title', 'Blessed wedding template', '2026-03-13 23:32:10'),
('rsvp_attend_no', 'Lo siento, no puedo asistir', '2026-03-14 00:22:15'),
('rsvp_attend_yes', 'Sí, asistiré', '2026-03-14 00:22:15'),
('rsvp_button', 'Enviar', '2026-03-14 00:22:15'),
('rsvp_guest_four', 'Tres', '2026-03-14 00:22:15'),
('rsvp_guest_label', 'Numero de invitados', '2026-03-14 00:22:15'),
('rsvp_guest_three', 'Dos', '2026-03-14 00:22:15'),
('rsvp_guest_two', 'Uno', '2026-03-14 00:22:15'),
('rsvp_meal_1', 'Buffet Style', '2026-03-13 23:32:10'),
('rsvp_meal_2', 'Food Stations', '2026-03-13 23:32:10'),
('rsvp_meal_3', 'Themed Cuisine', '2026-03-13 23:32:10'),
('rsvp_meal_4', 'Dessert Only', '2026-03-13 23:32:10'),
('rsvp_meal_label', 'Preferencia de Comida', '2026-03-14 00:22:15'),
('rsvp_paragraph', 'Por favor, confirmar asistencia antes del 5 de junio, 2026', '2026-03-14 01:03:39'),
('rsvp_title', '¿Asistirás?', '2026-03-14 01:03:39'),
('story_1_date', '22 junio, 2022', '2026-03-14 00:07:54'),
('story_1_text', 'Nuestros caminos se cruzaron al trabajar juntos en un aempresa en Bogotá, ella muy alegre y él muy serio; eso no fue un inconveniente, fue el inicio de miradas y sonrisas que no dejaron de suceder.', '2026-03-14 01:21:55'),
('story_1_title', '¿Cómo nos conocimos?', '2026-03-14 00:07:54'),
('story_2_date', '29 diciembre, 2024', '2026-03-14 00:07:54'),
('story_2_text', 'El incó su rodilla, sacó una caja de tercio pelo de su bolcillo y abriendola extendió sus manos sobre su cabeza mientras pronunciaba, ¿Quieres ser mi esposa?', '2026-03-14 01:21:55'),
('story_2_title', 'Él hizo la propuesta y ella dijo ¡Sí!', '2026-03-14 00:07:54'),
('story_3_date', '12 febrero, 2025', '2026-03-14 00:07:54'),
('story_3_text', 'Ahora estamos en un corre, corre... La vida nos alcanza y la boda está a la vuelta de la esquina.', '2026-03-14 01:21:55'),
('story_3_title', 'Nuestro esperado Día', '2026-03-14 01:21:55'),
('story_label', 'Nuestra historia', '2026-03-14 00:04:55'),
('story_title', 'Un cuento de Amor', '2026-03-14 00:07:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rsvps`
--

CREATE TABLE `rsvps` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `guests` int(11) DEFAULT NULL,
  `meal_preference` varchar(100) DEFAULT NULL,
  `attending` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rsvps`
--

INSERT INTO `rsvps` (`id`, `name`, `email`, `guests`, `meal_preference`, `attending`, `created_at`) VALUES
(1, 'Iván Rodriguez', 'krraks72@gmail.com', 4, 'food', 1, '2026-03-13 22:14:12'),
(2, 'juan perez', 'krraks72@gmail.com', 2, 'cuisine', 1, '2026-03-13 22:26:29'),
(3, 'Impuesto Valor Agregado', 'krraks72@gmail.com', 3, 'cuisine', 1, '2026-03-14 00:32:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `content_blocks`
--
ALTER TABLE `content_blocks`
  ADD PRIMARY KEY (`content_key`);

--
-- Indices de la tabla `rsvps`
--
ALTER TABLE `rsvps`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `rsvps`
--
ALTER TABLE `rsvps`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
