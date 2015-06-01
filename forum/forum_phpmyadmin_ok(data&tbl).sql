-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2015 at 09:31 PM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `forum`
--

-- --------------------------------------------------------

--
-- Table structure for table `forum_answer`
--

CREATE TABLE IF NOT EXISTS `forum_answer` (
  `question_id` int(4) NOT NULL DEFAULT '0',
  `a_id` int(4) NOT NULL DEFAULT '0',
  `a_name` varchar(65) NOT NULL DEFAULT '',
  `a_email` varchar(65) NOT NULL DEFAULT '',
  `a_answer` longtext NOT NULL,
  `a_datetime` varchar(25) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forum_answer`
--

INSERT INTO `forum_answer` (`question_id`, `a_id`, `a_name`, `a_email`, `a_answer`, `a_datetime`) VALUES
(3, 1, '', '', '', '01/06/15 18:48:10'),
(3, 2, '', '', '', '01/06/15 18:52:19'),
(4, 1, '', '', '', '01/06/15 18:57:44'),
(4, 2, '', '', '', '01/06/15 19:00:55'),
(4, 3, '', '', '', '01/06/15 19:00:59'),
(5, 1, '', '', '', '01/06/15 19:20:20'),
(7, 1, 'Es una broma', 'sisi@com.com', 'es una cosa si si', '01/06/15 20:09:21'),
(7, 2, '', '', '', '01/06/15 20:17:24'),
(7, 3, 'test', 'test', 'Broma', '01/06/15 20:32:24'),
(7, 4, 'test', 'test', 'efefef', '01/06/15 20:50:11'),
(7, 5, 'test', 'test', 'Aixó es un missatge per tots aques que \r\ncosa de la bona \r\ncosa', '01/06/15 20:59:30'),
(7, 6, 'test', 'test', 'wqdqw\r\nqdw\r\nqqwd\r\n', '01/06/15 21:01:14'),
(7, 7, 'test', 'test', 'rthrthrh\r\nhrthrh\r\nrthrth', '01/06/15 21:01:46'),
(7, 8, 'test', 'test', 'bla bla bla', '01/06/15 21:03:48'),
(8, 1, 'test', 'test', '', '01/06/15 21:45:17'),
(8, 2, 'test', 'test', 'ddd', '01/06/15 21:45:21'),
(14, 1, 'test', 'test', '', '01/06/15 21:57:44'),
(13, 1, 'test', 'test', 'wefwfwef', '02/06/15 09:12:18'),
(15, 1, 'test', 'test', '', '01/06/15 21:58:53'),
(15, 2, 'test', 'test', '', '01/06/15 21:59:10'),
(11, 1, 'test', 'test', '', '01/06/15 21:59:23'),
(17, 1, 'test', 'test', 'Coses', '02/06/15 08:11:24'),
(17, 2, 'test', 'test', '', '01/06/15 22:01:47'),
(16, 1, 'test', 'test', '', '01/06/15 22:06:11'),
(16, 2, 'test', 'test', '', '01/06/15 22:06:16'),
(6, 1, 'test', 'test', 'Pellentesque in tellus ipsum. Curabitur velit nisl, placerat at mi at, dapibus accumsan risus. Nunc sed tortor scelerisque, fringilla eros sed, finibus risus. Nullam vel semper orci, vel volutpat velit. Mauris hendrerit vitae velit in scelerisque. Donec tristique pulvinar interdum. In vitae dapibus lorem. Fusce vel facilisis lectus, ut fringilla elit. Nam quis libero eget velit lacinia ultricies sed eu mi. Nullam in turpis ultrices, hendrerit purus vitae, tempor dui. Pellentesque laoreet, velit id finibus hendrerit, nisi velit iaculis turpis, ut laoreet tortor arcu a libero. Morbi at ante facilisis, semper elit vitae, ornare libero. Sed tristique tristique augue dapibus volutpat.', '01/06/15 22:09:58'),
(17, 3, 'test', 'test', 'Bromaaaaa de coses', '02/06/15 08:08:43'),
(18, 1, 'test', 'test', 'Es una resposta', '02/06/15 17:17:42'),
(14, 2, 'test', 'test', 'tomàquet 2.0', '02/06/15 08:30:57'),
(13, 2, 'test', 'test', 'efwfewfe', '02/06/15 21:12:15');

-- --------------------------------------------------------

--
-- Table structure for table `forum_question`
--

CREATE TABLE IF NOT EXISTS `forum_question` (
  `id` int(4) NOT NULL,
  `id_team` int(11) NOT NULL,
  `open` tinyint(1) NOT NULL DEFAULT '1',
  `topic` varchar(255) NOT NULL DEFAULT '',
  `detail` longtext NOT NULL,
  `name` varchar(65) NOT NULL DEFAULT '',
  `email` varchar(65) NOT NULL DEFAULT '',
  `datetime` varchar(25) NOT NULL DEFAULT '',
  `view` int(4) NOT NULL DEFAULT '0',
  `reply` int(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forum_question`
--

INSERT INTO `forum_question` (`id`, `id_team`, `open`, `topic`, `detail`, `name`, `email`, `datetime`, `view`, `reply`) VALUES
(1, 0, 0, 'Hola', 'Broma', 'Victor', 'xvictorx@hotmail.com', '01/06/15 06:33:30', 21, 0),
(2, 0, 0, '', '', '', '', '01/06/15 06:41:29', 5, 0),
(3, 0, 1, '', '', '', '', '01/06/15 06:44:00', 11, 2),
(4, 0, 1, '', '', '', '', '01/06/15 06:52:36', 24, 3),
(5, 0, 1, '', '', '', '', '01/06/15 07:10:42', 12, 1),
(6, 0, 1, 'Aixó es un titol per que si-', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut facilisis odio vitae blandit varius. Etiam congue, lectus elementum commodo tempus, urna augue laoreet purus, ac porttitor diam risus in ex. Donec aliquam porta ipsum, sit amet luctus quam mattis a. Cras finibus tellus at sapien vehicula, quis aliquam augue iaculis. Suspendisse diam diam, elementum a nibh et, lobortis porttitor magna. In vestibulum euismod augue, eu tincidunt libero pulvinar nec. In tristique ex orci, blandit laoreet lacus blandit at. Suspendisse dignissim nisi at turpis luctus, eu sodales nunc aliquet.\r\n\r\nPraesent gravida eleifend velit et egestas. Mauris ut urna augue. Sed augue ipsum, varius sit amet eleifend vel, bibendum vitae nisl. Sed porttitor iaculis nulla mattis varius. Vivamus nisl ante, tincidunt in augue placerat, porta cursus velit. Cras et ullamcorper mauris. Curabitur efficitur non purus iaculis viverra. Praesent vitae tincidunt mi. Sed quis neque felis. Suspendisse gravida metus lacus, congue vehicula sem blandit sit amet.\r\n\r\nIn hac habitasse platea dictumst. Mauris commodo velit purus, vel lobortis erat tempus sit amet. Donec auctor cursus diam, at viverra turpis pellentesque vel. Integer id neque lorem. In et metus et orci aliquet mattis. Integer efficitur nunc id varius tempus. Nunc eu lectus risus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec mattis gravida rhoncus. Phasellus quam ante, hendrerit varius imperdiet vel, mollis eu lacus. Cras congue odio id dolor aliquam, a pellentesque sapien blandit.\r\n\r\nSed laoreet augue risus, vel auctor augue ultrices a. Cras lobortis metus sit amet libero lacinia, in laoreet justo condimentum. Nullam auctor tellus sapien, sit amet blandit ligula hendrerit et. Ut purus arcu, ultrices vitae tincidunt eget, congue volutpat mi. Sed tincidunt auctor volutpat. Curabitur iaculis egestas metus, mattis porta nisi pretium vel. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas commodo velit non ex interdum porttitor. Nullam quis risus at neque blandit auctor ut vel risus. Maecenas rutrum risus felis, et accumsan lacus sodales nec. Quisque at interdum risus, vitae ultrices ligula. Proin a orci ultrices, gravida quam vitae, egestas mi. Sed a justo sit amet felis fringilla lobortis in quis quam. Nam vel ornare diam. Praesent feugiat massa et dapibus lobortis.\r\n\r\nPellentesque in tellus ipsum. Curabitur velit nisl, placerat at mi at, dapibus accumsan risus. Nunc sed tortor scelerisque, fringilla eros sed, finibus risus. Nullam vel semper orci, vel volutpat velit. Mauris hendrerit vitae velit in scelerisque. Donec tristique pulvinar interdum. In vitae dapibus lorem. Fusce vel facilisis lectus, ut fringilla elit. Nam quis libero eget velit lacinia ultricies sed eu mi. Nullam in turpis ultrices, hendrerit purus vitae, tempor dui. Pellentesque laoreet, velit id finibus hendrerit, nisi velit iaculis turpis, ut laoreet tortor arcu a libero. Morbi at ante facilisis, semper elit vitae, ornare libero. Sed tristique tristique augue dapibus volutpat.', 'Víctor Alarcón Serrano', 'victor.alarcon.serrano@gmail.com', '01/06/15 07:42:46', 20, 1),
(7, 0, 1, 'SELECT * FROM forum_question;', 'efwef\r\n\r\nwefwef\r\n\r\n\r\nwefwef\r\n\r\n\r\nwefwef\r\n\r\n\r\nwefwef\r\n\r\n\r\nwef\r\n', '', '', '01/06/15 07:50:15', 111, 8),
(8, 0, 1, 'titol de cosa', 'pregunta de cosa', '', '', '01/06/15 09:44:38', 25, 2),
(9, 0, 1, '', '', 'test', 'test', '01/06/15 09:56:35', 1, 0),
(10, 0, 1, '', '', 'test', 'test', '01/06/15 09:56:45', 1, 0),
(11, 0, 1, '', '', 'test', 'test', '01/06/15 09:56:49', 3, 1),
(12, 0, 1, '', '', 'test', 'test', '01/06/15 09:56:52', 1, 0),
(13, 0, 1, 'Cosa', 'ewfwwefwfewf', 'test', 'test', '02/06/15 09:12:12', 28, 2),
(14, 0, 0, 'ugyuwhefuoisnsadfhuidkf ', 'Oooooooooooooooooooooohhhhhhhhh Ö', 'test', 'test', '02/06/15 08:31:17', 9, 2),
(15, 0, 0, '', '', 'test', 'test', '01/06/15 09:57:04', 4, 2),
(16, 0, 0, 'Titol', 'Perdon', 'test', 'test', '02/06/15 08:25:04', 7, 2),
(17, 0, 0, 'Titol', 'No hi ha text oish, quina llastima', 'test', 'test', '02/06/15 08:11:44', 39, 3),
(18, 0, 0, 'Es un titol', 'Es un text', 'test', 'test', '02/06/15 05:17:20', 7, 1),
(20, 0, 0, 'nom de broma', 'fffffffffaaa', 'test', 'test', '02/06/15 07:32:34', 70, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forum_answer`
--
ALTER TABLE `forum_answer`
  ADD KEY `a_id` (`a_id`);

--
-- Indexes for table `forum_question`
--
ALTER TABLE `forum_question`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forum_question`
--
ALTER TABLE `forum_question`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
