-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Hoszt: localhost
-- Létrehozás ideje: 2012. aug. 15. 16:16
-- Szerver verzió: 5.5.25a
-- PHP verzió: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Adatbázis: `gearoscope_new`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_bands`
--

CREATE TABLE IF NOT EXISTS `gearoscope_bands` (
  `band_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `active` int(1) NOT NULL,
  `date` int(11) NOT NULL,
  `band_name` varchar(255) NOT NULL,
  `formation_year` int(4) NOT NULL,
  `style` int(3) NOT NULL,
  `website` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `band_photo_url` varchar(255) NOT NULL,
  `active_member` int(1) NOT NULL,
  `date_modified` int(11) NOT NULL,
  PRIMARY KEY (`band_id`),
  FULLTEXT KEY `band_name_2` (`band_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- A tábla adatainak kiíratása `gearoscope_bands`
--

INSERT INTO `gearoscope_bands` (`band_id`, `user_id`, `active`, `date`, `band_name`, `formation_year`, `style`, `website`, `description`, `band_photo_url`, `active_member`, `date_modified`) VALUES
(1, 5, 1, 1334917392, 'Superbutt', 2002, 2, 'www.superbutt.net', '', '1334917392.jpg', 0, 0),
(2, 5, 1, 1334917560, 'Autumn Twilight', 2000, 1, 'www.autumntwilight.hu', '', '1334917560.jpg', 0, 0),
(3, 5, 1, 1339335007, 'Agregator', 2000, 3, 'www.agregator.hu', '', '1339335007.jpg', 0, 0),
(5, 23, 1, 1344524475, 'Neck Sprain', 1992, 1, 'necksprain.hu', 'Acer has a new affordable Android option out in the UK today. ', '1344525435.jpg', 0, 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_band_editors`
--

CREATE TABLE IF NOT EXISTS `gearoscope_band_editors` (
  `band_editor_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `band_id` int(11) NOT NULL,
  `active` int(1) NOT NULL COMMENT '0 - pending, 1 - acitve',
  `code` varchar(32) NOT NULL,
  PRIMARY KEY (`band_editor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- A tábla adatainak kiíratása `gearoscope_band_editors`
--

INSERT INTO `gearoscope_band_editors` (`band_editor_id`, `user_id`, `band_id`, `active`, `code`) VALUES
(16, 5, 3, 1, ''),
(18, 16, 3, 0, ''),
(24, 21, 1, 0, '1d65bd745760bf78069598dfa051bcc3'),
(23, 22, 1, 0, '6d97506abef2c7f0498a931c864dc4f5'),
(25, 23, 5, 1, '24750b340162135231d71c48906d78ec'),
(26, 22, 5, 1, '809287dafb0e8b69513d8bd3c04646be'),
(27, 21, 5, 1, '389a50f2f3813005be54572a05728b62');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_comments`
--

CREATE TABLE IF NOT EXISTS `gearoscope_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `gear_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `created_date` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- A tábla adatainak kiíratása `gearoscope_comments`
--

INSERT INTO `gearoscope_comments` (`comment_id`, `gear_id`, `user_id`, `description`, `created_date`) VALUES
(1, 4, 23, 'Szép ez a cintányér!', 1344954517),
(2, 4, 23, 'Szerintem is szép!', 1344954930),
(3, 4, 23, 'Nagyon jó vagyok!', 1344955125),
(4, 4, 23, 'Félelmetesen jó vagyok!', 1344955146),
(5, 4, 23, 'Köcsög vagyok!', 1344955160),
(6, 1, 23, 'Nem is ez a kép való ide!', 1345035317),
(7, 1, 23, 'Na, ez a kép már jó.', 1345035371);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_content_nodes`
--

CREATE TABLE IF NOT EXISTS `gearoscope_content_nodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `node` varchar(50) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_gears`
--

CREATE TABLE IF NOT EXISTS `gearoscope_gears` (
  `gear_id` int(11) NOT NULL AUTO_INCREMENT,
  `gears_category_id` int(11) NOT NULL,
  `gears_subcategory_id` int(11) NOT NULL,
  `gears_subsubcategory_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gear_name` varchar(255) NOT NULL,
  `serial_number` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `gear_photo_url` varchar(255) NOT NULL,
  `gear_thumbnail_url` varchar(255) NOT NULL,
  `active` int(1) NOT NULL,
  `featured` int(1) NOT NULL,
  `create_date` int(11) NOT NULL,
  `last_edit_date` int(11) NOT NULL,
  PRIMARY KEY (`gear_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- A tábla adatainak kiíratása `gearoscope_gears`
--

INSERT INTO `gearoscope_gears` (`gear_id`, `gears_category_id`, `gears_subcategory_id`, `gears_subsubcategory_id`, `user_id`, `gear_name`, `serial_number`, `description`, `gear_photo_url`, `gear_thumbnail_url`, `active`, `featured`, `create_date`, `last_edit_date`) VALUES
(1, 1, 1, 8, 23, 'Ibanez S5470F Prestige', 'S5470F', 'The cutting edge of Ibanez design, the S series, continues to be a marvel of form and function. Its signature body shape - sculpted, lightweight, and mahogany - is stronger and more musically responsive than guitars weighing twice as much. Though no longer called by its original name, the Saber, the S series is still a rock ''n'' roll version of that quick, graceful and potentially lethal weapon. The S Prestige is "Made in Japan" by the finest Ibanez craftsmen and from top-of-the-line components.', 'resize_1345035334.png', 'thumbnail_1345035334.png', 1, 0, 1344608618, 1345035360),
(2, 1, 1, 8, 23, 'Ibanez S570', 'S570', 'The Ibanez S series first appeared in 1987. Ever since, it has changed in appearance and function to represent the cutting edge of Ibanez design. Famous for its lightweight carved mahogany body, the S can take a beating while still providing the resonance of guitars twice its size. The S series comes equipped with the ZR tremolo system, featuring a smooth ball bearing pivot for ultra-smooth arm control - same as ZR-2 tremolo system.', 'resize_1344608797.png', 'thumbnail_1344608797.png', 1, 0, 1344608721, 1344608857),
(3, 2, 6, 9, 23, 'Meinl MB 10 18" Crash', 'MB10-18MC-B', 'Bright, warm and well-balanced sound in a wide dynamic range. Full, even and soft responsive feel with an extensive spread. Versatile, all-purpose classic rock crash.', 'resize_1344889828.jpg', 'thumbnail_1344889828.jpg', 1, 0, 1344847169, 1344889946),
(4, 2, 6, 11, 1, 'Turkish Sehzade Hi-hat 13"', 'SH-H', 'Great chick performance. Warm, dark, earthy. Round & full sound. These soft and sensible hi-hats have excellent stick definition with a woody feel.', 'resize_1344872403.png', 'thumbnail_1344872403.png', 1, 0, 1344864223, 1344872422);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_gears_categories`
--

CREATE TABLE IF NOT EXISTS `gearoscope_gears_categories` (
  `gears_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  PRIMARY KEY (`gears_category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- A tábla adatainak kiíratása `gearoscope_gears_categories`
--

INSERT INTO `gearoscope_gears_categories` (`gears_category_id`, `category`) VALUES
(1, 'Pengetős'),
(2, 'Ütős'),
(3, 'Vonós'),
(4, 'Elektronikus');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_gears_subcategories`
--

CREATE TABLE IF NOT EXISTS `gearoscope_gears_subcategories` (
  `gears_subcategory_id` int(11) NOT NULL AUTO_INCREMENT,
  `subcategory` varchar(255) NOT NULL,
  `gears_category_id` int(11) NOT NULL,
  PRIMARY KEY (`gears_subcategory_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- A tábla adatainak kiíratása `gearoscope_gears_subcategories`
--

INSERT INTO `gearoscope_gears_subcategories` (`gears_subcategory_id`, `subcategory`, `gears_category_id`) VALUES
(1, 'Gitár', 1),
(2, 'Basszusgitár', 1),
(3, 'Citera', 1),
(4, 'Dobfelszerelés', 2),
(5, 'Erősítők', 4),
(6, 'Cintányérok', 2);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_gears_subsubcategories`
--

CREATE TABLE IF NOT EXISTS `gearoscope_gears_subsubcategories` (
  `gears_subsubcategory_id` int(11) NOT NULL AUTO_INCREMENT,
  `subsubcategory` varchar(255) NOT NULL,
  `gears_subcategory_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`gears_subsubcategory_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- A tábla adatainak kiíratása `gearoscope_gears_subsubcategories`
--

INSERT INTO `gearoscope_gears_subsubcategories` (`gears_subsubcategory_id`, `subsubcategory`, `gears_subcategory_id`, `user_id`) VALUES
(1, 'Fender', 1, 0),
(2, 'Gibson', 1, 0),
(3, 'Tama', 4, 0),
(4, 'Basix Custom', 4, 5),
(5, 'Invasion', 1, 1),
(6, 'Invasion', 2, 1),
(7, 'ESP', 1, 23),
(8, 'Ibanez', 1, 23),
(9, 'Meinl', 6, 23),
(10, 'Sabian', 6, 23),
(11, 'Turkish', 6, 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_images`
--

CREATE TABLE IF NOT EXISTS `gearoscope_images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `gear_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `created_date` int(11) NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- A tábla adatainak kiíratása `gearoscope_images`
--

INSERT INTO `gearoscope_images` (`image_id`, `gear_id`, `url`, `created_date`) VALUES
(1, 3, 'gallery_3_23_DV019_Jpg_Regular_449868005080001_USD1.jpg', 1344889930),
(2, 3, 'gallery_3_23_meinl-mb10-mb10-20mr-b.jpg', 1344889931);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_instruments`
--

CREATE TABLE IF NOT EXISTS `gearoscope_instruments` (
  `instrument_id` int(11) NOT NULL AUTO_INCREMENT,
  `instrument_name` int(11) NOT NULL,
  PRIMARY KEY (`instrument_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_members`
--

CREATE TABLE IF NOT EXISTS `gearoscope_members` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `instrument_id` int(11) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_messages`
--

CREATE TABLE IF NOT EXISTS `gearoscope_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_type_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_message_types`
--

CREATE TABLE IF NOT EXISTS `gearoscope_message_types` (
  `message_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_text` text NOT NULL,
  PRIMARY KEY (`message_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_news`
--

CREATE TABLE IF NOT EXISTS `gearoscope_news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL,
  `guid` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `dateModified` int(11) NOT NULL,
  `authors` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`news_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

--
-- A tábla adatainak kiíratása `gearoscope_news`
--

INSERT INTO `gearoscope_news` (`news_id`, `active`, `guid`, `title`, `description`, `dateModified`, `authors`, `link`, `content`) VALUES
(1, 1, 4470408, 'Luna – Hangmás-klippremier', '<p><img alt="" src="http://langologitarok.blog.hu/media/image/hangmas_luna_video_01.jpg" />Elkészült a Hangmás legújabb videoklipje Luna című számukhoz, ami a harmadik lemezük második videója és amit az <a href="http://www.facebook.com/pages/The-Anormal-Sessions/261055663932316" target="_blank">Anormal Sessions csapata</a> készített a zenekarnak Vandad Kashefi rendezésében, míg az operatőr Győri Márk, a vágó pedig  Makk Lili voltak. Ez az Anormal Sessions nyolcadik videója, amit ezúttal a bezárt Lipótmezei Elme- és  Ideggyógyintézetben  forgattak, a zenét pedig szokásos módon élőben vették fel hozzá. A klipben ennek ellenére mégsem a zenélő együttest láthatjuk, hanem a dalhoz egy rövidfilm készült, aminek főszereplője Hőrich Nóra fiatal színésznő, aki a Papírrepülők című filmből lehet ismerős. A klipet a múlt héten a Titanic Filmfesztiválon a Quimby és Lajkó Félix új kisfilmjei mellett mutatták be, a nagyközönség számára viszont most először itt a Lángoló Gitárokon látható. A lapozás után található videó mellett az együttes frontemberének, valamint a klip főszereplőjének nyilatkozatai is olvashatók a forgatás részleteiről.</p>', 1335261600, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/24/luna_hangmas_klippremier', '<p><img alt="" src="http://langologitarok.blog.hu/media/image/hangmas_luna_video_01.jpg" />Elkészült a Hangmás legújabb videoklipje Luna című számukhoz, ami a harmadik lemezük második videója és amit az <a href="http://www.facebook.com/pages/The-Anormal-Sessions/261055663932316" target="_blank">Anormal Sessions csapata</a> készített a zenekarnak Vandad Kashefi rendezésében, míg az operatőr Győri Márk, a vágó pedig  Makk Lili voltak. Ez az Anormal Sessions nyolcadik videója, amit ezúttal a bezárt Lipótmezei Elme- és  Ideggyógyintézetben  forgattak, a zenét pedig szokásos módon élőben vették fel hozzá. A klipben ennek ellenére mégsem a zenélő együttest láthatjuk, hanem a dalhoz egy rövidfilm készült, aminek főszereplője Hőrich Nóra fiatal színésznő, aki a Papírrepülők című filmből lehet ismerős. A klipet a múlt héten a Titanic Filmfesztiválon a Quimby és Lajkó Félix új kisfilmjei mellett mutatták be, a nagyközönség számára viszont most először itt a Lángoló Gitárokon látható. A lapozás után található videó mellett az együttes frontemberének, valamint a klip főszereplőjének nyilatkozatai is olvashatók a forgatás részleteiről.</p>'),
(2, 1, 4470668, 'Bury My Bones - Az egykori Gallows-frontember új zenekarának első videója', '<p><img alt="" src="http://langologitarok.blog.hu/media/image/pure_love_bury_my_bones_video_02.jpg" /></p>\n<p>Tavaly nyáron valószínűleg sokan csodálkozva olvasták, hogy az egyre népszerűbb Gallows zenekart otthagyta a frontembere, Frank Carter, aki a brit hardcore egyik legfelkapottabb együttesétől a harmadik lemez készítése közben vált el, mivel <a href="http://www.nme.com/news/gallows/59941">más irányt képzelt el zeneileg</a>, mint a többiek. A Gallows végül elcsaklizta a jó ideje szétesésben lévő Alexisonfire-ből Wade McNeil gitáros-vokalistát, Frank Carter pedig belekezdett új zenei projektjébe a <strong>Pure Love</strong>-ba. Aki mindezek után esetleg azt gondolta, hogy a <a class="noplay" href="http://www.youtube.com/watch?v=PMktrXD_w3c">magát néha a színpadon tetováltató</a> Carter valami még dühösebb anyagot fog kihozni új zenekarával,  minden bizonnyal meg fog lepődni a következő videó láttán. A hajtás után a <em>Bury My Bones</em> című dal videoklipje látható.</p>\n', 1335270600, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/24/bury_my_bones_az_ex_gallows_frontember_uj_zenekaranak_elso_videoja', '<p><img alt="" src="http://langologitarok.blog.hu/media/image/pure_love_bury_my_bones_video_02.jpg" /></p>\n<p>Tavaly nyáron valószínűleg sokan csodálkozva olvasták, hogy az egyre népszerűbb Gallows zenekart otthagyta a frontembere, Frank Carter, aki a brit hardcore egyik legfelkapottabb együttesétől a harmadik lemez készítése közben vált el, mivel <a href="http://www.nme.com/news/gallows/59941">más irányt képzelt el zeneileg</a>, mint a többiek. A Gallows végül elcsaklizta a jó ideje szétesésben lévő Alexisonfire-ből Wade McNeil gitáros-vokalistát, Frank Carter pedig belekezdett új zenei projektjébe a <strong>Pure Love</strong>-ba. Aki mindezek után esetleg azt gondolta, hogy a <a class="noplay" href="http://www.youtube.com/watch?v=PMktrXD_w3c">magát néha a színpadon tetováltató</a> Carter valami még dühösebb anyagot fog kihozni új zenekarával,  minden bizonnyal meg fog lepődni a következő videó láttán. A hajtás után a <em>Bury My Bones</em> című dal videoklipje látható.</p>\n'),
(3, 1, 4470794, 'Black Eyed Peasre házasodnak legtöbben Nagy-Britanniában', '<p style="text-align: left;"><img alt="" src="http://langologitarok.blog.hu/media/image/blackeyedpeas(1).jpg" style="width: 550px; height: 367px;" /></p> <p>A Spotify online zenei szolgáltató felmérése szerint a <strong>Black Eyed Peas</strong> <em>I Gotta Feeling</em> című dala a leggyakoribb választás a brit esküvőkön. A 78 000 lejátszási lista alapján összeállított sorrend szerint az elmúlt években csökkent a népszerűsége az olyan klasszikusoknak, mint a Beatles vagy a Rolling Stones dalai, de a <em>YMCA </em>sem megy már olyan jól, mint régen. A második helyen egyébként a Kings of Leon <em>Sex on Fire</em>-je található, amit a The Killers <em>Mr Brightside</em>-ja követ. A néhol (további) meglepetéseket is tartalmazó húszas listát végig lehet böngészni a kattintás után.</p> ', 1335273300, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/24/black_eyed_peasre_hazasodnak_legtobben_nagy_britanniaban', '<p style="text-align: left;"><img alt="" src="http://langologitarok.blog.hu/media/image/blackeyedpeas(1).jpg" style="width: 550px; height: 367px;" /></p> <p>A Spotify online zenei szolgáltató felmérése szerint a <strong>Black Eyed Peas</strong> <em>I Gotta Feeling</em> című dala a leggyakoribb választás a brit esküvőkön. A 78 000 lejátszási lista alapján összeállított sorrend szerint az elmúlt években csökkent a népszerűsége az olyan klasszikusoknak, mint a Beatles vagy a Rolling Stones dalai, de a <em>YMCA </em>sem megy már olyan jól, mint régen. A második helyen egyébként a Kings of Leon <em>Sex on Fire</em>-je található, amit a The Killers <em>Mr Brightside</em>-ja követ. A néhol (további) meglepetéseket is tartalmazó húszas listát végig lehet böngészni a kattintás után.</p> '),
(4, 1, 4470931, 'Szarból aranyat – Jack White-lemezkritika', '<h3><img src="http://langologitarok.blog.hu/media/image/jack_white_blunderbuss_cover_01.jpg" style="width: 200px; height: 197px;" alt="" />Jack White – Blunderbuss<br /> (Third Man/Columbia)</h3> <p>Már kicsit több mint egy éve kell nélkülöznünk a White Stripest, ami nekem például nagy szívfájdalmam, mert a kéttagú zenekar még a kevésbé jó pillanataiban is olyan megbízhatóan magas minőséget hozott, aminek köszönhetően el tudtam hinni, hogy Jack White korunk azon kevés zenészei közé tartozik, akik még a szart is arannyá változtatják. Ezt az elképzeléseimet csak tovább erősítették a mellékzenekarok (Raconteours, Dead Weather), de még az olyan bukást sejtető kollaborációk is, mint a közös dal Alicia Keysszel.</p>', 1335276900, '_fá_', 'http://langologitarok.blog.hu/2012/04/24/szarbol_aranyat_jack_white_lemezkritika', '<h3><img src="http://langologitarok.blog.hu/media/image/jack_white_blunderbuss_cover_01.jpg" style="width: 200px; height: 197px;" alt="" />Jack White – Blunderbuss<br /> (Third Man/Columbia)</h3> <p>Már kicsit több mint egy éve kell nélkülöznünk a White Stripest, ami nekem például nagy szívfájdalmam, mert a kéttagú zenekar még a kevésbé jó pillanataiban is olyan megbízhatóan magas minőséget hozott, aminek köszönhetően el tudtam hinni, hogy Jack White korunk azon kevés zenészei közé tartozik, akik még a szart is arannyá változtatják. Ezt az elképzeléseimet csak tovább erősítették a mellékzenekarok (Raconteours, Dead Weather), de még az olyan bukást sejtető kollaborációk is, mint a közös dal Alicia Keysszel.</p>'),
(5, 1, 4472098, 'Törölte idei turnéterveit Sinéad O''Connor', '', 1335333600, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/25/torolte_idei_turneterveit_sinead_o_connor', ''),
(6, 1, 4472168, 'Jay-Z és Kanye West niggerezős dala a francia elnökválasztási kampányban', '<p><img src="http://langologitarok.blog.hu/media/image/jay_z_kanye_west_01.jpg" style="width: 550px; height: 391px;" alt="" />Javában zajlik a francia elnökválasztás, ahol az első kör után Francois Hollande vezet a regnáló Nicolas Sarkozy előtt. Hollande ráadásul meglepően furcsa kampányeszközhöz nyúlt: az elnökjelölt Jay-Z és Kanye West közös szerzeményét, a Niggas In Parist használta fel egyik kampányvideójához, amivel főleg a perifériára szorult kisebbséget kívánta megcélozni, ami addig Sarkozynek nem igazán sikerült. A dal szövegének egyébként annyi köze van Párizshoz, hogy az öt eltöltött idejük ihlette a szerzőket, valójában viszont nem szól másról, mint kosárlabdás hasonlatokról, a szokásos kivagyiskodásról, illetve például arról, hogy Kanye West Vilmos herceg helyében inkább az Olsen ikreket vette volna feleségül. Felmerülhet a kérdés, miért nem a virágzó hip-hop kultúrájú Franciaországból választott magának zenét a politikus stábja, amire a választ ugyan nem tudjuk, annyi viszont a hírekből kiderül, hogy Hollande tanácsadói részletesen kielemezték Obama 2008-as kampányát. Ezzel kapcsolatban érdemes megjegyezni, hogy bár Obama többször is hangot adott már annak, hogy kedveli a fent említett rappereket, nála 2008-ban a leggyakrabban felhangzó dal azért egy jóval kevésbé balhés U2-szám volt. A lapozás után a szóban forgó kampányvideó látható és az eredeti dalt is odatettük, aminek remek videójáról egyébként <a href="http://langologitarok.blog.hu/2012/02/12/vegyuk_be_a_kocsit_a_zenekarba_klipmegosztas" target="_blank">itt írtunk</a> bővebben.</p> ', 1335337200, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/25/jay_z_es_kanye_west_niggerezos_dala_a_francia_elnokvalasztasi_kampanyban', '<p><img src="http://langologitarok.blog.hu/media/image/jay_z_kanye_west_01.jpg" style="width: 550px; height: 391px;" alt="" />Javában zajlik a francia elnökválasztás, ahol az első kör után Francois Hollande vezet a regnáló Nicolas Sarkozy előtt. Hollande ráadásul meglepően furcsa kampányeszközhöz nyúlt: az elnökjelölt Jay-Z és Kanye West közös szerzeményét, a Niggas In Parist használta fel egyik kampányvideójához, amivel főleg a perifériára szorult kisebbséget kívánta megcélozni, ami addig Sarkozynek nem igazán sikerült. A dal szövegének egyébként annyi köze van Párizshoz, hogy az öt eltöltött idejük ihlette a szerzőket, valójában viszont nem szól másról, mint kosárlabdás hasonlatokról, a szokásos kivagyiskodásról, illetve például arról, hogy Kanye West Vilmos herceg helyében inkább az Olsen ikreket vette volna feleségül. Felmerülhet a kérdés, miért nem a virágzó hip-hop kultúrájú Franciaországból választott magának zenét a politikus stábja, amire a választ ugyan nem tudjuk, annyi viszont a hírekből kiderül, hogy Hollande tanácsadói részletesen kielemezték Obama 2008-as kampányát. Ezzel kapcsolatban érdemes megjegyezni, hogy bár Obama többször is hangot adott már annak, hogy kedveli a fent említett rappereket, nála 2008-ban a leggyakrabban felhangzó dal azért egy jóval kevésbé balhés U2-szám volt. A lapozás után a szóban forgó kampányvideó látható és az eredeti dalt is odatettük, aminek remek videójáról egyébként <a href="http://langologitarok.blog.hu/2012/02/12/vegyuk_be_a_kocsit_a_zenekarba_klipmegosztas" target="_blank">itt írtunk</a> bővebben.</p> '),
(7, 1, 4472234, 'Átlag éjjel - Itt a Tej első dala, táncoltat az IHM énekese', '<p><em><img src="http://langologitarok.blog.hu/media/image/tej_ihm_bol_vagva.jpg" alt="" />Átlag éjjel</em> címmel itt a <strong>Tej </strong>első bemutatható állapotba került dala. A Tej alapját Pálinkás Tamás Isten Háta Mögött-frontember akusztikus dalai adják, amiket Sándor Dániel Isten Háta Mögött-billentyűs-gitáros-producer közreműködésével tánczenévé formáltak. Pálinkás a Lángoló Gitároknak elmondta, hogy egyelőre nem tudja pontosan miről is szól a szám, „de sajnos nagyon valószínű, hogy a kopulációról.” Pálinkás fejében már évek óta megy ez a „dancefloor-cucc”, és elmondása alapján kevésbé érzi magát hülyén, ha kiadja a kezéből. A Tej tervei közé tartozik, hogy „olyan kultusz alakuljon ki a zenekar körül, mint a 90-es évek elején a Bonanza körül, majd hirtelen feloszlás, szólólemez, Kossuth-díj.” Egyébként Pálinkás gitár nélkül teljesen bepánikol a koncerteken, így az első nem hivatalos Tej-bulin háttal énekelt a közönségnek. A nagylemez még egyáltalán nem téma, jönnek ki a dalok sorban, aztán majd lesz valami. Hajtás után ott az <em>Átlag éjjel</em>.</p> ', 1335348000, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/25/atlag_ejjel_itt_a_tej_elso_dala_tancoltat_az_ihm_enekese', '<p><em><img src="http://langologitarok.blog.hu/media/image/tej_ihm_bol_vagva.jpg" alt="" />Átlag éjjel</em> címmel itt a <strong>Tej </strong>első bemutatható állapotba került dala. A Tej alapját Pálinkás Tamás Isten Háta Mögött-frontember akusztikus dalai adják, amiket Sándor Dániel Isten Háta Mögött-billentyűs-gitáros-producer közreműködésével tánczenévé formáltak. Pálinkás a Lángoló Gitároknak elmondta, hogy egyelőre nem tudja pontosan miről is szól a szám, „de sajnos nagyon valószínű, hogy a kopulációról.” Pálinkás fejében már évek óta megy ez a „dancefloor-cucc”, és elmondása alapján kevésbé érzi magát hülyén, ha kiadja a kezéből. A Tej tervei közé tartozik, hogy „olyan kultusz alakuljon ki a zenekar körül, mint a 90-es évek elején a Bonanza körül, majd hirtelen feloszlás, szólólemez, Kossuth-díj.” Egyébként Pálinkás gitár nélkül teljesen bepánikol a koncerteken, így az első nem hivatalos Tej-bulin háttal énekelt a közönségnek. A nagylemez még egyáltalán nem téma, jönnek ki a dalok sorban, aztán majd lesz valami. Hajtás után ott az <em>Átlag éjjel</em>.</p> '),
(8, 1, 4472642, 'Sven Väth, Tinie Tempah és a Knife Party is a Soundon', '<p><img alt="" style="width: 550px; height: 329px;" src="http://langologitarok.blog.hu/media/image/sven_vath_2012_01.jpg" />Napról napra jönnek az újabb nevek a nyári fesztiválokra, ma épp a Balaton Sound dobott be új fellépőket. A korábban már bejelentett zenekarok, előadók és DJ-k mellett ma kiderült, hogy jön a Balaton partjára a német techno emblematikus figurájának számító veterán lemezlovas, a fenti képen mosolygó Sven Väth, a Pendulum alapítóinak electro-house projektje, a Knife Party, valamint a Brit Awardson már kétszer is kitüntetet Tinie Tempah. A lapozás után eláruljuk a részleteket és további neveket sorolunk.</p>', 1335351600, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/25/sven_v_th_tinie_tempah_es_a_knife_party_is_a_soundon', '<p><img alt="" style="width: 550px; height: 329px;" src="http://langologitarok.blog.hu/media/image/sven_vath_2012_01.jpg" />Napról napra jönnek az újabb nevek a nyári fesztiválokra, ma épp a Balaton Sound dobott be új fellépőket. A korábban már bejelentett zenekarok, előadók és DJ-k mellett ma kiderült, hogy jön a Balaton partjára a német techno emblematikus figurájának számító veterán lemezlovas, a fenti képen mosolygó Sven Väth, a Pendulum alapítóinak electro-house projektje, a Knife Party, valamint a Brit Awardson már kétszer is kitüntetet Tinie Tempah. A lapozás után eláruljuk a részleteket és további neveket sorolunk.</p>'),
(9, 1, 4472776, 'Nyerd meg a Hogyan játsszunk basszusgitáron? című könyvet!', '', 1335355200, '[X]', 'http://langologitarok.blog.hu/2012/04/25/nyerd_meg_a_hogyan_jatsszunk_basszusgitaron_cimu_konyvet', ''),
(10, 1, 4472754, 'Dorfmeisterrel nyit Budapest legnagyobb szabadtéri szórakozóhelye', '<p><strong><img src="http://langologitarok.blog.hu/media/image/park_logo.jpg" alt="" width="300" />Richard Dorfmeister</strong> downtempo-legendával nyitja kapuit Budapest legújabb szórakozóhelye a <strong>Park</strong>. A május 3-i nyitónap Dorfmeister mellett a jamaikai <strong>Ras MC T-Weed</strong> is fellép. Az akár 12 ezer ember befogadására is alkalmas, szállítmányozási konténerekből épült szórakoztató-központ öt különböző helyszínen kínál majd programokat elektronikus produkcióktól kezdve élőzenén át a retródiszkóig. A Park öt hónapig üzemel majd és naponta délután öttől, másnap hajnali ötig tartanak a programok. Az eddig bejelentett külföldi fellépők listáján szerepel Boban Markovic, a két drum and bass sztár Andy C és Goldie, a hazánkba rendszeresen visszajáró Dub Pistols és a volt faithlesses Sister Bliss. Emellett rengeteg magyar előadó is fellép majd a Parkban, köztük egy közös slam poetry esttel és élőzenei kísérettel jelentkező  Punnany Massif, Hősök, DSP és az Akkezdet Phiai, de lesz ’90-es évek parti is a Hip Hop Boyzzal, Ámokfutókkal, Happy Ganggel és az UFÓ-val, ahogy Nagy Feró és a Beatrice illetve a Bikini is fellép majd a Parkban.</p> ', 1335357000, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/25/dorfmeisterrel_nyit_budapest_legnagyobb_szabadteri_szorakozohelye', '<p><strong><img src="http://langologitarok.blog.hu/media/image/park_logo.jpg" alt="" width="300" />Richard Dorfmeister</strong> downtempo-legendával nyitja kapuit Budapest legújabb szórakozóhelye a <strong>Park</strong>. A május 3-i nyitónap Dorfmeister mellett a jamaikai <strong>Ras MC T-Weed</strong> is fellép. Az akár 12 ezer ember befogadására is alkalmas, szállítmányozási konténerekből épült szórakoztató-központ öt különböző helyszínen kínál majd programokat elektronikus produkcióktól kezdve élőzenén át a retródiszkóig. A Park öt hónapig üzemel majd és naponta délután öttől, másnap hajnali ötig tartanak a programok. Az eddig bejelentett külföldi fellépők listáján szerepel Boban Markovic, a két drum and bass sztár Andy C és Goldie, a hazánkba rendszeresen visszajáró Dub Pistols és a volt faithlesses Sister Bliss. Emellett rengeteg magyar előadó is fellép majd a Parkban, köztük egy közös slam poetry esttel és élőzenei kísérettel jelentkező  Punnany Massif, Hősök, DSP és az Akkezdet Phiai, de lesz ’90-es évek parti is a Hip Hop Boyzzal, Ámokfutókkal, Happy Ganggel és az UFÓ-val, ahogy Nagy Feró és a Beatrice illetve a Bikini is fellép majd a Parkban.</p> '),
(11, 1, 4472934, 'Mozivászonra kerül a Beatles első amerikai fellépése', '', 1335361500, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/25/mozivaszonra_kerul_a_beatles_elso_amerikai_fellepese', ''),
(12, 1, 4472993, 'That''s Why God Made the Radio - Itt az új Beach Boys-kislemez', '<p style="text-align: left;"><img src="http://langologitarok.blog.hu/media/image/beach_boys_reunite_01.jpg" alt="" /><a href="http://langologitarok.blog.hu/2011/12/16/ujra_egymara_talalt_brian_wilson_es_a_beach_boys_uj_lemez_es_vilagkoruli_turne_is_lesz">Tavaly év végén már lehetett tudni</a>, hogy 2012-ben új lemezzel és turnéval rukkol elő a <strong>Beach Boys</strong>, most pedig már hivatalos a június 5-ei megjelenési dátum. Az 50. évfordulóját idén ünneplő legendás surfpop-zenekar <em>That''s Why God Made The Radio</em> című albumán az 1998-ban elhunyt Carl Wilsontól is hallhatunk majd éneksávokat. A 11 számos, Brian Wilson és Mike Love producerelte lemezről ráadásul már itt a címadó szám is, mint az első kislemez, amit a hajtás után természetesen megtalál minden Lángoló Gitárok olvasó, egy a dalhoz készült előzetessel egyetemben.</p>', 1335362700, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/25/izelito_az_uj_beach_boys_kislemezbol', '<p style="text-align: left;"><img src="http://langologitarok.blog.hu/media/image/beach_boys_reunite_01.jpg" alt="" /><a href="http://langologitarok.blog.hu/2011/12/16/ujra_egymara_talalt_brian_wilson_es_a_beach_boys_uj_lemez_es_vilagkoruli_turne_is_lesz">Tavaly év végén már lehetett tudni</a>, hogy 2012-ben új lemezzel és turnéval rukkol elő a <strong>Beach Boys</strong>, most pedig már hivatalos a június 5-ei megjelenési dátum. Az 50. évfordulóját idén ünneplő legendás surfpop-zenekar <em>That''s Why God Made The Radio</em> című albumán az 1998-ban elhunyt Carl Wilsontól is hallhatunk majd éneksávokat. A 11 számos, Brian Wilson és Mike Love producerelte lemezről ráadásul már itt a címadó szám is, mint az első kislemez, amit a hajtás után természetesen megtalál minden Lángoló Gitárok olvasó, egy a dalhoz készült előzetessel egyetemben.</p>'),
(13, 1, 4473014, 'Mégsem kell temetni a Gorillazt és a Blurt', '<p><img alt="" style="width: 550px; height: 327px;" src="http://langologitarok.blog.hu/media/image/damon_albarn_02.jpg" />Pár hete írtunk arról, hogy Damon Albarn szerint mindkét zenekarának annyi: a Blurtől és a Gorillaztól se számítsanak túl sokra a jövőben a rajongók. Előbbinek a nyári Hyde Parkbeli koncertjét jelölte meg utolsó fellépésként, utóbbival kapcsolatban pedig elmondta, hogy a projekt végét főleg a Gorillaz másik agyának számító, a vizuális megjelenésért felelős Jamie Hewlett-tel való nézeteltéréseik eredményezik. Aztán most egy újabb interjúból kiderült, hogy nem eszik azért ilyen forrón a kását: bár Albarn korábban egyszer azt nyilatkozta, tőle mindent komolyan kell venni, amit mond, most úgy tűnik ez azért távolról sincs így.</p> ', 1335364200, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/25/megsem_kell_temetni_a_gorillazt_es_a_blurt', '<p><img alt="" style="width: 550px; height: 327px;" src="http://langologitarok.blog.hu/media/image/damon_albarn_02.jpg" />Pár hete írtunk arról, hogy Damon Albarn szerint mindkét zenekarának annyi: a Blurtől és a Gorillaztól se számítsanak túl sokra a jövőben a rajongók. Előbbinek a nyári Hyde Parkbeli koncertjét jelölte meg utolsó fellépésként, utóbbival kapcsolatban pedig elmondta, hogy a projekt végét főleg a Gorillaz másik agyának számító, a vizuális megjelenésért felelős Jamie Hewlett-tel való nézeteltéréseik eredményezik. Aztán most egy újabb interjúból kiderült, hogy nem eszik azért ilyen forrón a kását: bár Albarn korábban egyszer azt nyilatkozta, tőle mindent komolyan kell venni, amit mond, most úgy tűnik ez azért távolról sincs így.</p> '),
(14, 1, 4473138, 'Színház és sport is lesz az EFOTT-on', '<p><img src="http://langologitarok.blog.hu/media/image/efott_sajttaj.jpg" alt="" width="550" />Csík-minikoncerttel egybekötött sajtótájékoztatót tartott az EFOTT a BME Szkéné színháztermében. A helyszínválasztás apropója a Szkéné EFOTT-ra költözése: minden este előadásokkal várják a koncert helyett színházba vágyó fesztiválozókat. Ezen kívül még a sport is kiemelt téma lesz július 3-8. között, Velencén.</p> ', 1335366300, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/25/szinhaz_es_sport_is_lesz_az_efott_on', '<p><img src="http://langologitarok.blog.hu/media/image/efott_sajttaj.jpg" alt="" width="550" />Csík-minikoncerttel egybekötött sajtótájékoztatót tartott az EFOTT a BME Szkéné színháztermében. A helyszínválasztás apropója a Szkéné EFOTT-ra költözése: minden este előadásokkal várják a koncert helyett színházba vágyó fesztiválozókat. Ezen kívül még a sport is kiemelt téma lesz július 3-8. között, Velencén.</p> '),
(15, 1, 4473319, 'Sötét maszatolás – Paradise Lost-lemezkritika', '<h3><img alt="" style="width: 200px; height: 197px;" src="http://langologitarok.blog.hu/media/image/paradise_lost_tragic_idol_cover_01.jpg" />Paradise Lost – Tragic Idol<br /> (Century Media)</h3> <p>A Paradise Lost visszafelé halad az időben. Már az előző lemezükre is ráfogtuk, hogy "vissza a gyökerekhez"-célkitűzéssel készült, de a Tragic Idolnál "gyökerebb" lemezt valószínűleg nem tudnak gyártani. Ez konkrétan visszatér az Icon-korszakhoz, amikor Nick Holmes szomorúan kiabálva énekelt három hangos dallamokat. Szóval érezhető, látható a cél, és ennek meg is felel a Tragic Idol, viszont nem jó lemez. Néhány szám üti meg csak a mércét, a többi maszatolás csupán, amiben van hangulat, de a zene öregesen és enerváltan szól. Persze, a metálszakma egyik legjobb dobosa (Adrian Erlandsson) zenél most velük, de a dinamika csak ott jelenik meg, a dallamok, a riffek, az ötletek viszont nem frissek. Az üveghangú gitártorzítás, a keserű szólók, a zúgó, búgó riffek ugyanolyanok, mint a Depeche Mode-időszakon kívül bármikor. Nick Holmes sem lett vidámabb, és kevésbé ironikus, de nem csak nem hihető, amit összehoztak, hanem sajnos nem is dicsérhető.</p>', 1335371400, 'Dankó János', 'http://langologitarok.blog.hu/2012/04/25/sotet_maszatolas_paradise_lost_lemezkritika', '<h3><img alt="" style="width: 200px; height: 197px;" src="http://langologitarok.blog.hu/media/image/paradise_lost_tragic_idol_cover_01.jpg" />Paradise Lost – Tragic Idol<br /> (Century Media)</h3> <p>A Paradise Lost visszafelé halad az időben. Már az előző lemezükre is ráfogtuk, hogy "vissza a gyökerekhez"-célkitűzéssel készült, de a Tragic Idolnál "gyökerebb" lemezt valószínűleg nem tudnak gyártani. Ez konkrétan visszatér az Icon-korszakhoz, amikor Nick Holmes szomorúan kiabálva énekelt három hangos dallamokat. Szóval érezhető, látható a cél, és ennek meg is felel a Tragic Idol, viszont nem jó lemez. Néhány szám üti meg csak a mércét, a többi maszatolás csupán, amiben van hangulat, de a zene öregesen és enerváltan szól. Persze, a metálszakma egyik legjobb dobosa (Adrian Erlandsson) zenél most velük, de a dinamika csak ott jelenik meg, a dallamok, a riffek, az ötletek viszont nem frissek. Az üveghangú gitártorzítás, a keserű szólók, a zúgó, búgó riffek ugyanolyanok, mint a Depeche Mode-időszakon kívül bármikor. Nick Holmes sem lett vidámabb, és kevésbé ironikus, de nem csak nem hihető, amit összehoztak, hanem sajnos nem is dicsérhető.</p>'),
(16, 1, 4474116, 'Björk újabb koncertet mondott le', '', 1335420000, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/26/bjork_ujabb_koncertet_mondott_le', ''),
(17, 1, 4474265, 'You''re So Vain – Marilyn Manson és Johnny Depp közös dala', '<p><img src="http://langologitarok.blog.hu/media/image/marilyn_manson_johnny_depp_02.jpg" style="width: 550px; height: 413px;" alt="" />Napokon belül, egész pontosan április 30-án megjelenik Marilyn Manson legújabb, Born Villain című nagylemeze, amin, mint ahogy azt <a href="http://langologitarok.blog.hu/2012/03/21/kozos_dalt_keszitett_marilyn_manson_es_johnny_depp" target="_blank">már kábé egy hónapja megírtuk</a>, bónuszdalként szerepelni fog egy 1972-es Carly Simon-dal, a You''re So Vain feldolgozása is, amit Manson egy régi cimborája, a sztárszínész Johnny Depp közreműködésével vett fel. Manson egy korábbi nyilatkozata szerint Deep a dobokon és szólógitáron játszik a számban, míg ő maga természetesen énekel és gitározik benne. Sőt, ha minden igaz, Johnny Depp kilenc éves kisfia, Jack is szerepel a felvételen, legalábbis korábban ezt ígérték, bár az nem derült ki, hogy rá milyen hangszert bíztak és ennek megfejtéséhez a lapozás után hallható dal sem visz sokkal közelebb.</p> ', 1335423600, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/26/you_re_so_vain_marilyn_manson_es_johnny_depp_kozos_dala', '<p><img src="http://langologitarok.blog.hu/media/image/marilyn_manson_johnny_depp_02.jpg" style="width: 550px; height: 413px;" alt="" />Napokon belül, egész pontosan április 30-án megjelenik Marilyn Manson legújabb, Born Villain című nagylemeze, amin, mint ahogy azt <a href="http://langologitarok.blog.hu/2012/03/21/kozos_dalt_keszitett_marilyn_manson_es_johnny_depp" target="_blank">már kábé egy hónapja megírtuk</a>, bónuszdalként szerepelni fog egy 1972-es Carly Simon-dal, a You''re So Vain feldolgozása is, amit Manson egy régi cimborája, a sztárszínész Johnny Depp közreműködésével vett fel. Manson egy korábbi nyilatkozata szerint Deep a dobokon és szólógitáron játszik a számban, míg ő maga természetesen énekel és gitározik benne. Sőt, ha minden igaz, Johnny Depp kilenc éves kisfia, Jack is szerepel a felvételen, legalábbis korábban ezt ígérték, bár az nem derült ki, hogy rá milyen hangszert bíztak és ennek megfejtéséhez a lapozás után hallható dal sem visz sokkal közelebb.</p> '),
(18, 1, 4474541, 'Breath Of Life – Itt egy vadiúj Florence + the Machine-dal', '<p><img src="http://langologitarok.blog.hu/media/image/florence_and_the_machine_breath_of_life_cover_01.jpg" alt="" />Május legvégén mutatják be a Hófehér és a vadász című filmet Kristen Stewart és Charlize Theron főszereplésével, aminek egyik betétdala egy Florence + the Machine-dal, a Breath Of Life lesz, aminek egyik különlegessége, hogy egy teljesen új felvétel, nem a tavaly ősszel kiadott Ceremonials albumról másolták ki. A fantasy zenéje nagyjából a bemutatásával egyszerre jelenik meg, Florence Welch vadonatúj száma viszont itt a lapozás után máris meghallgatható.</p>', 1335431700, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/26/breath_of_life_itt_egy_vadiuj_florence_the_machine_dal', '<p><img src="http://langologitarok.blog.hu/media/image/florence_and_the_machine_breath_of_life_cover_01.jpg" alt="" />Május legvégén mutatják be a Hófehér és a vadász című filmet Kristen Stewart és Charlize Theron főszereplésével, aminek egyik betétdala egy Florence + the Machine-dal, a Breath Of Life lesz, aminek egyik különlegessége, hogy egy teljesen új felvétel, nem a tavaly ősszel kiadott Ceremonials albumról másolták ki. A fantasy zenéje nagyjából a bemutatásával egyszerre jelenik meg, Florence Welch vadonatúj száma viszont itt a lapozás után máris meghallgatható.</p>'),
(19, 1, 4474597, 'A Matter of Birth or Death - Itt a Seen első nagylemeze', '<p><img src="http://langologitarok.blog.hu/media/image/seen_bed1006_part01.jpg" alt="" />Hosszú évek kalandos működése után jelentkezik bemutatkozó nagylemezével a <strong>Seen</strong>. A kompromisszummentesen sodró és dalok helyett inkább energiákban gondolkodó formáció <em>A Matter of Birth and Death</em> című keresztelt debütálása egyben konceptlemez is lett. Az albumot Lángoló Gitárok olvasói hallhatják először.</p>', 1335437640, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/26/a_matter_of_birth_or_death_itt_a_seen_elso_nagylemeze', '<p><img src="http://langologitarok.blog.hu/media/image/seen_bed1006_part01.jpg" alt="" />Hosszú évek kalandos működése után jelentkezik bemutatkozó nagylemezével a <strong>Seen</strong>. A kompromisszummentesen sodró és dalok helyett inkább energiákban gondolkodó formáció <em>A Matter of Birth and Death</em> című keresztelt debütálása egyben konceptlemez is lett. Az albumot Lángoló Gitárok olvasói hallhatják először.</p>'),
(20, 1, 4474613, 'Princess Of China – A Coldplay különleges videója Rihannával', '<p><img src="http://langologitarok.blog.hu/media/image/rihanna_coldplay_princess_of_china_special_concert_video_01.jpg" alt="" />Egy speciális videó segítségével adja elő aktuális világkörüli turnéján a Rihanna közreműködésével készült Princess Of China című számát a Coldplay, ami a tervek szerint az együttes következő kislemezes dala lesz a tavaly megjelent albumról. Mivel énekesnő természetesen nem lesz jelen személyesen a koncertkörút minden állomásán, így a dalhoz egy speciális videó készült, amiben Rihanna egy animált háttér előtt látható keleties öltözetben és sminkben, amint hatalmas karmokkal felszerelkezve mutat be ázsiai táncokra emlékeztető kézmozdulatokat, közben pedig persze énekel. A hatást a háttér folyamatos változása és némi vizuális trükk segíti, miközben a Coldplay élőben zenél majd a színpadon. Rihanna egyébként önmagát ebben a szerelésben egyszerűen csak "gansta goth geisha"-ként jellemezte, pár képet feltett kedvenc játszóterére, a Twitterre is a forgatásról, ezek bemutatásától viszont most nagyvonalúan eltekintünk. A videó a hajtás után megnézhető, bár, meg kell mondjuk, rettentő ósdi megoldás ez <a target="_blank" href="http://langologitarok.blog.hu/tags/hologram">a hologramkorszakban</a>.</p>', 1335439800, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/04/26/princess_of_china_a_coldplay_kulonleges_videoja_rihannaval', '<p><img src="http://langologitarok.blog.hu/media/image/rihanna_coldplay_princess_of_china_special_concert_video_01.jpg" alt="" />Egy speciális videó segítségével adja elő aktuális világkörüli turnéján a Rihanna közreműködésével készült Princess Of China című számát a Coldplay, ami a tervek szerint az együttes következő kislemezes dala lesz a tavaly megjelent albumról. Mivel énekesnő természetesen nem lesz jelen személyesen a koncertkörút minden állomásán, így a dalhoz egy speciális videó készült, amiben Rihanna egy animált háttér előtt látható keleties öltözetben és sminkben, amint hatalmas karmokkal felszerelkezve mutat be ázsiai táncokra emlékeztető kézmozdulatokat, közben pedig persze énekel. A hatást a háttér folyamatos változása és némi vizuális trükk segíti, miközben a Coldplay élőben zenél majd a színpadon. Rihanna egyébként önmagát ebben a szerelésben egyszerűen csak "gansta goth geisha"-ként jellemezte, pár képet feltett kedvenc játszóterére, a Twitterre is a forgatásról, ezek bemutatásától viszont most nagyvonalúan eltekintünk. A videó a hajtás után megnézhető, bár, meg kell mondjuk, rettentő ósdi megoldás ez <a target="_blank" href="http://langologitarok.blog.hu/tags/hologram">a hologramkorszakban</a>.</p>'),
(21, 1, 4571444, 'Megtalálták Axl Rose ellopott ékszereit', '', 1339075140, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/06/07/megtalaltak_axl_rose_ellopott_ekszereit', ''),
(22, 1, 4571938, 'London lesz a legjobb hely a világon - Duran Duran-interjú John Taylorral', '', 1339079220, 'Juhász Edina', 'http://langologitarok.blog.hu/2012/06/07/london_lesz_a_legjobb_hely_a_vilagon_duran_duran-interju', ''),
(23, 1, 4571982, 'Barátokkal a stúdióban - Óriás-stúdiósession (exkluzív premier)', '', 1339082400, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/06/07/baratokkal_a_studioban_orias-studiosession_exkluziv_premier', ''),
(24, 1, 4571565, 'Dave Mustaine-t kövekkel dobálták meg Horvátországban', '', 1339135200, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/06/08/kovekkel_dobaltak_dave_mustaine-t_horvatorszagban', ''),
(25, 1, 4573080, 'Lulu in Black – Burzum-lemezkritika', '', 1339139160, 'Gnosis', 'http://langologitarok.blog.hu/2012/06/08/lulu_in_black_burzum-lemezkritika', ''),
(26, 1, 4573235, 'Elvonóra megy Matt Pike, a High On Fire főnöke', '', 1339145520, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/06/08/rehabra_kerult_matt_pike', ''),
(27, 1, 4573326, 'Szabad Európa Rádió – A The [hated] Tomorrow új lemeze', '', 1339148520, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/06/08/szabad_europa_radio_a_the_hated_tomorrow_uj_lemeze', ''),
(28, 1, 4573252, 'Öngyilkos lett a Fleetwood Mac volt gitárosa', '', 1339153860, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/06/08/ongyilkos_lett_a_fleetwood_mac_volt_gitarosa', ''),
(29, 1, 4573420, '„Csókold meg a csillogó seggem!” – Erykah Badu kiakadt a Flaming Lips frontemberére (18+)', '', 1339156560, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/06/08/_csokold_meg_a_csillogo_seggem_erykah_badu_kiakadt_a_flaming_lips_frontemberere', ''),
(30, 1, 4573757, '12 év egy új könyvben – Nyerd meg az új Tankcsapda-könyvet!', '', 1339161180, '[X]', 'http://langologitarok.blog.hu/2012/06/08/12_ev_egy_uj_konyvben_nyerd_meg_az_uj_tankcsapda-konyvet', ''),
(31, 1, 4573711, 'Lemondta szigetes fellépését a Modeselektor', '', 1339162740, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/06/08/nem_jon_modeselektor_a_szigetre', ''),
(32, 1, 4573913, 'Százötvennél már nem fog a fék – Sexepil-koncertbeszámoló', '', 1339165080, 'Németh Marcell', 'http://langologitarok.blog.hu/2012/06/08/szazotvennel_mar_nem_fog_a_fek_sexepil-koncertbeszamolo', ''),
(33, 1, 4574001, 'Axl Rose 16 ellensége', '', 1339234320, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/06/09/axl_rose_16_ellensege', ''),
(34, 1, 4574998, 'Így lopnak a sztárzenekarok', '', 1339246980, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/06/09/igy_lopnak_a_sztarzenekarok', ''),
(35, 1, 4576106, 'Thank you very much, good night, fuck you Hungary! - klipmegosztás', '', 1339318380, 'Sajó Dávid', 'http://langologitarok.blog.hu/2012/06/10/fuck_you_hungary_klipmegosztas', ''),
(36, 1, 4578597, 'Lady Gagát fejbe vágta egy pózna', '', 1339398360, '[MTI]', 'http://langologitarok.blog.hu/2012/06/11/cim-nelkul_2833', ''),
(37, 1, 4578494, '383 & 484 - Új Eejit Midget-kislemez', '', 1339404780, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/06/11/383_484_uj_eejit_midget-kislemez', ''),
(38, 1, 4578491, 'Full Stop - Ismét új dalt játszott a Radiohead élőben', '', 1339409280, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/06/11/uj_dal_a_radioheadtol', ''),
(39, 1, 4494423, 'Elegem van a siránkozókból – Saint Vitus-interjú Dave Chandlerrel', '<p><img src="http://langologitarok.blog.hu/media/image/saintvitus0141photobyAudreyJarrett.jpg" alt="" />A <strong>Saint Vitus</strong> tipikusan az a zenekar, ami soha nem lett igazán híres, de lépten-nyomon hivatkoznak rá a fiatalabb, sikeresebb csapatok. Az 1978-ban alakult doom metál együttes több mint egy évtizedes, szinte teljes szünet után alakult újra 2008-ban, méghozzá az önállóan is legendának számító Scott "Wino" Weinrich énekessel az élen. Jelenlegi aktualitásukat az adja, hogy 17 év várakozás után április végén jelent meg a <em>Lillie: F-65 </em>című új lemezük, amit ráadásul július 4-én az A38 Hajón is bemutatnak a magyar rajongóknak. Dave Chandler alapító-gitárossal beszélgettünk.</p>\r\n', 1339412760, 'Juhász Edina', 'http://langologitarok.blog.hu/2012/06/11/elegem_van_a_sirankozokbol_saint_vitus_interju_dave_chandlerrel', '<p><img src="http://langologitarok.blog.hu/media/image/saintvitus0141photobyAudreyJarrett.jpg" alt="" />A <strong>Saint Vitus</strong> tipikusan az a zenekar, ami soha nem lett igazán híres, de lépten-nyomon hivatkoznak rá a fiatalabb, sikeresebb csapatok. Az 1978-ban alakult doom metál együttes több mint egy évtizedes, szinte teljes szünet után alakult újra 2008-ban, méghozzá az önállóan is legendának számító Scott "Wino" Weinrich énekessel az élen. Jelenlegi aktualitásukat az adja, hogy 17 év várakozás után április végén jelent meg a <em>Lillie: F-65 </em>című új lemezük, amit ráadásul július 4-én az A38 Hajón is bemutatnak a magyar rajongóknak. Dave Chandler alapító-gitárossal beszélgettünk.</p>\r\n'),
(40, 1, 4580147, 'Magyarok lopták el a Limp Bizkit gitárját a Nova Rockon', '', 1339414860, '[MTI] Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/06/11/magyarok_loptak_el_a_limp_bizkit_gitarjat_a_nova_rockon', ''),
(41, 1, 4661247, 'Turn Up The Radio - Új Madonna-videó', '', 1342508640, 'sajó d.', 'http://langologitarok.blog.hu/2012/07/17/turn_up_the_radio_uj_madonna-video', ''),
(42, 1, 4661308, 'Angels - Új The xx-kislemez', '', 1342512600, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/17/angels_uj_the_xx-kislemez', ''),
(43, 1, 4661580, 'White Light - Új George Michael-videó', '', 1342515600, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/17/uj_george_michael-video', ''),
(44, 1, 4661883, 'S mint súlytalan - Serj Tankian-lemezkritika', '', 1342519800, 'SCs', 'http://langologitarok.blog.hu/2012/07/17/s_mint_sulytalan_serj_tankian-lemezkritika', ''),
(45, 1, 4662017, 'Nem félek semmi újtól - Serj Tankian-interjú', '', 1342523100, 'Juhász Edina', 'http://langologitarok.blog.hu/2012/07/17/nem_felek_semmi_ujtol_serj_tankian-interju', ''),
(46, 1, 4661295, 'Ég anda - Új Sigur Rós-videó', '', 1342527600, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/17/eg_anda_uj_sigur_ros-video', ''),
(47, 1, 4662139, 'Settle Down - Új No Doubt-videó', '', 1342531440, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/17/settle_down_uj_no_doubt-video', ''),
(48, 1, 4663466, 'Don''t Use - ZeropozitivE-klippremier', '', 1342598460, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/18/don_t_use_zeropozitive-klippremier', ''),
(49, 1, 4664195, 'Decemberben hajóra száll a Coachella fesztivál', '', 1342610280, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/18/decemberben_hajora_szall_a_coachella_fesztival', ''),
(50, 1, 4664770, 'Mindenkinek jobb lesz így – Rendhagyó Konyha-klippremier', '', 1342627200, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/18/mindenkinek_jobb_lesz_igy_rendhagyo_konyha-klippremier', ''),
(51, 1, 4665737, 'Szólólemezt ad ki az Iron Maiden főnöke', '', 1342685160, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/19/szololemezt_ad_ki_az_iron_maiden_fonoke', ''),
(52, 1, 4666063, 'Jimmy Fallonnál járt a Refused', '', 1342693680, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/19/jimmy_fallonnal_jart_a_refused', ''),
(53, 1, 4666218, 'Corgan nyávog és zseni – The Smashing Pumpkins-lemezkritika', '', 1342700040, 'Dankó János', 'http://langologitarok.blog.hu/2012/07/19/corgan_nyavog_de_zseni_the_smashing_pumpkins-lemezkritika', ''),
(54, 1, 4666276, 'Száztagú debreceni rockzenekar', '', 1342701480, 'sixx', 'http://langologitarok.blog.hu/2012/07/19/szaztagu_debreceni_rockzenekar', ''),
(55, 1, 4667622, 'Leave It Up To Me - A Compact Disco és Columbo közös klipje', '', 1342772640, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/20/leave_it_up_to_me_a_compact_disco_es_colombo_kozos_klipje', ''),
(56, 1, 4667680, 'Akár ingyen is letölthető Flea szólólemeze', '', 1342775700, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/20/akar_ingyen_is_letoltheto_flea_szololemeze', ''),
(57, 1, 4667849, 'Pete Dohertyt eltanácsolták egy thaiföldi drogelvonóról', '', 1342778760, '[MTI] Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/20/pete_dohertyt_eltanacsoltak_egy_thaifoldi_drogelvonorol', ''),
(58, 1, 4668182, 'A Spice Girls újjáalakul az olimpiára', '', 1342791360, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/20/a_spice_girls_ujjaalakul_az_olimpiara', ''),
(59, 1, 4668301, 'Az utóbbi idôk Szigete - Programfüzetek 2008-2011', '', 1342793520, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/20/_programfuzetek_2008-2011', ''),
(60, 1, 4668636, 'Fiam, ez mind bolond! A világnak el kell pusztulnia! - Nosztalgiázás a 20. Sziget alkalmából (+ játék)', '', 1342803600, 'Lángoló Gitárok', 'http://langologitarok.blog.hu/2012/07/20/-_nosztalgiazas_a_20_sziget_alkalmabol_jatek', '');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_pages`
--

CREATE TABLE IF NOT EXISTS `gearoscope_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `namespace` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `date_created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_styles`
--

CREATE TABLE IF NOT EXISTS `gearoscope_styles` (
  `style_id` int(11) NOT NULL AUTO_INCREMENT,
  `style` varchar(255) NOT NULL,
  PRIMARY KEY (`style_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- A tábla adatainak kiíratása `gearoscope_styles`
--

INSERT INTO `gearoscope_styles` (`style_id`, `style`) VALUES
(1, 'metal'),
(2, 'klasszikus heavy metal'),
(3, 'death and roll'),
(4, 'disznó metal');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `gearoscope_users`
--

CREATE TABLE IF NOT EXISTS `gearoscope_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_active` int(1) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_username` varchar(255) NOT NULL,
  `user_password` varchar(250) DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_photo` varchar(255) NOT NULL,
  `user_bio` text,
  `user_role` varchar(25) DEFAULT NULL,
  `user_register_date` int(11) NOT NULL,
  `user_login_date` int(11) NOT NULL,
  `user_oauth_provider` enum('facebook') NOT NULL,
  `user_oauth_id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- A tábla adatainak kiíratása `gearoscope_users`
--

INSERT INTO `gearoscope_users` (`user_id`, `user_active`, `user_name`, `user_username`, `user_password`, `user_email`, `user_photo`, `user_bio`, `user_role`, `user_register_date`, `user_login_date`, `user_oauth_provider`, `user_oauth_id`, `code`) VALUES
(1, 1, '', 'Gearoscope', '21232f297a57a5a743894a0e4a801fc3', '', 'user.jpg', NULL, 'Administrator', 0, 1344862970, 'facebook', 0, ''),
(4, 1, 'Erdei Attila', 'Kisatti00', '0730c6c19e8b4b77bd061e3cbec48f6a', 'attila.erdei87@gmail.hu', 'user.jpg', NULL, 'user', 0, 0, 'facebook', 0, ''),
(5, 1, 'Erdei Attila', 'Attilae', '0730c6c19e8b4b77bd061e3cbec48f6a', 'skinnerscag@gmail.com', 'user.jpg', NULL, 'user', 0, 1344553468, 'facebook', 0, ''),
(16, 1, 'LFCaptain', 'LFCaptain', '0730c6c19e8b4b77bd061e3cbec48f6a', 'attila.erdei87@gmail', 'user.jpg', NULL, 'user', 0, 1339361721, 'facebook', 0, ''),
(21, 1, 'Johnny Gold', 'Johnny Gold', '0730c6c19e8b4b77bd061e3cbec48f6a', 'atti00@freemail.hu', 'user.jpg', NULL, 'user', 0, 0, 'facebook', 0, 'dcd4e92266f7e5bd426354e16cb1cba8'),
(22, 1, 'Richard GoldMájer', 'Richard GoldMájer', '0730c6c19e8b4b77bd061e3cbec48f6a', 'erdei.attila@kreati.hu', 'user.jpg', NULL, 'user', 0, 0, 'facebook', 0, '85b885848ea43b0e053637d76b29a208'),
(23, 1, 'DevDrummer', 'DevDrummer', 'cb542fe52780420a170498b02153abf6', 'attila.erdei87@gmail.com', '1345036591.1873.jpg', 'Még a videójátékoknál is gyorsabban nő a népszerűségük, egyre kevesebb az mp3-lejátszó.', 'user', 1344520274, 1345035127, 'facebook', 0, 'ed00fa74b2f8a39d5d2ac6e2c748f75b');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
