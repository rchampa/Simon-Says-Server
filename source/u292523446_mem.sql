
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 11-11-2013 a las 15:27:34
-- Versión del servidor: 5.1.66
-- Versión de PHP: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `u292523446_mem`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `player_name` varchar(25) NOT NULL,
  `friend_name` varchar(25) NOT NULL,
  `friendship_added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `friendship_updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state` enum('waiting_for_response','accepted','rejected') NOT NULL DEFAULT 'waiting_for_response',
  PRIMARY KEY (`player_name`,`friend_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `friends`
--

INSERT INTO `friends` (`player_name`, `friend_name`, `friendship_added_at`, `friendship_updated_at`, `state`) VALUES
('Ricardo', 'Test0', '2013-08-04 21:27:59', '2013-08-04 21:28:27', 'accepted'),
('Ricardo', 'Test1', '2013-08-04 21:33:56', '2013-08-04 21:34:16', 'accepted'),
('Ricardo', 'Test2', '2013-08-04 21:38:07', '2013-08-04 21:38:23', 'accepted'),
('emecas', 'Ricardo', '2013-08-05 18:13:48', '2013-08-05 18:18:58', 'accepted'),
('ricardomarica', 'Ricardo', '2013-08-04 23:40:49', '2013-08-04 23:41:21', 'accepted'),
('emecas', 'Juan', '2013-08-05 18:10:53', '0000-00-00 00:00:00', 'waiting_for_response'),
('SrSandia', 'Ricardo', '2013-08-06 08:15:03', '2013-08-06 12:33:53', 'accepted'),
('Kirtash', 'Ricardo', '2013-08-06 12:25:50', '2013-08-06 12:26:07', 'accepted'),
('barbi', 'Ricardo', '2013-10-26 10:50:55', '2013-10-26 10:51:09', 'accepted'),
('richard', 'ricardo', '2013-11-03 17:11:09', '2013-11-03 17:14:43', 'accepted'),
('uu', 'gabri', '2013-11-10 20:21:07', '0000-00-00 00:00:00', 'waiting_for_response'),
('uu', 'Test0', '2013-11-10 22:46:41', '2013-11-10 22:47:01', 'accepted');

