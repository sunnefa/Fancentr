-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 26, 2012 at 10:36 AM
-- Server version: 5.1.44
-- PHP Version: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fans`
--

-- --------------------------------------------------------

--
-- Table structure for table `fancentr__ads`
--

CREATE TABLE `fancentr__ads` (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_title` varchar(255) NOT NULL,
  `adcode` text NOT NULL,
  PRIMARY KEY (`ad_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='The advertisements displayed in some pages' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `fancentr__ads`
--

INSERT INTO `fancentr__ads` VALUES(2, 'Google ad 2', '<script type="text/javascript"><!--\r\ngoogle_ad_client = "ca-pub-3029797927391727";\r\n/* fancentr small square */\r\ngoogle_ad_slot = "7676116416";\r\ngoogle_ad_width = 200;\r\ngoogle_ad_height = 200;\r\n//-->\r\n</script>\r\n<script type="text/javascript"\r\nsrc="http://pagead2.googlesyndication.com/pagead/show_ads.js">\r\n</script>');

-- --------------------------------------------------------

--
-- Table structure for table `fancentr__feeds`
--

CREATE TABLE `fancentr__feeds` (
  `feed_id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `last_updated` datetime NOT NULL,
  PRIMARY KEY (`feed_id`),
  KEY `user_id_feed` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='The information about different RSS feeds users create' AUTO_INCREMENT=13 ;

--
-- Dumping data for table `fancentr__feeds`
--

INSERT INTO `fancentr__feeds` VALUES(1, 'Twilight', 6, '2012-10-05 14:29:42');
INSERT INTO `fancentr__feeds` VALUES(9, 'Actresses', 6, '2012-10-05 14:52:12');
INSERT INTO `fancentr__feeds` VALUES(11, 'Somethings', 6, '2012-10-05 16:13:02');
INSERT INTO `fancentr__feeds` VALUES(12, 'Actresses', 1, '2012-10-22 14:15:36');

-- --------------------------------------------------------

--
-- Table structure for table `fancentr__feeds_sites`
--

CREATE TABLE `fancentr__feeds_sites` (
  `feed_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  KEY `feed_id_jn` (`feed_id`),
  KEY `site_id_jn` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='The connection between RSS feeds and sites';

--
-- Dumping data for table `fancentr__feeds_sites`
--

INSERT INTO `fancentr__feeds_sites` VALUES(1, 25);
INSERT INTO `fancentr__feeds_sites` VALUES(1, 23);
INSERT INTO `fancentr__feeds_sites` VALUES(9, 28);
INSERT INTO `fancentr__feeds_sites` VALUES(9, 3);
INSERT INTO `fancentr__feeds_sites` VALUES(9, 26);
INSERT INTO `fancentr__feeds_sites` VALUES(9, 4);
INSERT INTO `fancentr__feeds_sites` VALUES(9, 1);
INSERT INTO `fancentr__feeds_sites` VALUES(9, 34);
INSERT INTO `fancentr__feeds_sites` VALUES(11, 28);
INSERT INTO `fancentr__feeds_sites` VALUES(11, 16);
INSERT INTO `fancentr__feeds_sites` VALUES(12, 28);
INSERT INTO `fancentr__feeds_sites` VALUES(12, 3);

-- --------------------------------------------------------

--
-- Table structure for table `fancentr__modules`
--

CREATE TABLE `fancentr__modules` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(45) NOT NULL,
  `module_path` varchar(45) NOT NULL,
  `module_is_active` int(11) DEFAULT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='The different page modules possible such as stats, ads etc.' AUTO_INCREMENT=13 ;

--
-- Dumping data for table `fancentr__modules`
--

INSERT INTO `fancentr__modules` VALUES(1, 'header', 'modules/core/header.php', 1);
INSERT INTO `fancentr__modules` VALUES(2, 'footer', 'modules/core/footer.php', 1);
INSERT INTO `fancentr__modules` VALUES(3, 'navigation', 'modules/core/navigation.php', 1);
INSERT INTO `fancentr__modules` VALUES(4, 'ads', 'modules/ads/ads.php', 1);
INSERT INTO `fancentr__modules` VALUES(5, 'sites', 'modules/sites/sites.php', 1);
INSERT INTO `fancentr__modules` VALUES(6, 'text', 'modules/text/text.php', 1);
INSERT INTO `fancentr__modules` VALUES(7, 'users', 'modules/users/users.php', 1);
INSERT INTO `fancentr__modules` VALUES(8, 'welcome', 'modules/core/welcome.php', 1);
INSERT INTO `fancentr__modules` VALUES(9, 'rss', 'modules/rss/rss.php', 1);
INSERT INTO `fancentr__modules` VALUES(10, 'statistics', 'modules/core/statistics.php', 1);
INSERT INTO `fancentr__modules` VALUES(11, 'contact', 'modules/core/contact.php', 1);
INSERT INTO `fancentr__modules` VALUES(12, 'messages', 'modules/messages/messages.php', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fancentr__modules_pages`
--

CREATE TABLE `fancentr__modules_pages` (
  `module_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `display_order` int(11) DEFAULT NULL,
  KEY `module_id_jn` (`module_id`),
  KEY `page_id_jn` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='The relationship between pages and modules';

--
-- Dumping data for table `fancentr__modules_pages`
--

INSERT INTO `fancentr__modules_pages` VALUES(1, 1, 1);
INSERT INTO `fancentr__modules_pages` VALUES(8, 1, 2);
INSERT INTO `fancentr__modules_pages` VALUES(4, 1, 3);
INSERT INTO `fancentr__modules_pages` VALUES(5, 1, 4);
INSERT INTO `fancentr__modules_pages` VALUES(3, 1, 5);
INSERT INTO `fancentr__modules_pages` VALUES(10, 1, 6);
INSERT INTO `fancentr__modules_pages` VALUES(2, 1, 7);
INSERT INTO `fancentr__modules_pages` VALUES(1, 2, 1);
INSERT INTO `fancentr__modules_pages` VALUES(8, 2, 2);
INSERT INTO `fancentr__modules_pages` VALUES(4, 2, 3);
INSERT INTO `fancentr__modules_pages` VALUES(5, 2, 4);
INSERT INTO `fancentr__modules_pages` VALUES(3, 2, 5);
INSERT INTO `fancentr__modules_pages` VALUES(10, 2, 6);
INSERT INTO `fancentr__modules_pages` VALUES(2, 2, 7);
INSERT INTO `fancentr__modules_pages` VALUES(1, 3, 1);
INSERT INTO `fancentr__modules_pages` VALUES(8, 3, 2);
INSERT INTO `fancentr__modules_pages` VALUES(4, 3, 3);
INSERT INTO `fancentr__modules_pages` VALUES(7, 3, 4);
INSERT INTO `fancentr__modules_pages` VALUES(3, 3, 5);
INSERT INTO `fancentr__modules_pages` VALUES(10, 3, 6);
INSERT INTO `fancentr__modules_pages` VALUES(2, 3, 7);
INSERT INTO `fancentr__modules_pages` VALUES(1, 4, 1);
INSERT INTO `fancentr__modules_pages` VALUES(8, 4, 2);
INSERT INTO `fancentr__modules_pages` VALUES(4, 4, 3);
INSERT INTO `fancentr__modules_pages` VALUES(6, 4, 4);
INSERT INTO `fancentr__modules_pages` VALUES(3, 4, 5);
INSERT INTO `fancentr__modules_pages` VALUES(10, 4, 6);
INSERT INTO `fancentr__modules_pages` VALUES(2, 4, 7);
INSERT INTO `fancentr__modules_pages` VALUES(1, 5, 1);
INSERT INTO `fancentr__modules_pages` VALUES(8, 5, 2);
INSERT INTO `fancentr__modules_pages` VALUES(4, 5, 3);
INSERT INTO `fancentr__modules_pages` VALUES(6, 5, 4);
INSERT INTO `fancentr__modules_pages` VALUES(3, 5, 5);
INSERT INTO `fancentr__modules_pages` VALUES(10, 5, 6);
INSERT INTO `fancentr__modules_pages` VALUES(2, 5, 7);
INSERT INTO `fancentr__modules_pages` VALUES(1, 6, 1);
INSERT INTO `fancentr__modules_pages` VALUES(8, 6, 2);
INSERT INTO `fancentr__modules_pages` VALUES(4, 6, 3);
INSERT INTO `fancentr__modules_pages` VALUES(6, 6, 4);
INSERT INTO `fancentr__modules_pages` VALUES(3, 6, 5);
INSERT INTO `fancentr__modules_pages` VALUES(10, 6, 6);
INSERT INTO `fancentr__modules_pages` VALUES(2, 6, 7);
INSERT INTO `fancentr__modules_pages` VALUES(1, 7, 1);
INSERT INTO `fancentr__modules_pages` VALUES(8, 7, 2);
INSERT INTO `fancentr__modules_pages` VALUES(4, 7, 3);
INSERT INTO `fancentr__modules_pages` VALUES(6, 7, 4);
INSERT INTO `fancentr__modules_pages` VALUES(3, 7, 5);
INSERT INTO `fancentr__modules_pages` VALUES(10, 7, 6);
INSERT INTO `fancentr__modules_pages` VALUES(2, 7, 7);
INSERT INTO `fancentr__modules_pages` VALUES(1, 8, 1);
INSERT INTO `fancentr__modules_pages` VALUES(8, 8, 2);
INSERT INTO `fancentr__modules_pages` VALUES(4, 8, 3);
INSERT INTO `fancentr__modules_pages` VALUES(11, 8, 4);
INSERT INTO `fancentr__modules_pages` VALUES(3, 8, 5);
INSERT INTO `fancentr__modules_pages` VALUES(10, 8, 6);
INSERT INTO `fancentr__modules_pages` VALUES(2, 8, 7);
INSERT INTO `fancentr__modules_pages` VALUES(1, 9, 1);
INSERT INTO `fancentr__modules_pages` VALUES(8, 9, 2);
INSERT INTO `fancentr__modules_pages` VALUES(4, 9, 3);
INSERT INTO `fancentr__modules_pages` VALUES(6, 9, 4);
INSERT INTO `fancentr__modules_pages` VALUES(3, 9, 5);
INSERT INTO `fancentr__modules_pages` VALUES(10, 9, 6);
INSERT INTO `fancentr__modules_pages` VALUES(2, 9, 7);
INSERT INTO `fancentr__modules_pages` VALUES(1, 10, 1);
INSERT INTO `fancentr__modules_pages` VALUES(8, 10, 2);
INSERT INTO `fancentr__modules_pages` VALUES(4, 10, 3);
INSERT INTO `fancentr__modules_pages` VALUES(9, 10, 4);
INSERT INTO `fancentr__modules_pages` VALUES(3, 10, 5);
INSERT INTO `fancentr__modules_pages` VALUES(10, 10, 6);
INSERT INTO `fancentr__modules_pages` VALUES(2, 10, 7);
INSERT INTO `fancentr__modules_pages` VALUES(1, 11, 1);
INSERT INTO `fancentr__modules_pages` VALUES(8, 11, 2);
INSERT INTO `fancentr__modules_pages` VALUES(4, 11, 3);
INSERT INTO `fancentr__modules_pages` VALUES(12, 11, 4);
INSERT INTO `fancentr__modules_pages` VALUES(3, 11, 5);
INSERT INTO `fancentr__modules_pages` VALUES(10, 11, 6);
INSERT INTO `fancentr__modules_pages` VALUES(2, 11, 7);

-- --------------------------------------------------------

--
-- Table structure for table `fancentr__pages`
--

CREATE TABLE `fancentr__pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(55) NOT NULL,
  `page_name` varchar(55) DEFAULT NULL,
  `page_url` varchar(55) DEFAULT NULL,
  `page_meta_description` varchar(255) NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='The different pages, such as profile page, site page etc.' AUTO_INCREMENT=12 ;

--
-- Dumping data for table `fancentr__pages`
--

INSERT INTO `fancentr__pages` VALUES(1, 'A fansite directory social network', 'home', 'home/', 'Fancentr.org is a fansite directory which is also a social network.');
INSERT INTO `fancentr__pages` VALUES(2, 'Sites', 'sites', 'sites/', 'The sites in our database');
INSERT INTO `fancentr__pages` VALUES(3, 'Users', 'users', 'users/', 'Everything to do with the users');
INSERT INTO `fancentr__pages` VALUES(4, 'Privacy Policy', 'privacy', 'privacy/', 'Our privacy policy');
INSERT INTO `fancentr__pages` VALUES(5, 'Terms of use', 'tos', 'tos/', 'Our terms of use');
INSERT INTO `fancentr__pages` VALUES(6, 'DMCA', 'dmca', 'dmca/', 'Our DMCA notification info');
INSERT INTO `fancentr__pages` VALUES(7, 'Crawler', 'crawler', 'crawler/', 'Information about our crawler');
INSERT INTO `fancentr__pages` VALUES(8, 'Contact us', 'contact', 'contact/', 'A form to get in touch with us');
INSERT INTO `fancentr__pages` VALUES(9, 'Cookies', 'cookies', 'cookies/', 'Information about how we use cookies');
INSERT INTO `fancentr__pages` VALUES(10, 'RSS feeds', 'rss', 'rss/', 'Your RSS feeds');
INSERT INTO `fancentr__pages` VALUES(11, 'Messages', 'messages', 'messages/', 'Send and receive private messages');

-- --------------------------------------------------------

--
-- Table structure for table `fancentr__private_messages`
--

CREATE TABLE `fancentr__private_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `message` text,
  `recipient_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `date_sent` datetime NOT NULL,
  `is_read` int(11) DEFAULT NULL,
  `replied` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`message_id`),
  KEY `sender_id_message` (`sender_id`),
  KEY `recipient_id_message` (`recipient_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Private messages between users' AUTO_INCREMENT=10 ;

--
-- Dumping data for table `fancentr__private_messages`
--

INSERT INTO `fancentr__private_messages` VALUES(4, 'Hello sekky', 'Hello!', 2, 1, '2012-10-08 18:06:45', 1, 0, 0);
INSERT INTO `fancentr__private_messages` VALUES(5, 'Hello sun', 'Hi, this is a message', 4, 1, '2012-10-08 18:07:36', 1, 0, 0);
INSERT INTO `fancentr__private_messages` VALUES(6, 'Hello Sunnefa', 'Hi sunnefa how are you?', 1, 3, '2012-10-09 18:09:19', 1, 0, 0);
INSERT INTO `fancentr__private_messages` VALUES(7, 'Hi', 'Let''s see if this comes as unread', 1, 4, '2012-10-09 18:13:34', 1, 0, 0);
INSERT INTO `fancentr__private_messages` VALUES(8, 'Re: Hi', 'It did, but if you had viewed it first it wouldn''t have. I have to change that.\r\nLet''s see if this comes as unread', 4, 1, '2012-10-09 18:15:18', 1, 0, 0);
INSERT INTO `fancentr__private_messages` VALUES(9, 'Re: Re: Hi', 'The question is what will we do about the old messages always appearing beneath? Is there some kind of quote thing we can do or should be just skip it?\r\nIt did, but if you had viewed it first it wouldn''t have. I have to change that.\r\nLet''s see if this comes as unread', 1, 4, '2012-10-09 18:23:05', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `fancentr__sites`
--

CREATE TABLE `fancentr__sites` (
  `site_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` mediumtext,
  `date_added` datetime NOT NULL,
  `url` varchar(255) NOT NULL,
  `loves` int(11) DEFAULT NULL,
  `hates` int(11) DEFAULT NULL,
  `visits` int(11) DEFAULT '0',
  `user_added` int(11) NOT NULL,
  `rss_ready` int(11) DEFAULT NULL,
  `feed_url` varchar(255) DEFAULT NULL,
  `screenshot` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`site_id`),
  KEY `user_added` (`user_added`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Basic information about sites listed' AUTO_INCREMENT=35 ;

--
-- Dumping data for table `fancentr__sites`
--

INSERT INTO `fancentr__sites` VALUES(1, 'Megan Fox Network &bullet; A Fansite Dedicated To Megan Denise Fox-Green &bullet; M-Fox.Org', 'Frequently updated news, photos, videos and more on Megan Fox.', '2012-06-07 00:00:00', 'http://m-fox.org/', NULL, NULL, 2, 1, 1, 'http://m-fox.org/feed/', NULL);
INSERT INTO `fancentr__sites` VALUES(2, 'Amberheardsource.org - Amber Heard Fansite', 'Welcome to amberheardsource.org a fansite dedicated to the lovely actress Amber Heard. You might have seen Amber Heard in movies such as ''All the Boys Love Mandy Lane'', ''The Stepfather'', and ''The Joneses'' as well as her up coming movie ''The Ward''. Here on', '2012-06-07 18:37:48', 'http://amberheardsource.org/', NULL, NULL, 5, 1, 1, 'http://amberheardsource.org/feed/', NULL);
INSERT INTO `fancentr__sites` VALUES(3, 'Amanda Seyfried Network - Your Elite Source For Everything Amanda Seyfried - A-seyfried.org', 'Frequently updated news, photos, videos and more on Amanda Seyfried.', '2012-06-07 18:48:15', 'http://a-seyfried.org/', NULL, NULL, 1, 1, 1, 'http://a-seyfried.org/feed/', NULL);
INSERT INTO `fancentr__sites` VALUES(4, 'Kate Hudson | Kate Hudson Web | Your ultimate source for Kate Hudson', 'Kate Hudson fansite with the latest news, pictures, information, media and much more.', '2012-06-07 18:49:48', 'http://katehudsonweb.com/', NULL, NULL, 1, 1, 1, 'http://katehudsonweb.com/feed/', NULL);
INSERT INTO `fancentr__sites` VALUES(10, 'Bethany J.com ~ Your source for actress Bethany Joy Galeotti  ', 'I know I have a bit to catch up on, but Bethany has released her rendition of &#8220;Someone to Watch Over Me&#8221; as part of her &#8220;Diamond Gothic&#8221; project. Chapters 2 through 5 have also', '2012-09-24 18:13:59', 'http://bethany-j.com/', NULL, NULL, 1, 1, 1, 'http://bethany-j.com/feed/', NULL);
INSERT INTO `fancentr__sites` VALUES(11, 'Fan Sites Network | Free hosting for your fansite!', 'The Fan Sites Network has been hosting celebrity related fan sites for over 8 years. We currently have over 800 Webmasters in our fan site family. Read what our dedicated Webmasters have to say about our FREE services&#8230;', '2012-09-24 18:14:56', 'http://fan-sites.org/', NULL, NULL, 1, 1, 1, 'http://fan-sites.org/feed/', NULL);
INSERT INTO `fancentr__sites` VALUES(12, 'Angelic Tarja Turunen', 'Angelic Tarja Turunen is your #1 fansite for the charismatic and beautiful Finish soprano singer, Tarja Turunen. We have all the latest news, pictures, videos and info on Tarja and her career.', '2012-09-24 18:15:34', 'http://angelictarjaturunen.com', NULL, NULL, 0, 1, 1, 'http://angelictarjaturunen.com/feed', NULL);
INSERT INTO `fancentr__sites` VALUES(13, 'Tracy Spiridakos Fan :: Your online resource dedicate to beautiful and talented actress Tracy Spiridakos  ', 'Tracy Spiridakos Fan :: Your online resource dedicate to beautiful and talented actress Tracy Spiridakos', '2012-09-26 13:46:07', 'http://tracyspiridakos.org/', NULL, NULL, 0, 1, 1, 'http://tracyspiridakos.org/feed/', NULL);
INSERT INTO `fancentr__sites` VALUES(16, 'Aguilera World || Your Elite Source for All Things Christina Aguilera  ', 'Welcome to AguileraWorld.com, the number one fansite source for all things Christina Aguilera. Aguilera World is here to deliver you up to date Christina news on the Pop Princess. As you may know Christina is currently working on promoting her new album ''Bionic'' and new movie ''Burlesque''. As she promotes the album and movie, we''ll be here every step of the way making sure you''re fully updated on all thinks Christina Aguilera on a day-to-day basis.', '2012-09-26 13:50:11', 'http://aguileraworld.com/', NULL, NULL, 3, 1, 1, 'http://www.aguileraworld.com/feed/', NULL);
INSERT INTO `fancentr__sites` VALUES(17, 'Rose Byrne', 'Welcome to roseNET, the newest Rose Byrne fansite on the net! roseNET is your #1 source for all things Rose Byrne! She is most known for her portrayal of Briseis from Troy and Alex from Wicker Park. Take a look around and don''t forget to sign the guestbook! We are proud to be hosted by Gertie and f-s(dot)org. You won''t find any extensive galleries, you can find those other places. Here, however, you''ll find loads of media and art, for your viewing pleasure!', '2012-09-26 15:11:19', 'http://rosefan.fan-sites.org/', NULL, NULL, 2, 1, 1, 'http://rosefan.fan-sites.org//feed', NULL);
INSERT INTO `fancentr__sites` VALUES(21, 'ToniBraxtonWeb.org &#8211; Toni Braxton | Your fansource for everything Toni Braxton', 'New season of VH1?s Behind the music returns Sunday, September 16 at 9/8c. Featuring Ne-Yo, Carrie Underwood, Train, Gym Class Heroes, Nicole Scherzinger, T.I. P!nk! and Toni Braxton!', '2012-09-26 17:55:44', 'http://tonibraxtonweb.org/', NULL, NULL, 1, 1, 1, 'http://www.tonibraxtonweb.org/feed/', NULL);
INSERT INTO `fancentr__sites` VALUES(22, 'Intoxicated Twilight | Your #1 resource for the Twilight Saga Franchise | Bella Swan, Edward Cullen, Twilight, New Moon, Eclipse, Breaking Dawn', 'Intoxicated Twilight is the first paparazzi free resource for the Twilight Saga films and books. New Moon coming in 2009!', '2012-09-26 17:57:21', 'http://intoxicated-twilight.com/', NULL, NULL, 0, 1, 1, 'http://intoxicated-twilight.com/feed/', NULL);
INSERT INTO `fancentr__sites` VALUES(23, 'The Cullen Couples - Longerthanalifetime.org', 'Welcome to Longerthanalifetime.org. Your fansite about the Cullen couples from the Twilight Saga.\r', '2012-09-26 18:00:33', 'http://longerthanalifetime.org/', NULL, NULL, 0, 1, 1, 'http://longerthanalifetime.org/?feed=rss2', NULL);
INSERT INTO `fancentr__sites` VALUES(25, 'Our Forever // A Twilight Fansite. Forever is Only the Beginning...', 'Welcome to Our-Forever.com! Your source dedicated to bringing you the latest news and pictures on the dazzling Twilight Saga books &amp; movies. Our extensive photo gallery currently has nearly 15,000 images! If you have any questions/comments, feel free ', '2012-09-26 18:03:40', 'http://twilightdazzles.com/', NULL, NULL, 0, 1, 1, 'http://our-forever.com/feed/', NULL);
INSERT INTO `fancentr__sites` VALUES(26, 'Ginnifer Goodwin Web', 'Hey and welcome to Ginnifer Goodwin Web, your soon-to-be number 1 resource for all things Ginnifer Goodwin.', '2012-10-03 14:47:54', 'http://ginnifer-goodwin.org/', NULL, NULL, 1, 5, 1, 'http://ginnifer-goodwin.org//feed', NULL);
INSERT INTO `fancentr__sites` VALUES(27, 'Chuck Media  ', 'Welcome to Chuck Media, a fansite dedicated to NBC''s hit show Chuck. We do our best to serve you with the latest news, pictures and everything else a Chuck fan needs. Take a look around and come back very soon! - Stephi', '2012-10-03 15:00:44', 'http://chuck-media.org/', NULL, NULL, 0, 5, 1, 'http://chuck-media.org/?feed=rss2', NULL);
INSERT INTO `fancentr__sites` VALUES(28, ' Zoe Saldana Online//Your Ultimate Source For Zoe Saldana', 'Welcome to Zoe Saldana Online your ultimate source for all things Zoe. Here we have your latest news, pictures, videos, and more about the actress Zoe Saldana. Feel free to contact us with any feedback or contributions you may have. We hope you enjoy and ', '2012-10-03 15:06:37', 'http://zoesaldanaonline.com/', NULL, NULL, 0, 5, 1, 'http://www.zoesaldanaonline.com/feed/', NULL);
INSERT INTO `fancentr__sites` VALUES(29, 'Nicole Anderson | nicole-anderson.com // The Largest Nicole Anderson Fan Resource On The Web // Multimedia // News Content &amp; Much More!', 'Welcome to nicole-anderson.org your largest & most comprehensive daily dose dedicated to our favorite girl Nicole Anderson. Nicole is best known for roles on hit Tv Shows such as Make It Or Break It or movies such as Accused at Seventeen and Mean Girls 2.Our goal is to bring you the latest news,images,media clips and more. Here you will find everything you need on Nicole,multimedia features,an extensive gallery and a lot more! We hope you enjoy your visit!', '2012-10-03 15:09:41', 'http://nicole-anderson.com/', NULL, NULL, 1, 5, 1, 'http://nicole-anderson.com//feed', NULL);
INSERT INTO `fancentr__sites` VALUES(30, 'Ryan Reynolds | Ryan Reynolds Online | The most up to date Ryan Reynolds Fansite', 'Ryan Reynolds Online. Your largest, most comprehensive fansite for Mr Ryan Reynolds star of The Green Lantern, Buried and X-men Origins Wolverine', '2012-10-03 16:05:10', 'http://ryanreynolds.org/', NULL, NULL, 0, 6, 1, 'http://ryanreynolds.org/feed/', NULL);
INSERT INTO `fancentr__sites` VALUES(31, 'Alexander-Skarsgard.net', 'Welcome to ALEXANDER-SKARSGARD.NET. This is your fan connection to the talented Swedish actor Alexander Skarsgard. Best known for his roles as vampire Eric Northman on the HBO series True Blood, Meekus in Zoolander and Brad Colbert in the HBO miniseries G', '2012-10-03 16:24:14', 'http://alexander-skarsgard.net/', NULL, NULL, 0, 6, 1, 'http://alexander-skarsgard.net//feed', NULL);
INSERT INTO `fancentr__sites` VALUES(33, 'candiceaccola.us', 'Welcome to Candiceaccola.us we are a fansite for the beautiful and talented actress and singer Candice Accola, you would know her from the The Vampire Diaries as Caroline she has also been in films like juno and the truth about angels.you will find News,I', '2012-10-03 16:30:09', 'http://candiceaccola.us/', NULL, NULL, 0, 6, 1, 'http://candiceaccola.us//feed', NULL);
INSERT INTO `fancentr__sites` VALUES(34, 'Nina Dobrev Fan', 'Welcome to NINADOBREVFAN.US we are a fansite dedicated to the beautiful and talented actress Nina Dobrev.You may know Nina from her role on DEGRASSI or her current show THE VAMPIRE DIARIES.We are here to provide you with all the latest news,info and media on Nina and her projects. So enjoy and COME BACK SOON!', '2012-10-03 16:31:55', 'http://ninadobrevfan.us/', NULL, NULL, 3, 6, 1, 'http://ninadobrevfan.us//feed', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fancentr__text`
--

CREATE TABLE `fancentr__text` (
  `text_id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `text_name` varchar(60) NOT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`text_id`),
  KEY `text_page` (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Text used by pages that don''t change' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `fancentr__text`
--

INSERT INTO `fancentr__text` VALUES(1, '<h1>Fancentr.org Terms of Use</h1>\r\n<p>By visiting Fancentr.org, you are bound by the following terms. In the terms below, "we" or "us" refers to the owners and/or maintainers of the website.</p>\r\n<ol>\r\n    <li>You agree not to threaten, harass, take legal action against, make demands of us, or force our signature on any legal contracts, no matter what the reason, even though you feel you might have the legal rights to do so.</li>\r\n    <li>You agree not to reproduce any part of our website in anyway anywhere else.</li>\r\n    <li>You agree that you will not hold us liable for any content you might find on our website.</li>\r\n    <li>You understand that unless we specifically state otherwise, we do not claim any legal ownership, copyright or intellectual property rights of any images or content on our website.</li>\r\n    <li>You understand that we do now knowingly intend to violate any copyrights or intellectual property rights.</li>\r\n    <li>To our knowledge all images, videos, audio and photos, if any, are being used in full compliance with US Copyright Law, specifically the Fair Use Copyright law, clause 107.</li>\r\n    <li>You understand that we are not affiliated with or have any connections to any celebrities, bands, companies or other entities mentioned on the website, nor do we claim to be.</li>\r\n    <li>You understand that our site is a fan operated website, created by fans for other fans and we do not earn any commission for any part of our website in any way.</li>\r\n    <li>You understand that these terms apply too any and all parts of our website and any other websites affiliated with and using the name Fancentr.org, including our Facebook and Twitter pages.</li>\r\n    <li>We reserve the right to change any part of our website, including these terms and our privacy policy, at any time and without notice.</li>\r\n</ol>', 'tos', 5);
INSERT INTO `fancentr__text` VALUES(2, '<h1>Fancentr.org DMCA</h1>\r\n\r\n<p>Fancentr.org is not responsible for the content of any fansits listed in the directory or for any content individual users share on the site, but we will in all cases respect the ownership copyright holders have over their material. It is our belief that all content on our main domain and associated sites is being used in accordance with Fair Use Copyright Law 107.</p>\r\n\r\n<p>If you as a copyright holder feel that your rights have been infringed by any content on our site, please notify us and we will take the requested action immediately. The following information needs to be submitted:</p>\r\n\r\n<ol>\r\n	<li>An identification of the allegedly infringed material.</li>\r\n	<li>An identification of the location of the infringed material so we can identify and remove it more easily</li>\r\n	<li>Accurate contact information, including your name, e-mail address, phone number and address</li>\r\n	<li>Both these statements, signed by you, with either a digital signature or a scan of your signature:\r\n	<ul>\r\n		<li>"I hereby certify that I am the copyright holder of the material this notice relates to or am authorized to speak on their behalf."<li>\r\n		<li>"I have good reason to belief that the material this notice relates to is not being used in accordance with the law or with the copyright holder''s permission."<li>\r\n	</ul>\r\n</ol>\r\n\r\n<p>By sending us such a notice, as well as by visiting this site as per our <a href="http://www.fancentr.org/terms_of_use">Terms of Use</a>, you agree to take no legal action against Fancentr.org.</p>\r\n\r\n<p>Failure to submit a notice to use will result in no action on our part.</p>\r\n\r\n<p>Notices should be sent to webmaster@fancentr.org with the words ''Copyright infringement'' in the subject.</p>', 'dmca', 6);
INSERT INTO `fancentr__text` VALUES(3, '<h1>Fancentr.org Privacy Policy</h1>\r\n\r\n<p>The following privacy policy applies to any and all information collected by Fancentr.org, hereafter referred to as &quot;us&quot; or &quot;we&quot;, about our visitors and what we do with that information.</p>\r\n\r\n<ul>\r\n    <li>At present the only information we collect is volunteered by visitors who opt to provide us with their name and email address. We collect this information for our visitors benefit, so we can send them an email when our website is fully completed. We do not collect any information our visitors are not willing to share, nor do we plant any cookies to track our visitors behaviours.</li>\r\n    \r\n    <li>\r\n        We will never give or sell any visitor information to third parties or advertisers.\r\n    </li>\r\n    \r\n    <li>\r\n        If you would like us to remove your information from our database, please contact us by sending an email to <a href="mailto:webmaster@fancentr.org">webmaster@fancentr.org</a> with your name and email address and we will remove your information from our database as soon as possible.\r\n    </li>\r\n    \r\n    <li>\r\n        We reserve the right to change any part of our website, including our terms of use and privacy policy at any time and without notice.\r\n    </li>\r\n</ul>', 'privacy', 4);
INSERT INTO `fancentr__text` VALUES(4, '<h1>The Fancentr.org crawler</h1>\r\n\r\n<p>Frequently asked questions about the Fancentr.org crawler</p>\r\n\r\n<ol>\r\n    <li>Q: Why do I see <em>Fancentr.org (http://www.fancentr.org/crawler/)</em> in the list of user agents who visit my site?\r\n    <p>A: Because in order for us to be able to add sites to our directory we need to scan them to find their title, description, keywords and RSS feed link.</p>\r\n    </li>\r\n    \r\n    <li>Q: Are you doing this to steal our content?\r\n        <p>A: No, we would never do anything like that. We do not store any of the content on a site except its title, description, keywords and RSS feed link. This is in most cases the same information that search engines like Google, Yahoo and Bing store, sometimes even less. Please note that we also do not crawl anything but the index page, because this is not a search engine, it''s just a directory of websites.</p>\r\n    </li>\r\n    \r\n    <li>Q: Why do you need to store this information?\r\n    <p>A: Because if we did not store the title and description of each site we list users wouldn''t know anything about the sites. The keywords are stored to make searching easier. The RSS feed link is stored to allow users to create custom RSS feeds of their favorite sites enabling them to check for new updates on several sites at once in one place.</p>\r\n    </li>\r\n    \r\n    <li>Q: Does your crawler respect my robots.txt preferences?\r\n    <p>A: Yes, it does. Please note, however, that our crawler is a work in progress so there might be cases where our programming and your robots.txt rules don''t match up. If that happens, please accept our apologies and understand that we are not crawling your site to be mean or steal anything.</p>\r\n    </li>\r\n    \r\n    <li>Q: Why do some of the sites in your database have no description or something weird like "Site statistics.." and not the real description of the site?\r\n        <p>A: Because we do not allow entering a description manually. We crawl the site to look for the site''s meta description, or failing that a welcome text, which is common on many fansites. If those two things are both missing we simply take the text in the very first &LTp&GT; tag we find. This often works, but depending on the page''s HTML structure might not give us the text we were hoping for. We are currently working on improving this to look for other factors as well.</p>\r\n<p>Periodically we will review the sites  that have strange or missing descriptions and update them manually. You can also help by letting us know and sending us the real description using our <a href="contact">contact form</a>.</p>\r\n    </li>\r\n    \r\n    <li>Q: I tried to add my site but I kept getting the message that it''s not a fansite, which is not true. Why can''t I add my site to your directory?\r\n        <p>We apologize for that. Our crawler works by downloading the site''s HTML and looking for certain keywords. At the moment our list of keywords is very small and only in English. We plan on expanding the keyword list to include both more words that are common between fansites and in other languages. If your site is in a foreign language, you can send us a list of words commonly used on your site which we can add to our crawler.</p>  \r\n    </li>\r\n</ol>', 'crawler', 7);
INSERT INTO `fancentr__text` VALUES(5, '<h1>Cookies</h1>\r\n<p>A few words about how Fancentr.org uses cookies</p>\r\n<ul>\r\n<li>Fancentr.org (hereafter referred to as us or we) only uses cookies to keep track of users who are logged in.</li>\r\n<li>This means that cookies are only used for users who are registered and only for their convenience. We do not use cookies to gather any information nor do we use any cookies for users who are not logged in.</li>\r\n<li>Please note that we are showing Google ads on our site. We are not responsible for Google''s cookie behavior, they might or might not plant cookies on your system that are beyond our control.</li>\r\n</ul>', 'cookies', 9);

-- --------------------------------------------------------

--
-- Table structure for table `fancentr__users`
--

CREATE TABLE `fancentr__users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(55) NOT NULL,
  `display_name` varchar(55) DEFAULT NULL,
  `email` varchar(55) NOT NULL,
  `bio` text,
  `date_registered` datetime NOT NULL,
  `is_logged_in` int(11) DEFAULT NULL,
  `last_login_from` varchar(20) NOT NULL,
  `display_picture` varchar(255) DEFAULT NULL,
  `password` varchar(55) NOT NULL,
  `active` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Basic information about registered users' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `fancentr__users`
--

INSERT INTO `fancentr__users` VALUES(1, 'Sunnefa', 'Sunnefa Lind', 'sunnefa_lind@hotmail.com', 'Hi, I''m Sunnefa and this is my profile. I''m a big fan of stuff and I like things.', '2012-09-21 11:27:34', 0, '127.0.0.1', 'cb703549a67a6355401c05cac7ab30c5.jpg', '81dc9bdb52d04dc20036dbd8313ed055', 1);
INSERT INTO `fancentr__users` VALUES(2, 'Sekky', 'Sekaya', 'shannen.sekaya@gmail.com', 'Hi, I''m Sekaya!', '2012-10-03 13:59:54', 1, '127.0.0.1', 'c0b1721b7eb60e76bd0a36d11c7121fb.jpg', '81dc9bdb52d04dc20036dbd8313ed055', 1);
INSERT INTO `fancentr__users` VALUES(3, 'Sunny', 'Display', 'sekaya@fancentr.org', NULL, '2012-10-03 14:22:30', 0, '127.0.0.1', NULL, '81dc9bdb52d04dc20036dbd8313ed055', 1);
INSERT INTO `fancentr__users` VALUES(4, 'Sun', 'Sun', 'sekaa@fancentr.org', NULL, '2012-10-03 14:26:31', 1, '127.0.0.1', NULL, '81dc9bdb52d04dc20036dbd8313ed055', 1);
INSERT INTO `fancentr__users` VALUES(5, 'Sum', 'Suny', 'sum@fancentr.org', NULL, '2012-10-03 14:30:18', 0, '127.0.0.1', NULL, '81dc9bdb52d04dc20036dbd8313ed055', 1);
INSERT INTO `fancentr__users` VALUES(6, 'Summy', 'Sune', 'sunmf@fancentr.org', NULL, '2012-10-03 15:17:22', 0, '127.0.0.1', NULL, '81dc9bdb52d04dc20036dbd8313ed055', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fancentr__feeds`
--
ALTER TABLE `fancentr__feeds`
  ADD CONSTRAINT `user_id_feed` FOREIGN KEY (`user_id`) REFERENCES `fancentr__users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `fancentr__feeds_sites`
--
ALTER TABLE `fancentr__feeds_sites`
  ADD CONSTRAINT `feed_id_jn` FOREIGN KEY (`feed_id`) REFERENCES `fancentr__feeds` (`feed_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `site_id_jn` FOREIGN KEY (`site_id`) REFERENCES `fancentr__sites` (`site_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `fancentr__modules_pages`
--
ALTER TABLE `fancentr__modules_pages`
  ADD CONSTRAINT `module_id_jn` FOREIGN KEY (`module_id`) REFERENCES `fancentr__modules` (`module_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `page_id_jn` FOREIGN KEY (`page_id`) REFERENCES `fancentr__pages` (`page_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `fancentr__private_messages`
--
ALTER TABLE `fancentr__private_messages`
  ADD CONSTRAINT `recipient_id_message` FOREIGN KEY (`recipient_id`) REFERENCES `fancentr__users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `sender_id_message` FOREIGN KEY (`sender_id`) REFERENCES `fancentr__users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `fancentr__sites`
--
ALTER TABLE `fancentr__sites`
  ADD CONSTRAINT `user_added` FOREIGN KEY (`user_added`) REFERENCES `fancentr__users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `fancentr__text`
--
ALTER TABLE `fancentr__text`
  ADD CONSTRAINT `text_page` FOREIGN KEY (`page_id`) REFERENCES `fancentr__pages` (`page_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
