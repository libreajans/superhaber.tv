<?php if(!defined('APP')) die('...'); ?>

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `app_author` (
`author_id` int(11) NOT NULL,
`author_name` varchar(200) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`author_keyword` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`author_image` varchar(300) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`author_twitter` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`author_facebook` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`author_instagram` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`author_email` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`author_text` text COLLATE utf8mb4_turkish_ci,
`author_contact` text COLLATE utf8mb4_turkish_ci,
`author_notes` text COLLATE utf8mb4_turkish_ci,
`author_status` int(1) DEFAULT '0',
`author_show_index` int(1) NOT NULL DEFAULT '0',
`author_show_page` int(1) NOT NULL DEFAULT '0',
`author_order` int(11) NOT NULL DEFAULT '0',
`create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci ROW_FORMAT=DYNAMIC;


CREATE TABLE `app_comment` (
`comment_id` int(11) UNSIGNED NOT NULL,
`comment_content` int(11) NOT NULL,
`comment_author` varchar(128) COLLATE utf8mb4_turkish_ci NOT NULL,
`comment_ip` varchar(128) COLLATE utf8mb4_turkish_ci NOT NULL,
`comment_text` text COLLATE utf8mb4_turkish_ci NOT NULL,
`comment_status` int(1) NOT NULL DEFAULT '0',
`comment_template` int(1) NOT NULL,
`comment_aprover` int(11) NOT NULL DEFAULT '0',
`create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE `app_contact` (
`contact_id` int(11) UNSIGNED NOT NULL,
`contact_email` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`contact_text` text COLLATE utf8mb4_turkish_ci NOT NULL,
`contact_status` int(1) NOT NULL,
`contact_aprover` int(1) NOT NULL,
`create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE `app_content` (
`content_id` int(11) UNSIGNED NOT NULL,
`content_user` int(11) NOT NULL DEFAULT '0',
`content_author` int(11) DEFAULT '0',
`content_text` longtext COLLATE utf8mb4_turkish_ci,
`content_desc` text COLLATE utf8mb4_turkish_ci,
`content_title` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_title_outside` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_title_seo` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_title_url` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_image` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_image_manset` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_redirect` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_tags` varchar(1024) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_metadesc` varchar(1024) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_image_dir` varchar(11) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_status` int(1) NOT NULL DEFAULT '0',
`content_template` int(11) NOT NULL DEFAULT '0',
`content_comment_status` int(1) NOT NULL DEFAULT '1',
`content_manset_text_status` int(1) NOT NULL DEFAULT '1',
`content_cat_show_status` int(1) NOT NULL DEFAULT '1',
`content_ads_status` int(1) NOT NULL DEFAULT '1',
`content_google_status` int(1) NOT NULL DEFAULT '0',
`content_cat` int(11) NOT NULL DEFAULT '0',
`content_cat2` int(11) NOT NULL DEFAULT '0',
`content_type` int(11) NOT NULL DEFAULT '0',
`content_order` int(11) DEFAULT NULL,
`content_url` varchar(320) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_image_url` varchar(320) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_thumb_url` varchar(320) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_image_manset_url` varchar(320) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_time` timestamp NULL DEFAULT NULL,
`create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`change_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci ROW_FORMAT=DYNAMIC
PARTITION BY HASH (content_id)
PARTITIONS 10;

CREATE TABLE `app_gallery_images` (
`id` int(11) UNSIGNED NOT NULL,
`gallery_id` int(11) NOT NULL,
`photo_order` int(11) DEFAULT NULL,
`photo_image` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`photo_embed` text COLLATE utf8mb4_turkish_ci NOT NULL,
`photo_text` text COLLATE utf8mb4_turkish_ci,
`create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci ROW_FORMAT=DYNAMIC
PARTITION BY HASH (id)
PARTITIONS 10;

CREATE TABLE `app_page` (
`page_id` int(11) NOT NULL,
`page_title` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`page_text` text COLLATE utf8mb4_turkish_ci,
`page_status` int(11) NOT NULL DEFAULT '0',
`create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci ROW_FORMAT=DYNAMIC;


CREATE TABLE `app_user` (
`user_id` int(11) NOT NULL,
`user_email` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`user_pass` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`user_name` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`user_realname` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`user_avatar` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`user_auth` text COLLATE utf8mb4_turkish_ci,
`user_status` int(1) DEFAULT NULL,
`user_editor` int(1) DEFAULT '0',
`create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci ROW_FORMAT=DYNAMIC;


CREATE TABLE `app_user_log` (
`id` int(11) NOT NULL,
`user_id` int(11) NOT NULL DEFAULT '0',
`user_ip` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`user_action` varchar(512) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE `app_view` (
`id` int(11) UNSIGNED NOT NULL,
`content_view` int(11) NOT NULL DEFAULT '0',
`content_view_real` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci ROW_FORMAT=DYNAMIC
PARTITION BY HASH (id)
PARTITIONS 10;


ALTER TABLE `app_author`
ADD UNIQUE KEY `author_id` (`author_id`);

ALTER TABLE `app_comment`
ADD UNIQUE KEY `content_id` (`comment_id`);

ALTER TABLE `app_contact`
ADD UNIQUE KEY `id` (`contact_id`);

ALTER TABLE `app_content`
ADD UNIQUE KEY `content_id` (`content_id`),
ADD KEY `content_type` (`content_type`),
ADD KEY `content_cat` (`content_cat`),
ADD KEY `content_cat2` (`content_cat2`),
ADD KEY `content_image_manset` (`content_image_manset`(250)),
ADD KEY `content_image` (`content_image`(250)),
ADD KEY `content_status` (`content_status`),
ADD KEY `content_template` (`content_template`),
ADD KEY `content_user` (`content_user`),
ADD KEY `content_author` (`content_author`),
ADD KEY `content_cat_show_status` (`content_cat_show_status`);

ALTER TABLE `app_gallery_images`
ADD UNIQUE KEY `id` (`id`),
ADD KEY `image` (`photo_image`(250)),
ADD KEY `gallery_id` (`gallery_id`);

ALTER TABLE `app_page`
ADD UNIQUE KEY `page_id` (`page_id`);

ALTER TABLE `app_user`
ADD UNIQUE KEY `user_id` (`user_id`),
ADD UNIQUE KEY `user_email` (`user_email`),
ADD KEY `user_pass` (`user_pass`);

ALTER TABLE `app_user_log`
ADD UNIQUE KEY `id` (`id`),
ADD KEY `user_id` (`user_id`),
ADD KEY `user_ip` (`user_ip`);

ALTER TABLE `app_view`
ADD UNIQUE KEY `id` (`id`) USING BTREE;


ALTER TABLE `app_author`
MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
ALTER TABLE `app_comment`
MODIFY `comment_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `app_contact`
MODIFY `contact_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `app_content`
MODIFY `content_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `app_gallery_images`
MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `app_page`
MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;
ALTER TABLE `app_user`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `app_user_log`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(15, 'Metehan Demir', 'metehan-demir', '15.jpg', '', '', '', '', NULL, NULL, NULL, 1, 1, 1, 1, '2017-01-11 14:56:43');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(16, 'Mete Yarar', 'mete-yarar', '16.jpg', '', '', '', '', NULL, NULL, NULL, 1, 1, 1, 3, '2017-01-11 15:01:05');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(17, 'Ahmet Tezcan', 'ahmet-tezcan', '17.jpg', '', '', '', '', NULL, NULL, NULL, 1, 1, 1, 4, '2017-01-11 15:01:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(18, 'Metin Külünk', 'metin-kulunk', '18.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(19, 'Yasemin Yıldırım', 'yasemin-yildirim', '19.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(20, 'Mikdat Kadıoğlu', 'mikdat-kadioglu', '20.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(21, 'Hasan Eraslan', 'hasan-eraslan', '21.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(22, 'Bahadır Yenişehirlioğlu', 'bahadir-yenisehirlioglu', '22.jpg', '', '', '', '', NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(23, 'Nermin Taylan', 'nermin-taylan', '23.jpg', '', '', '', '', NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(24, 'Zakir Avşar', 'zakir-avsar', '24.jpg', '', '', '', '', NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(25, 'Hüseyin Kaya', 'huseyin-kaya', '25.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(27, 'Derya Tanık', 'derya-tanik', '27.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(28, 'Asuman Uğur', 'asuman-ugur', '28.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(29, 'Metin Boşnak', 'metin-bosnak', '29.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(30, 'Özlem Özcan', 'ozlem-ozcan', '30.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(31, 'Ayla İbar', 'ayla-ibar', '31.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(33, 'Reşat Çalışlar', 'resat-calislar', '33.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(47, 'İlhami Yangın', 'ilhami-yangin', '47.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(48, 'ibrahim Baran', 'ibrahim-baran', '48.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, '2017-01-06 23:45:09');
INSERT INTO `app_author` (`author_id`, `author_name`, `author_keyword`, `author_image`, `author_twitter`, `author_facebook`, `author_instagram`, `author_email`, `author_text`, `author_contact`, `author_notes`, `author_status`, `author_show_index`, `author_show_page`, `author_order`, `create_time`) VALUES(49, 'izzet Çapa', 'izzet-capa', '49.jpg', '', '', '', '', NULL, NULL, NULL, 1, 1, 1, 2, '2017-01-11 15:00:50');

INSERT INTO `app_user` (`user_id`, `user_email`, `user_pass`, `user_name`, `user_realname`, `user_avatar`, `user_auth`, `user_status`, `user_editor`, `create_time`) VALUES(1, 'info@superhaber.tv', 'a71bf971091d9202146bf51310ccea60', 'Haber Admin', 'Haber Admin', 'avatar4.png', 'a:44:{s:9:\"user_list\";s:1:\"1\";s:8:\"user_add\";s:1:\"1\";s:9:\"user_edit\";s:1:\"1\";s:11:\"user_delete\";s:1:\"1\";s:9:\"page_list\";s:1:\"1\";s:8:\"page_add\";s:1:\"1\";s:9:\"page_edit\";s:1:\"1\";s:11:\"page_delete\";s:1:\"1\";s:12:\"content_list\";s:1:\"1\";s:11:\"content_add\";s:1:\"1\";s:12:\"content_edit\";s:1:\"1\";s:14:\"content_delete\";s:1:\"1\";s:12:\"comment_list\";s:1:\"1\";s:11:\"comment_add\";s:1:\"1\";s:12:\"comment_edit\";s:1:\"1\";s:14:\"comment_delete\";s:1:\"1\";s:10:\"stats_list\";s:1:\"1\";s:9:\"stats_add\";s:1:\"1\";s:10:\"stats_edit\";s:1:\"1\";s:12:\"stats_delete\";s:1:\"1\";s:12:\"contact_list\";s:1:\"1\";s:11:\"contact_add\";s:1:\"1\";s:12:\"contact_edit\";s:1:\"1\";s:14:\"contact_delete\";s:1:\"1\";s:12:\"gallery_list\";s:1:\"1\";s:11:\"gallery_add\";s:1:\"1\";s:12:\"gallery_edit\";s:1:\"1\";s:14:\"gallery_delete\";s:1:\"1\";s:11:\"author_list\";s:1:\"1\";s:10:\"author_add\";s:1:\"1\";s:11:\"author_edit\";s:1:\"1\";s:13:\"author_delete\";s:1:\"1\";s:12:\"article_list\";s:1:\"1\";s:11:\"article_add\";s:1:\"1\";s:12:\"article_edit\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:8:\"log_self\";s:1:\"1\";s:10:\"log_others\";s:1:\"1\";s:10:\"log_anonim\";s:1:\"1\";s:13:\"user_truncate\";s:1:\"1\";s:16:\"content_truncate\";s:1:\"1\";s:16:\"comment_truncate\";s:1:\"1\";s:16:\"contact_truncate\";s:1:\"1\";s:14:\"content_manset\";s:1:\"1\";}', 9, 0, '2017-01-01 09:12:12');

INSERT INTO `app_page` (`page_id`, `page_title`, `page_text`, `page_status`, `create_time`) VALUES(101, 'Künye', '<table class=\"borderless\" style=\"width: 100%;\"><tbody><tr><td style=\"width: 230px;\"><strong>Şirket</strong></td><td style=\"width: 20px;\">:</td><td>Atlantik Medya Filmcilik Prodüksiyon Reklam Yayın San. ve Tic. Ltd. Şti.</td></tr><tr><td><strong>Sahibi ve Genel Yayın Yönetmeni<br /></strong></td><td>:</td><td>Cengiz ER</td></tr><tr><td><strong>Genel Koordinatör</strong></td><td>:</td><td>Berkin Türkoğlu</td></tr><tr><td><strong>Editörler</strong></td><td>:</td><td><p>Barış Özkan<br />Çağrı Alkan<br />Hava Asal</p></td></tr><tr><td><strong>Spor Editörü</strong></td><td>:</td><td>İlker Yılmaz</td></tr><tr><td><strong>Yayın Kurulu</strong></td><td>:</td><td>Cengiz Er<br />Ahmet Tezcan<br />Metehan Demir<br />Mete Yarar<br />Serkan Korkmaz<br />Mikdat Kadıoğlu</td></tr><tr><td><strong>Hukuk Danışmanı</strong></td><td>:</td><td><p>Av. Bekir Sinan Akboğa</p></td></tr><tr><td> </td><td> </td><td> </td></tr><tr><td><strong>Yazılım ve UX</strong></td><td>:</td><td>Sabri ÜNAL<br />Halil KILIÇARSLAN</td></tr><tr><td><strong>Yer Sağlayıcı</strong></td><td>:</td><td>Radore</td></tr><tr><td> </td><td> </td><td> </td></tr><tr><td><strong>Adres</strong></td><td>:</td><td>Hakimiyet-i Milliye Caddesi Vedat Kancal İş Merkezi N0:58/49-A Üsküdar/İstanbul</td></tr><tr><td><strong>Eposta</strong></td><td>:</td><td><a href=\"mailto:info@superhaber.tv\">info@superhaber.tv</a> - <a href=\"mailto:ihbar@suberhaber.tv\">ihbar@suberhaber.tv</a> - <a href=\"mailto:reklam@superhaber.tv\">reklam@superhaber.tv</a></td></tr><tr><td><strong>Tel</strong></td><td>:</td><td>0216 495 90 58</td></tr><tr><td> </td><td> </td><td> </td></tr><tr><td><strong>Berkin Türkoğlu</strong></td><td>:</td><td><p>Medyaya 1999 yılında, Türkiye\'nin ilk haber portalı, Vestelnet\'in projesi Ajan.com\'da editörlük yaparak adım attı. Çeşitli yayınevlerinde editör ve redaktör olarak görev yaptı. Habertürk, Vatan Gazetesi, Kanal D gibi medya kuruluşlarında, Ufuk Güldemir, Mehmet Ali Birand, Atılgan Bayar gibi nevi şahsına münhasır isimlerden haberciliği öğrenme fırsatı yakaladı. Bugün TV\'de 5 yılı aşkın süre editör olarak görev yaptıktan sonra, yaşanan siyasi gelişmeler üzerine görevinden istifa etti. 2015 yılından bu yana SuperHaber\'de editörlük yapıyor.</p></td></tr><tr><td><strong>Barış Özkan</strong></td><td>:</td><td>1998 yılında İstanbul Üniversitesi İletişim Fakültesi\'nden mezunu oldu. Gazeteciliğe 9. Kanal Spor Servisi\'nde spiker olarak başladı. Çeşitli kanallarda spor spikerligi yaptıktan sonra, haber programları hazırlayıp sundu, dış haberler, siyaset, gündem editörlükleri yaptı. Nethaber, kanald.com.tr internet sitelerinde editörlük görevlerini üstlendi.</td></tr><tr><td><strong>Çağrı Alkan</strong></td><td>:</td><td><p>Marmara Üniversitesi İletişim Fakültesi\'nden mezun oldu. Meslek hayatına Associated Press İstanbul bürosunda foto muhabirliği stajı ile başladı. İHA ve DHA ile Kanaltürk ve Habertürk televizyonlarında editör olarak gorev aldı.</p></td></tr><tr><td><strong>Hava Asal</strong></td><td>:</td><td><p>1995 yılında girdiği Marmara Üniversitesi Gazetecilik Bölümü\'nden 2000 yılında mezun oldu. Gazetecilik mesleğine Sabah Gazetesi\'nde muhabir olarak adım attı. Haber1.com, Medyafaresi, Medyaradar ve haberiyakala.com haber ve medya sitelerinde haber editörü olarak çalıştı.</p></td></tr><tr><td><strong>İlker Yılmaz</strong></td><td>:</td><td><p>Gazeteciliğe 2010 yılında Taraf Gazetesi\'nde başladı. 2011\'de TRT Spor internet sitesinde çalışmaya başlayan Yılmaz, aynı zamanda Hayatım Futbol online dergisinin koordinatörlüğünü üstlendi. İlker Yılmaz Ağustos 2015\'ten bu yana SuperHaber\'de spor editörlüğü görevini yapıyor.</p></td></tr><tr><td><strong>Levent Coşkun</strong></td><td>:</td><td><p>A.Ü. Güzel Sanatlar mezunu. Birçok TV kanalının kuruluşunda yer aldı. Sırasıyla; Ntv, Habertürk, A Haber\'de Kreatif Direktör olarak yöneticilik yaptı.</p></td></tr></tbody></table>', 1, '2017-01-01 09:12:12');
INSERT INTO `app_page` (`page_id`, `page_title`, `page_text`, `page_status`, `create_time`) VALUES(102, 'Kullanım Şartları', '<p>SuperHaber.tv ve SuperHaber.com web sitelerini ziyaret ederek, SuperHaber.tv ve SuperHaber.com web sitelerinin kullanım koşullarının tamamını okuduğunuzu, içeriğini tamamen anladığınızı; bu sayfada belirtilen ve SuperHaber.tv ve SuperHaber.com web sitelerinde (Bundan böyle SuperHaber olarak anılacak) belirtilecek olan tüm hususları kayıtsız ve şartsız olarak kabul ettiğinizi ve onayladığınızı kabul ederek, belirtilen bu hususlar ile ilgili olarak herhangi bir itiraz ve defi ileri sürmeyeceğinizi açıkça beyan ve taahhüt etmiş oluyorsunuz.</p><p>1. SuperHaber hizmetine bağlı tüm servislerin, alan adlarının, yazılım kodlarının, ara yüzlerinin, içeriklerinin, ürün incelemelerinin, videolarının, algoritmalarının, çizimlerinin, modellerinin, tasarımlarının ve diğer tüm fikri sınai hakların sahibi, (üçüncü partilerden sağlanan içerik ve uygulamalar hariç olmak üzere) Hakimiyet-i Milliye Caddesi Vedat Kancal İş Merkezi N0:58/49-A Üsküdar-İstanbul, adresinde mukim Atlantik Medya Filmcilik Prodüksiyon Reklam Yayın San. ve Tic. Ltd. Şti.\'dir. Sunulan servislerin yazılım, tasarım ve telif hakları tamamen SuperHaber\'e aittir. Servislerin ve hizmetlerin, bununla bağlantılı sayfaların kopyalanmasına, çoğaltılmasına ve yayılmasına, ayrıca tersine mühendislik işlemlerine tabi tutulmasına izin verilmemektedir.</p><p>2. SuperHaber tarafından üretilen (video, yazı, resim, haber, ses dosyası) içeriklerin, kaynak belirtilmek koşulu ile alıntılanmasına, akan (streaming) videoların gömülü kodlarının başka sitelerde kullanılmasına SuperHaber tarafından izin verilmektedir.</p><p>3. SuperHaber,</p><ol style=\"list-style-type: lower-roman;\"><li>internet, teknoloji ve dijital iş kültürü ile içerikler üretmek ve bunları internet üzerinden yayınlamak,</li><li>üyelerinin yayınladığı internet, teknoloji ve dijital iş kültürü ile ilgili her tür içeriğe ortam sağlamak,</li><li>tüm içeriklerini tavsiye ve bilgilendirme amaçlı olarak kamuya sunmak amacı taşımaktadır.</li></ol><p>4. SuperHaber, ziyaretçilerin ve üyelerin bilgilenmesi amacıyla sunulan içeriklerin kesin doğru olduğunu garanti ve taahhüt etmemekte ve bu içeriklerin sadece tavsiye ve bilgilendirme amaçlı olduğunu beyan etmektedir. SuperHaber, kendi ürettiği ve yayınladığı içeriklerden bizzat sorumlu olduğu gibi, ayrıca üye ve ziyaretçilerin ürettikleri ve yayınladıkları içeriklerden 5651 sayılı kanunun 5. Maddesi gereği hiçbir şekilde sorumluluk kabul etmemektedir.</p><p>5. SuperHaber, sunmuş olduğu hizmetlerin ve servislerin daha efektif kullanılabilmesi amacıyla bir çok 3. Parti kurum ve kuruluşlarla çeşitli şekillerde işbirliği yapabilir. Bu işbirliği; reklam, sponsorluk, izinli pazarlama, veri paylaşımı ve yasal diğer ticari yöntemlerle olabilir. SuperHaber, e.posta ile pazarlama faaliyetlerinde, kanunların öngördüğü şekilde izinli iletişimde bulunacağını, kullanıcının / üyenin isteği dışında iletişim ve pazarlama faaliyeti yapmayacağını, kullanıcının sistemden ücretsiz ve kolayca çıkabilmesini sağlayacak araçlar sunmayı beyan ve taahhüt eder.</p><p>6. SuperHaber, kullanıcı tarafından oluşturulan her türlü veri ve içeriğin hiç bir şekilde kontrolünü yapmamaktadır. Ancak kullanıcı(lar) tarafından, 3. şahıs ve kurumların kişilik hakları ile Türkiye Cumhuriyeti kanunları ve uluslararası anlaşmalar ile koruma altına alınan diğer hakların ihlal edilmesi halinde; ayrıca telif haklarını ihlal eden, müstehcenlik içeren, hakaret, küfür, küçük düşürücü ifadeler barındıran içeriklerin gönderilmesi; ayrıca SuperHaber hizmetlerinin ve servislerinin amacını aşan bir şekilde kullanılması halinde, SuperHaber hizmetleri derhal durdurma, ilgili içerikleri yayından çıkarma ve üyelik iptal etme hakkına sahiptir.</p><p>7. SuperHaber, sunduğu servislerin kesintisiz ve hatasız çalışması için elinde geleni yapacaktır. Ancak internet erişim hizmeti veren servis sağlayıcıların sistemlerinde meydana gelebilecek teknik arızalar, anlaşmalı olduğu 3. Partilerin sistemlerinde meydana gelecek aksaklıklar, sistem bakımları ve mücbir nedenler gibi vakalarda SuperHaber servisleri de bağlantılı olarak etkilenebilir. Bu gibi durumlarda SuperHaber servislerin etkin olarak işlemesi için gerekli tedbirleri alacaktır, fakat doğrudan müdahale yetkisi olmayan bağlantılı servis arızaları ve hataları için sorumluluk kabul etmemektedir.</p><p>8. SuperHaber, işbu şartların yürürlükte olduğu tarih itibariyle, tüm içeriklerini ve servislerini ücretsiz olarak sunmaktadır. İleride herhangi bir zamanda, sunmuş olduğu servislerden ve içeriklerden bedelli ve/veya bedelsiz olarak yararlandırma hakkına sahiptir. Bedelli hizmetler ve abonelik türleri, SuperHaber sitesinde “hizmetler” bölümünde ayrıca belirtilmiş olabilir. Kullanıcıların ilan edilen abonelik sistemlerinden birini seçmesi halinde sunulan hizmet ve özelliklerini okuması gerekmektedir. Ücretli veya ücretsiz herhangi bir şekilde yararlanmada üye / kullanıcı, SuperHaber’ın taahhüt ettiği hizmetten fazlasını talep edemez.</p><p>9. SuperHaber, dileği takdirde sunmuş olduğu hizmetler üzerinde en iyi hizmeti verme amacı ile her zaman tek taraflı olarak değişiklik yapabilir.</p><p>10. SuperHaber sitesine üye olurken verilen bilgilerin doğruluğundan ve gizliliğinden, ayrıca şifre ve kullanıcı adının korunmasından, başka kimselerle paylaşılmamasından kullanıcının kendisi sorumludur. Bu bilgilerin yetkisiz kişiler tarafından ele geçirilmesi ve SuperHaber servislerinin kullanılması halinde SuperHaber sorumluluk kabul etmemektedir.</p><p>11. SuperHaber, değişen yasalar ve koşullara göre kullanıcı sözleşmesini her zaman tek taraflı olarak değiştirebilir, yürürlükten kaldırabilir veya yeni bir sürümünü yayınlayabilir.</p><p>12. SuperHaber ve kullanıcı arasında ortaya çıkabilecek her türlü hukuki ihtilaf öncelikle iyi niyet çerçevesinde çözümlenmeye çalışacaktır, aksi halde taraflar İstanbul Mahkemeleri ve İcra Dairelerinin yetkili olduğunu kabul ederler. Tarafınıza sunulan kullanım kuralları, Kullanıcının kayıtlı üye olması / siteye girmesi, içeriklerden ve servislerden yararlanmaya başlaması ile her iki taraf arasında kabul edilmiş sayılır. Kullanım şartları kabul edilmediği takdirde, kullanıcının siteyi ve servisleri kullanmaması yeterlidir.</p>', 1, '2017-01-01 09:12:12');
INSERT INTO `app_page` (`page_id`, `page_title`, `page_text`, `page_status`, `create_time`) VALUES(103, 'Gizlilik Bildirimi', '<p>1. SuperHaber.tv ve SuperHaber.com web siteleri (Bundan böyle SuperHaber olarak anılacak), gizliliğinizi korumak ve kullanılmakta bulunan teknolojik altyapıdan en üst seviyede yararlanmanızı sağlayabilmek amacıyla; kişisel bilgi ve veri güvenliğiniz için çeşitli gizlilik ilkeleri benimsemiştir. Bu gizlilik ilkeleri dahilinde SuperHaber internet sitesi ve tüm alt internet sitelerinde veri toplanması ve/veya kullanımı konusunda uygulanmak üzere belirlenmiş ve beyan edilmektedir.</p><p>2. SuperHaber internet sitesini ziyaret etmekle ve/veya kullanmakla ve/veya üye olmakla belirtilen bu ilkelerin tümü kullanıcı tarafından kabul edilmiş sayılır. SuperHaber sayfasında belirtilen iletişim adreslerinden birisi ile kendisine yapılacak geri bildirimler doğrultusunda, “Gizlilik Bildirimi” bölümünde düzeltme ve güncelleme gibi işlemleri, önceden bildirmeksizin her zaman yapabilir. SuperHaber “Gizlilik Bildirimi” bölümünü belli aralıklarla güncelleyebilir ve kullanıcı belli aralıklarla bu bölümü ziyaret ederek yeni gelişmelerden haberdar olabilir.</p><p>3. SuperHaber, ziyaretçileri ya da kullanıcıları tarafından SuperHaber.tv ve SuperHaber.com web siteleri üzerinden kendisine elektronik ortamdan iletilen her türden kişisel bilgileri ve verileri üçüncü kişilere hiç bir şekilde açıklamayacaktır. SuperHaber, sunmuş olduğu hizmetlerin ve servislerin daha efektif kullanılabilmesi amacıyla bir çok 3. Parti kurum ve kuruluşlarla çeşitli şekillerde işbirliği yapabilir. Bu işbirliği; reklam, sponsorluk, izinli pazarlama, veri paylaşımı ve yasal diğer ticari yöntemlerle olabilir. SuperHaber, iletişim faaliyetlerinde, kanunların öngördüğü şekilde izinli iletişim / pazarlama yapacağını, kullanıcının isteği dışında iletişim yapmamayı, kullanıcının sistemden ücretsiz ve kolayca çıkabilmesini sağlayacak araçlar sunmayı beyan ve taahhüt eder. SuperHaber, kendisine iletilen kişisel verileri ve bilgileri, bilgilerin toplanması ile ilgili açıklanan yukarıdaki amaçlar dışında üçüncü kişilerle kesinlikle paylaşmayacak, satışını yapmayacak ve hiç bir şart altında kullanılmasına izin vermeyecektir.</p><p>4. Sitedeki sistemle ilgili sorunların tanımlanabilmesi ve SuperHaber sitesinde çıkabilecek muhtemel sorunların acil olarak giderilmesi için, SuperHaber gerektiğinde kullanıcıların IP adresini kaydedebilir ve bu kayıtları anılan bu amaçlarla kullanabilir. Bu IP adresleri, SuperHaber tarafından kullanıcılarını ve ziyaretçilerini genel anlamda tanımlamak ve kapsamlı şekilde demografik veri toplayabilmek amacıyla kullanılabilir. SuperHaber sitesinin 5651 sayılı yasada belirtilen trafik verisi saklama yükümlülükleri ayrıca saklıdır.</p><p>5. SuperHaber’a kayıt olarak üye sıfatının kazanılması için veya üye olmaksızın çeşitli servis ve içeriklerden faydalanabilmesi için, ziyaretçilerin kendileriyle ilgili bir takım kişisel bilgilerini (örnek olarak: isim ve soy isim, telefon numarası, posta adresi, e-posta adresi vb.) formlar aracılığıyla SuperHaber’a vermeleri gerekmektedir. SuperHaber’ın, kayıt sırasında talep edebileceği bu bilgiler sistemde kayıtlı olarak tutulabilir. İletilen bu kişisel bilgiler, gerektiğinde kullanıcılarla iletişime geçmek amacıyla da kullanılabilir. SuperHaber tarafından talep edilebilecek bilgiler veya kullanıcı tarafından aktarılan bilgiler veya Site üzerinden yapılan işlemlerde ortaya koyulan ilgili bilgiler SuperHaber ve işbirliği içinde olduğu diğer kişi ve kurumlar tarafından kullanıcının kimliği hiç bir şekilde açığa çıkarılmaksızın sadece çeşitli istatistiki değerlendirmeler, izinli iletişim ve pazarlama, veri tabanı oluşturma çabaları ve pazar araştırmaları yapma gibi durumlar dahilinde kullanılabilir.</p><p>6. SuperHaber, site içerisinde başkaca sitelere link (bağlantı) sağlayabilir. Anlaşmalı olduğu 3. Partilerin reklamlarını ve/veya çeşitli hizmetlere ilişkin başvuru formlarını yayınlayabilir, Kullanıcıları bu formlar ve reklamlar aracılığıyla reklam veren veya anlaşmalı 3. partilerin sitesine yönlendirebilir. SuperHaber, bu bağlantı yoluyla erişilen diğer sitelerin gizlilik uygulama ve politikalarına ya da barındırdıkları içeriklere yönelik olmak üzere hiç bir sorumluluk taşımamaktadır.</p><p>7. Kullanıcıya ait kişisel bilgiler, ad ve soy ad, adres, telefon numarası, elektronik posta adresi ve kullanıcıyı tanımlamaya yönelik diğer her türlü bilgi olarak anlaşılır. SuperHaber, işbu gizlilik bildiriminde aksi belirtilmedikçe kişisel bilgilerden herhangi birini SuperHaber’nin işbirliği içinde olmadığı şirketlere ve üçüncü kişilere hiç bir şekilde açıklamayacaktır. SuperHaber, aşağıda sayılan hallerde ise işbu gizlilik bildirimi hükümleri dışına çıkarak kullanıcılara ait bilgileri üçüncü kişilere açıklayabilecektir. Bu durumlar;</p><ol style=\"list-style-type: lower-roman;\"><li>Kanun, KHK, Yönetmelik vb. yetkili hukuki makamlar tarafından çıkarılan ve yürürlükte bulunan hukuk kurallarının getirdiği zorunluluklara uyulmasının gerektiği haller,</li><li>SuperHaber’ın kullanıcılarıyla arasındaki sözleşmelerin gereklerinin yerine getirilmesi ve bunların uygulamaya konulmalarıyla ilgili hallerde,</li><li>Yetkili idari ve/veya adli makamlar tarafından usuli yöntemine uygun olarak yürütülen bir araştırma veya soruşturma doğrultusunda kullanıcılarla ilgili bilgi talep edilmesi hallerinde,</li><li>Kullanıcıların haklarını veya güvenliklerini koruma amacıyla bilgi verilmesinin gerekli olduğu hallerde.</li></ol><p>8. SuperHaber, kendisine verilen gizli bilgileri kesinlikle özel ve gizli tutmayı, bunu bir sır olarak saklamayı yükümlülük olarak kabul ettiğini ve gizliliğin sağlanıp sürdürülmesi, gizli bilginin tamamının veya herhangi bir parçasının kamu alanına girmesini veya yetkisiz kullanımını veya üçüncü bir kişiye açıklanmasını önleme gereği olan gerekli tüm tedbirleri almayı ve üzerine düşen tüm özeni tam olarak göstermeyi işbu bildirimle taahhüt etmektedir.</p><p>9. SuperHaber, kullanıcılar ve kullanıcıların SuperHaber sitesi kullanımı hakkındaki bilgileri, teknik bir iletişim dosyası olan çerezler (Cookie) kullanarak elde edebilir. Bahsi geçen teknik iletişim dosyaları, ana bellekte saklanmak üzere bir internet sitesinin, kullanıcının tarayıcısına (browser) gönderdiği minik metin dosyalarından ibarettir. Teknik iletişim dosyası bir internet sitesi hakkında kullanıcının bilgisayarındaki ilgili durum ve basit tercih ayarlarını saklayarak internet kullanımını bu anlamda kolaylaştırır. Bu bahsedilen teknik iletişim dosyası, internet sitesini zamansal oranlamalı olarak kaç kişinin kullandığını, bir kişinin ilgili internet sitesini hangi amaçla, kaç kez ziyaret ettiği ve ne kadar kaldığı hakkında istatistiki bilgiler elde etmeye ve kullanıcılar için özel olarak tasarlanmış kullanıcı sayfalarından dinamik çeşitlilikle reklam ve içerik üretilmesine yardımcı olmak üzere tasarlanmış ve kullanılmaktadır. Teknik iletişim dosyası, ana bellekten başkaca herhangi bir kişisel bilgi almak için tasarlanmamıştır. Tarayıcıların pek çoğu kurulum aşamasında bu teknik iletişim dosyasını kabul eder biçimde hazırlanmışlardır, ancak kullanıcılar dilerlerse teknik iletişim dosyasının bilgisayarlarına yerleştirilmemesi veya bu türden bir dosyasının gönderildiğinde ikaz verilmesini sağlayacak biçimde tarayıcılarının ayarlarını her zaman değiştirebilirler.</p><p>10. SuperHaber tarafından site içerisinde düzenlenebilecek periyodik veya periyodik olmayan anketlere cevap veren kullanıcılardan talep edilebilecek bilgiler de, SuperHaber ve işbirliği içindeki kişi ya da kurumlar tarafından bu kullanıcılara doğrudan pazarlama yapmak, istatistiki analizler yapmak ve özel bir veri tabanı oluşturmak amacıyla da kullanılabilmektedir.</p><p>11. SuperHaber, işbu gizlilik bildiriminde geçen hükümleri gerekli gördüğü anlarda SuperHaber sitesinde sayfasında yayınlamak şartıyla değiştirebilir. SuperHaber’ın değişiklik yaptığı gizlilik bildirimi hükümleri, ilgili sayfada yayınlandığı tarihte yürürlüğe girmiş kabul edilirler.</p><p>12. SuperHaber, gizliliğinizi korumak ve kullanılmakta bulunan teknolojik altyapıdan en üst seviyede yararlanmanızı sağlayabilmek amacıyla; kişisel bilgi ve veri güvenliğiniz için çeşitli gizlilik ilkeleri benimsemiştir. Bu gizlilik ilkeleri dahilinde SuperHaber internet sitesi ve tüm alt internet sitelerinde veri toplanması ve/veya kullanımı konusunda uygulanmak üzere belirlenmiş ve beyan edilmektedir.</p><p>13. SuperHaber internet sitesini ziyaret etmekle ve/veya kullanmakla ve/veya üye olmakla belirtilen bu ilkelerin tümü Kullanıcı tarafından kabul edilmiş sayılır. SuperHaber sayfasında belirtilen iletişim adreslerinden birisi ile kendisine yapılacak geri bildirimler doğrultusunda, “Gizlilik Bildirimi” bölümünde düzeltme ve güncelleme gibi işlemleri, önceden bildirmeksizin her zaman yapabilir. SuperHaber “Gizlilik Bildirimi” bölümünü belli aralıklarla güncelleyebilir ve kullanıcı belli aralıklarla bu bölümü ziyaret ederek yeni gelişmelerden haberdar olabilir.</p><p>14. SuperHaber, ziyaretçileri ya da kullanıcıları tarafından SuperHaber.tv ve SuperHaber.com web siteleri üzerinden kendisine elektronik ortamdan iletilen her türden kişisel bilgileri ve verileri üçüncü kişilere hiç bir şekilde açıklamayacaktır. SuperHaber, sunmuş olduğu hizmetlerin ve servislerin daha efektif kullanılabilmesi amacıyla bir çok 3. Parti kurum ve kuruluşlarla çeşitli şekillerde işbirliği yapabilir. Bu işbirliği; reklam, sponsorluk, izinli pazarlama, veri paylaşımı ve yasal diğer ticari yöntemlerle olabilir. SuperHaber, iletişim faaliyetlerinde, kanunların öngördüğü şekilde izinli iletişim / pazarlama yapacağını, kullanıcının isteği dışında iletişim yapmamayı, kullanıcının sistemden ücretsiz ve kolayca çıkabilmesini sağlayacak araçlar sunmayı beyan ve taahhüt eder. SuperHaber, kendisine iletilen kişisel verileri ve bilgileri, bilgilerin toplanması ile ilgili açıklanan yukarıdaki amaçlar dışında üçüncü kişilerle kesinlikle paylaşmayacak, satışını yapmayacak ve hiç bir şart altında kullanılmasına izin vermeyecektir.</p><p>15. Sitedeki sistemle ilgili sorunların tanımlanabilmesi ve SuperHaber sitesinde çıkabilecek muhtemel sorunların acil olarak giderilmesi için, SuperHaber gerektiğinde kullanıcıların IP adresini kaydedebilir ve bu kayıtları anılan bu amaçlarla kullanabilir. Bu IP adresleri, SuperHaber tarafından kullanıcılarını ve ziyaretçilerini genel anlamda tanımlamak ve kapsamlı şekilde demografik veri toplayabilmek amacıyla kullanılabilir. SuperHaber sitesinin 5651 sayılı yasada belirtilen trafik verisi saklama yükümlülükleri ayrıca saklıdır.</p><p>16. SuperHaber’a kayıt olarak üye sıfatının kazanılması için veya üye olmaksızın çeşitli servis ve içeriklerden faydalanabilmesi için, ziyaretçilerin kendileriyle ilgili bir takım kişisel bilgilerini (örnek olarak: isim ve soy isim, telefon numarası, posta adresi, e-posta adresi vb.) formlar aracılığıyla SuperHaber’a vermeleri gerekmektedir. SuperHaber’ın, kayıt sırasında talep edebileceği bu bilgiler sistemde kayıtlı olarak tutulabilir. İletilen bu kişisel bilgiler, gerektiğinde kullanıcılarla iletişime geçmek amacıyla da kullanılabilir. SuperHaber tarafından talep edilebilecek bilgiler veya kullanıcı tarafından aktarılan bilgiler veya site üzerinden yapılan işlemlerde ortaya koyulan ilgili bilgiler SuperHaber ve işbirliği içinde olduğu diğer kişi ve kurumlar tarafından kullanıcının kimliği hiç bir şekilde açığa çıkarılmaksızın sadece çeşitli istatistiki değerlendirmeler, izinli iletişim ve pazarlama, veri tabanı oluşturma çabaları ve pazar araştırmaları yapma gibi durumlar dahilinde kullanılabilir.</p><p>17. SuperHaber, site içerisinde başkaca sitelere link (bağlantı) sağlayabilir. Anlaşmalı olduğu 3. Partilerin reklamlarını ve/veya çeşitli hizmetlere ilişkin başvuru formlarını yayınlayabilir, Kullanıcıları bu formlar ve reklamlar aracılığıyla reklam veren veya anlaşmalı 3. partilerin sitesine yönlendirebilir. SuperHaber, bu bağlantı yoluyla erişilen diğer sitelerin gizlilik uygulama ve politikalarına ya da barındırdıkları içeriklere yönelik olmak üzere hiç bir sorumluluk taşımamaktadır.</p><p>18. Kullanıcıya ait kişisel bilgiler, ad ve soy ad, adres, telefon numarası, elektronik posta adresi ve kullanıcıyı tanımlamaya yönelik diğer her türlü bilgi olarak anlaşılır. SuperHaber, işbu gizlilik bildiriminde aksi belirtilmedikçe kişisel bilgilerden herhangi birini SuperHaber’ın işbirliği içinde olmadığı şirketlere ve üçüncü kişilere hiç bir şekilde açıklamayacaktır. SuperHaber, aşağıda sayılan hallerde ise işbu gizlilik bildirimi hükümleri dışına çıkarak kullanıcılara ait bilgileri üçüncü kişilere açıklayabilecektir. Bu durumlar;</p><ol style=\"list-style-type: lower-roman;\"><li>Kanun, KHK, Yönetmelik vb. yetkili hukuki makamlar tarafından çıkarılan ve yürürlükte bulunan hukuk kurallarının getirdiği zorunluluklara uyulmasının gerektiği haller,</li><li>SuperHaber’ın kullanıcılarıyla arasındaki sözleşmelerin gereklerinin yerine getirilmesi ve bunların uygulamaya konulmalarıyla ilgili hallerde,</li><li>Yetkili idari ve/veya adli makamlar tarafından usuli yöntemine uygun olarak yürütülen bir araştırma veya soruşturma doğrultusunda kullanıcılarla ilgili bilgi talep edilmesi hallerinde,</li><li>Kullanıcıların haklarını veya güvenliklerini koruma amacıyla bilgi verilmesinin gerekli olduğu hallerde.</li></ol><p>19. SuperHaber, kendisine verilen gizli bilgileri kesinlikle özel ve gizli tutmayı, bunu bir sır olarak saklamayı yükümlülük olarak kabul ettiğini ve gizliliğin sağlanıp sürdürülmesi, gizli bilginin tamamının veya herhangi bir parçasının kamu alanına girmesini veya yetkisiz kullanımını veya üçüncü bir kişiye açıklanmasını önleme gereği olan gerekli tüm tedbirleri almayı ve üzerine düşen tüm özeni tam olarak göstermeyi işbu bildirimle taahhüt etmektedir.</p><p>20. SuperHaber, kullanıcılar ve kullanıcıların SuperHaber sitesi kullanımı hakkındaki bilgileri, teknik bir iletişim dosyası olan çerezler (Cookie) kullanarak elde edebilir. Bahsi geçen teknik iletişim dosyaları, ana bellekte saklanmak üzere bir internet sitesinin, kullanıcının tarayıcısına (browser) gönderdiği minik metin dosyalarından ibarettir. Teknik iletişim dosyası bir internet sitesi hakkında kullanıcının bilgisayarındaki ilgili durum ve basit tercih ayarlarını saklayarak internet kullanımını bu anlamda kolaylaştırır. Bu bahsedilen teknik iletişim dosyası, internet sitesini zamansal oranlamalı olarak kaç kişinin kullandığını, bir kişinin ilgili internet sitesini hangi amaçla, kaç kez ziyaret ettiği ve ne kadar kaldığı hakkında istatistiki bilgiler elde etmeye ve kullanıcılar için özel olarak tasarlanmış kullanıcı sayfalarından dinamik çeşitlilikle reklam ve içerik üretilmesine yardımcı olmak üzere tasarlanmış ve kullanılmaktadır. Teknik iletişim dosyası, ana bellekten başkaca herhangi bir kişisel bilgi almak için tasarlanmamıştır. Tarayıcıların pek çoğu kurulum aşamasında bu teknik iletişim dosyasını kabul eder biçimde hazırlanmışlardır, ancak kullanıcılar dilerlerse teknik iletişim dosyasının bilgisayarlarına yerleştirilmemesi veya bu türden bir dosyasının gönderildiğinde ikaz verilmesini sağlayacak biçimde tarayıcılarının ayarlarını her zaman değiştirebilirler.</p><p>21. SuperHaber tarafından site içerisinde düzenlenebilecek periyodik veya periyodik olmayan anketlere cevap veren kullanıcılardan talep edilebilecek bilgiler de, SuperHaber ve işbirliği içindeki kişi ya da kurumlar tarafından bu kullanıcılara doğrudan pazarlama yapmak, istatistiki analizler yapmak ve özel bir veri tabanı oluşturmak amacıyla da kullanılabilmektedir.</p><p>22. SuperHaber, işbu gizlilik bildiriminde geçen hükümleri gerekli gördüğü anlarda SuperHaber sitesinde sayfasında yayınlamak şartıyla değiştirebilir. SuperHaber’ın değişiklik yaptığı gizlilik bildirimi hükümleri, ilgili sayfada yayınlandığı tarihte yürürlüğe girmiş kabul edilirler.</p>', 1, '2017-01-01 09:12:12');
INSERT INTO `app_page` (`page_id`, `page_title`, `page_text`, `page_status`, `create_time`) VALUES(104, 'Hukuka Aykırılık Bildirimi', '<p>SuperHaber.tv ve SuperHaber.com web sitelerinde (Bundan böyle SuperHaber olarak anılacak), hukuka ve yasalara saygılı olduğu gibi, kamunun, iş ortaklarının ve üçüncü kişilerin fikri sınai haklarına ve kişilik haklarına saygılı olmayı benimsemiştir.</p><p>SuperHaber, üyelerinin ve kullanıcılarının oluşturdukları içerik bakımından 5651 sayılı \"İnternet Ortamında Yapılan Yayınların Düzenlenmesi ve Bu Yayınlar Yoluyla İşlenen Suçlarla Mücadele edilmesi Hakkında Kanun\"da tanımlanan \"içerik sağlayıcı\" olarak hizmet vermektedir.</p><p>SuperHaber’ın kullanıcılarının ve üyelerinin oluşturduğu içeriklerin hukuka aykırı olup olmadığını kontrol etme yükümlülüğü yoktur. SuperHaber, hukuka aykırı içerikler ile 3. Kişilerin haklarının ihlal edildiği düşünülen içeriklerde \"UYAR ve KALDIR\" yöntemini benimsemiştir.</p><p>SuperHaber içerisindeki bazı içeriklerin kişilik haklarını, fikri ve sınai hakları ihlal ettiği iddiasında olan gerçek ve tüzel kişiler, komşu veya bağlantılı hak sahipleri ya da meslek birlikleri,</p><p>- İhlale konu içeriklerin URL adresi ve ihlale konu içeriğin konusunu,<br /> - Gerçek kişi ise kimliğini gösterir belge, tüzel kişi ise oda kayıt belgesi, meslek birliği ise yetkili kişinin imzası bulunan antetli bir talep yazısını,<br /> - Vekaletle işlem yapma yetkisi kullanılıyorsa vekaletnameyi,<br /> - Fikri ve sınai haklara ilişkin taleplerde hak sahibi olduğunu gösteren belgeyi,<br /> - Açık ad / ünvan ve açık iletişim adreslerini</p><p>Sunmak kaydıyla SuperHaber’in editorsuperhaber@gmail.com adresine bildirim yapabilirler. Bu elektronik iletişim adresine ulaşan talep ve şikayetler hukuk servisi tarafından incelenecek, gerekli görüldüğü takdirde ihlale konu içerikler SuperHaber sisteminden en kısa sürede çıkarılacak ve muhataba bilgi verilecektir.</p>', 1, '2017-01-01 09:12:12');
INSERT INTO `app_page` (`page_id`, `page_title`, `page_text`, `page_status`, `create_time`) VALUES(105, 'İletişim', '<table style=\"width: 350px;\"><tbody><tr><td><strong>Adres</strong></td><td>:</td><td><p>Hakimiyet-i Milliye Caddesi<br />Vedat Kancal İş Merkezi <br />N0:58/49-A <br /> Üsküdar-İstanbul</p></td></tr><tr><td><strong>Tel</strong></td><td>:</td><td>0216 495 90 58</td></tr><tr><td><strong>E-Posta</strong></td><td> </td><td>editor@superhaber.tv<br /> ihbar@superhaber.tv<br /> reklam@superhaber.tv</td></tr></tbody></table><p> </p>', 1, '2017-01-01 09:12:12');


-- partition ekle
ALTER TABLE app_content partition by hash(content_id) partitions 10;
ALTER TABLE app_gallery_images partition by hash(id) partitions 10;
ALTER TABLE app_view partition by hash(id) partitions 10;


-- comment_template kaldır

ALTER TABLE `app_comment` DROP `comment_template`;


-- some stupid idea

ALTER TABLE `app_content` CHANGE `content_seo_metadesc` `content_seo_metadesc` VARCHAR(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NULL DEFAULT NULL;
ALTER TABLE `app_content` CHANGE `content_tags` `content_tags` VARCHAR(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NULL DEFAULT NULL;

-- galeri width height değerleri eklendi

ALTER TABLE `app_gallery_images` ADD `photo_width` INT(4) NOT NULL DEFAULT '630' AFTER `photo_order`;
ALTER TABLE `app_gallery_images` ADD `photo_height` INT(4) NOT NULL DEFAULT '370' AFTER `photo_width`;