--
-- Disparadores `friends`
--
DROP TRIGGER IF EXISTS `friendship_updated`;
DELIMITER //
CREATE TRIGGER `friendship_updated` BEFORE UPDATE ON `friends`
 FOR EACH ROW begin
		if new.state != old.state then  
				set new.friendship_updated_at = NOW();
		end if;
	end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `game_id` int(11) NOT NULL AUTO_INCREMENT,
  `player1_name` varchar(25) NOT NULL,
  `player2_name` varchar(25) NOT NULL,
  `game_created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `game_updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `level` smallint(5) unsigned NOT NULL DEFAULT '4',
  `score1` smallint(5) unsigned NOT NULL,
  `score2` smallint(5) unsigned NOT NULL,
  `state` enum('waiting_for_response','waiting_player1','waiting_player2','refused','finished') NOT NULL DEFAULT 'waiting_for_response',
  PRIMARY KEY (`game_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `games`
--

INSERT INTO `games` (`game_id`, `player1_name`, `player2_name`, `game_created_at`, `game_updated_at`, `level`, `score1`, `score2`, `state`) VALUES
(1, 'Ricardo', 'Test0', '2013-08-04 21:28:36', '2013-08-04 21:36:10', 5, 0, 1, 'waiting_player2'),
(2, 'Ricardo', 'Test1', '2013-08-04 21:34:28', '2013-08-04 21:37:48', 4, 0, 0, 'waiting_player2'),
(3, 'Test2', 'Ricardo', '2013-08-04 21:38:36', '2013-08-04 21:40:08', 5, 0, 0, 'waiting_player1'),
(4, 'ricardomarica', 'Ricardo', '2013-08-04 23:42:54', '2013-08-05 18:22:37', 8, 0, 1, 'waiting_player1'),
(5, 'emecas', 'Ricardo', '2013-08-05 18:19:19', '2013-08-29 06:20:05', 10, 0, 2, 'waiting_player2'),
(6, 'Kirtash', 'Ricardo', '2013-08-06 12:34:05', '2013-08-08 16:43:06', 7, 0, 3, 'waiting_player1'),
(7, 'Ricardo', 'SrSandia', '2013-08-07 17:03:55', '0000-00-00 00:00:00', 4, 0, 0, 'waiting_for_response'),
(8, 'barbi', 'Ricardo', '2013-10-26 10:51:21', '2013-10-26 10:51:32', 4, 0, 0, 'waiting_player1'),
(9, 'richard', 'ricardo', '2013-11-03 17:14:56', '2013-11-03 17:16:37', 4, 0, 0, 'waiting_player2'),
(10, 'uu', 'Test0', '2013-11-10 22:47:21', '2013-11-10 22:48:32', 4, 0, 0, 'waiting_player2');

--
-- Disparadores `games`
--
DROP TRIGGER IF EXISTS `game_updated`;
DELIMITER //
CREATE TRIGGER `game_updated` BEFORE UPDATE ON `games`
 FOR EACH ROW begin
            if new.state != old.state then  
                    set new.game_updated_at = NOW();
            end if;
	end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `moves`
--

CREATE TABLE IF NOT EXISTS `moves` (
  `move_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `player_name` varchar(25) NOT NULL,
  `move` varchar(60) NOT NULL,
  `move_created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`move_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Volcado de datos para la tabla `moves`
--

INSERT INTO `moves` (`move_id`, `game_id`, `player_name`, `move`, `move_created_at`) VALUES
(1, 1, 'Ricardo', '3-1-2-0-', '2013-08-04 21:33:31'),
(2, 1, 'Test0', '0-1-2-3', '2013-08-04 21:35:30'),
(3, 1, 'Ricardo', '0-1-2-3-0-', '2013-08-04 21:36:10'),
(4, 2, 'Ricardo', '2-3-0-1-', '2013-08-04 21:37:48'),
(5, 3, 'Test2', '1-2-3-0', '2013-08-04 21:39:39'),
(6, 3, 'Ricardo', '3-2-1-0-', '2013-08-04 21:40:08'),
(7, 4, 'ricardomarica', '3-1-0-2-', '2013-08-04 23:46:17'),
(8, 4, 'Ricardo', '3-1-0-2-', '2013-08-04 23:47:06'),
(9, 4, 'ricardomarica', '0-3-2-1-3-', '2013-08-04 23:48:22'),
(10, 4, 'Ricardo', '0-2-3-1-3-', '2013-08-04 23:49:46'),
(11, 4, 'ricardomarica', '0-1-3-0-1-3-', '2013-08-05 00:28:56'),
(12, 4, 'Ricardo', '2-1-0-3-2-1-', '2013-08-05 00:38:06'),
(13, 4, 'ricardomarica', '0-2-1-3-0-2-1-', '2013-08-05 11:48:34'),
(14, 5, 'emecas', '0-3-2-1-', '2013-08-05 18:20:23'),
(15, 5, 'Ricardo', '0-1-2-3-', '2013-08-05 18:20:43'),
(16, 5, 'emecas', '0-1-2-3-3-', '2013-08-05 18:22:25'),
(17, 4, 'Ricardo', '3-1-3-1-3-1-0-', '2013-08-05 18:22:37'),
(18, 5, 'Ricardo', '0-2-3-1-2-', '2013-08-05 18:23:39'),
(19, 5, 'emecas', '0-2-3-1-2-2-', '2013-08-05 18:24:36'),
(20, 5, 'Ricardo', '0-3-1-2-0-1-', '2013-08-05 18:25:01'),
(21, 5, 'emecas', '0-3-2-1-1-2-3-', '2013-08-05 18:26:07'),
(22, 5, 'Ricardo', '0-3-1-2-3-0-1-', '2013-08-05 19:10:17'),
(23, 5, 'emecas', '3-3-3-0-0-1-2-3-', '2013-08-05 19:17:08'),
(24, 5, 'Ricardo', '3-0-1-2-3-0-1-2-', '2013-08-06 08:12:50'),
(25, 5, 'emecas', '0-3-2-1-3-0-2-3-1-', '2013-08-06 08:25:10'),
(26, 6, 'Kirtash', '0-3-1-2-', '2013-08-06 12:40:36'),
(27, 6, 'Ricardo', '0-2-1-3-', '2013-08-06 12:42:00'),
(28, 6, 'Kirtash', '0-3-2-0-3-', '2013-08-06 12:44:05'),
(29, 6, 'Ricardo', '0-3-0-3-0-', '2013-08-06 12:44:52'),
(30, 6, 'Kirtash', '3-1-0-2-3-1-', '2013-08-06 12:49:00'),
(31, 6, 'Ricardo', '3-0-1-2-3-0-', '2013-08-08 16:43:06'),
(32, 5, 'Ricardo', '0-1-2-3-0-1-2-3-0-', '2013-08-28 22:53:30'),
(33, 5, 'emecas', '0-3-1-2-0-3-1-2-0-3-', '2013-08-29 06:20:05'),
(34, 9, 'richard', '0-3-2-1-', '2013-11-03 17:16:37'),
(35, 10, 'uu', '0-3-2-1-', '2013-11-10 22:48:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `name` varchar(25) NOT NULL,
  `password` varchar(25) NOT NULL,
  `email` varchar(25) NOT NULL,
  `gcm_id` text NOT NULL,
  `isEnable` tinyint(1) NOT NULL DEFAULT '1',
  `user_created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`name`, `password`, `email`, `gcm_id`, `isEnable`, `user_created_at`, `user_updated_at`) VALUES
('Ricardo ', '0000', 'rizadohh.com', 'APA91bGZpw2D15Tcdvi4Jh372cZg2u-mEENaDXda3RBFZDbxX23LxcojbkC0rF5sl0ElJ0THseCnhXkHeLrcpSKItH_8SUikU_VfO4laK4pbOZJHA4vW1eKCEZu97scqyfIMIPXDQWk_M_2uq61rDBm1QypxbAnHtw', 1, '2013-08-03 18:11:45', '2013-11-10 16:07:41'),
('Test0', '000', '000', '000', 1, '2013-08-03 18:12:25', '0000-00-00 00:00:00'),
('Test1', '0000', '0000', '0000', 1, '2013-08-03 18:13:16', '0000-00-00 00:00:00'),
('Test2', '0000', '0000', '0000', 1, '2013-08-03 18:14:07', '0000-00-00 00:00:00'),
('emecas', '1234', 'emecas@gmail.com', 'APA91bEXkwlD1rn1I0MoZGY7xa88PngH3qZH4WxCMV0GGlF8BZEjTrB3sNHmFxoS-OOtQQDStOMIKY7e4gzAwHwUvwn7GpqD4vD_tDw-H3Zvwaq4xvp2esA_n7ZUu5YmNciMmQ8oZuiReuvlzGUnp1vcNyIvpIXVzg', 1, '2013-08-04 22:18:57', '2013-08-05 18:02:06'),
('ricardomarica', 'marica', 'sss@dddd.com', 'APA91bHbPoypCw_qiLeglQm7gM_lvMf9tyVE7cCHcR0lIXLcL-wg5BiSpXd-lpAfNxHB-3vSjr5D0A7PCDDGEYoyBMDl70qqbp87yCTYt-fO3GzPYBK8F0Ihubni7eY9oN15zWToYkU03nJJOsZbEwZ4A7M7kkCURg', 1, '2013-08-04 23:05:59', '0000-00-00 00:00:00'),
('Juan', '0000', '0000', '0000', 1, '2013-08-05 18:10:32', '0000-00-00 00:00:00'),
('SrSandia', 'cancel', 'germanperezs@gmail.com', 'APA91bHEmX5ATsNjyIenZqFwu0JG_RruMC4nylfrIptWoVLQk3NdPWl4uCT030AuIN-obE_qFk561JMXrND-M554ozKhcE4skbZMLE3mprdSGcTWS6OnXj668pLb3vZNgCHETVvzVvyqrGeR25TScrQ65O7xrL7QTQ', 1, '2013-08-06 08:14:50', '0000-00-00 00:00:00'),
('Kirtash', 'pepito23', 'a@a.com', 'APA91bGG3OKIsEw9_dfBLOmYNda8ZblSZ0p4URys8bwOPH4qlBKB3vhpRcCQQvPhKOKo5FFoDsgQFg-uOC5Ie4OK9gyxE1FGPbNHyCX7pQJHoSyTyJPGtaB8yg0qA_5m7WzHQxuT7gCXyXKkGqcZGwVbSLDlLNcgKw', 1, '2013-08-06 12:23:43', '0000-00-00 00:00:00'),
('gabri', 'protox', 'faleirogabrielf@gmail.com', 'APA91bHe6x3_spQGF9CmL_vJpVOY3ZXSGqvcUw920Tb50QU9Dt9WPxS7DKeZtDct2FGdC74Yygq9NkovbFMAetb3GS0-bruDurgJIjMZMWXCWkHzvoeUdAwdwm1DmHiYWAcUxRwZhTEnkK_OeIhS7QergcyFSqTY5w', 1, '2013-08-19 08:13:21', '0000-00-00 00:00:00'),
('barbi', 'barbi', 'ramiro1mbarbarmaim', 'APA91bEHPWvNlCkPIi3QaNri58nQzWsLJ7N8k-U0l96V7czuDgvI6wSJ3OybDXiiY29ApWbvpS8t58x6stOKouFVz67Cex4CNoZ24LGlRzCryuHQ6OCIM1Rfiz-_HNQQVsbqKfS4Y8PPmBZl4DMqOGu8zIPsaxunVQ', 1, '2013-10-26 10:50:17', '0000-00-00 00:00:00'),
('richard', 'pichipi', 'richardjordan310', 'APA91bETm0m7xQ6Hx2us0zlFvUCXIrM5XcGRj856O0dthiRlgwcqBJdiYzxJuN15e7_o0NbftHUhB7ICfCNVz0xMB4lVAhN4IAhmj-tN_ZoEn1F__BZhl4DIgm7WoZy0sKN00L4PT1PnpXcJyVWRqq_wyIh6olhOmw', 1, '2013-11-03 17:09:52', '0000-00-00 00:00:00'),
('uu', 'uu', 'uu', 'APA91bGZpw2D15Tcdvi4Jh372cZg2u-mEENaDXda3RBFZDbxX23LxcojbkC0rF5sl0ElJ0THseCnhXkHeLrcpSKItH_8SUikU_VfO4laK4pbOZJHA4vW1eKCEZu97scqyfIMIPXDQWk_M_2uq61rDBm1QypxbAnHtw', 1, '2013-11-10 19:05:43', '0000-00-00 00:00:00');

--
-- Disparadores `users`
--
DROP TRIGGER IF EXISTS `user_updated`;
DELIMITER //
CREATE TRIGGER `user_updated` BEFORE UPDATE ON `users`
 FOR EACH ROW begin
		if new.gcm_id != old.gcm_id then  
				set new.user_updated_at = NOW();
		end if;
	end
//
DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
