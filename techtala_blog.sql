-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2025 at 06:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `techtala_blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT curtime(),
  `users_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `username`, `comment_text`, `created_at`, `users_id`, `post_id`, `parent_id`) VALUES
(1, 'ColdGelo', 'wow', '2025-06-16 16:34:43', 13, 34, 0),
(2, 'ColdGelo', 'wow', '2025-06-16 16:35:06', 13, 34, 0),
(4, 'ColdGelo', 'wow', '2025-06-16 16:43:44', 13, 34, 0),
(5, 'ColdGelo', 'nice', '2025-06-16 16:46:36', 13, 34, 1),
(6, 'ColdGelo', 'heh', '2025-06-16 16:46:45', 13, 34, 1),
(7, 'ColdGelo', 'wth', '2025-06-16 16:47:37', 13, 34, 1),
(8, 'ColdGelo', 'what', '2025-06-16 17:45:30', 13, 34, 1),
(9, 'Gelo', 'hi', '2025-06-16 17:55:27', NULL, 34, 0),
(10, 'Gelo', 'galing', '2025-06-16 17:55:36', NULL, 34, 1),
(11, 'Gelo', 'what the helly', '2025-06-16 17:55:45', NULL, 34, 5),
(12, 'ColdGelo', 'wow', '2025-06-16 18:09:33', 13, NULL, 0),
(13, 'ColdGelo', 'what the helly', '2025-06-16 18:10:23', 13, NULL, 12),
(18, 'coldgelo', 'great', '2025-06-18 15:13:00', 13, 37, 0),
(19, 'joanaoademis', 'good', '2025-06-18 15:17:09', 15, 36, 0),
(20, 'joanaoademis', '///', '2025-06-18 15:17:48', 15, 36, 19),
(21, 'coldgelo', 'iujm', '2025-06-18 16:47:10', 13, 36, 20),
(22, 'coldgelo', 'hello', '2025-06-18 17:09:50', 13, 41, 0),
(23, 'realynmitra', 'great', '2025-06-18 18:15:24', 16, 46, 0),
(24, 'aliahlim', 'such a great drama', '2025-06-18 20:36:58', 17, 45, 0),
(25, 'realynmitra', 'nice', '2025-06-19 03:09:25', 16, 34, 10),
(26, 'joanaoademis', 'encantadiaaa', '2025-06-19 04:17:32', 15, 47, 0),
(27, 'realynmitra', 'such a great series', '2025-06-19 04:21:26', 16, 47, 26),
(32, 'dummy', 'hhh', '2025-06-21 02:46:04', 23, 53, 0),
(33, 'kurtpagatpat', 'ako ang susunod sa mga sangre!!', '2025-06-21 03:46:17', 18, 47, 0),
(34, 'joana', 'hello', '2025-06-21 05:46:46', 25, 40, 0),
(35, 'realynmitra', 'hehehe', '2025-06-21 05:47:27', 16, 40, 34);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT curtime(),
  `users_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) DEFAULT 0,
  `status` varchar(20) DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `username`, `title`, `content`, `image`, `created_at`, `users_id`, `updated_at`, `deleted`, `status`) VALUES
(34, 'ColdGelo', 'Data (Decap & Encap)', '<p>In<strong> data communication</strong> there are two concept involves in transmission namely encapsulation and decapsulation. <strong><em>Encapsulation </em></strong>adds addition information to a send packet as it travels to its destination,and <strong><em>Decapsulation </em></strong>is the reverse process of encapsulation, So a receiver node can read the original send data/information. This article will make you aware of Encapsulation &amp; Decapsulation, and their differences.</p>', '13_testing 3.png', '2025-06-16 22:17:10', 13, '2025-06-25 21:46:01', 0, 'published'),
(36, 'ColdGelo', 'Format', '<p><strong>Formatting </strong>a computer involves <u>preparing a storage device</u>, typically a <em>hard drive</em>, for use by an operating system. This process essentially erases all data on the drive and sets up a file system, allowing the computer to read and write information. It\'s a common practice for <u>troubleshooting </u>problems, giving a device a fresh start, or preparing it for a new operating system. </p>', '13_Format.jpg', '2025-06-17 00:34:18', 13, '2025-06-25 21:46:01', 0, 'published'),
(37, 'joanaoademis', 'The Legend of Shenli', '<p>Born into the<em> immortal realm, </em><strong>Shen Li</strong>, a <u>powerful general</u>, rejects a forced political marriage in favor of fighting for her freedom. Injured, she reverts to her <em>phoenix </em>form and falls into the<em> human world</em>, where she is bought by the sickly <strong>Xing Yun</strong> at a market. This fateful purchase binds their destinies, and love blossoms.<br><br>As they navigate the three realms, an ancient evil is revived, and a shocking betrayal shakes Shen Li\'s world. Determined for revenge and the truth, she defies Natural Law, with her love for Xing Yun becoming both their strength and a dangerous taboo. <u>As secrets unfold, can they defeat evil and save the realms, or will their love be their undoing?</u></p>', '15_The Legend of Shenli.jpg', '2025-06-18 21:04:16', 15, '2025-06-25 21:46:01', 0, 'published'),
(38, 'joanaoademis', 'Blossoms in Adversity', '<p>After a sudden tragedy strikes the <strong><em><u>Hua family,</u></em></strong> the men are forced into exile, leaving the women and children struggling to survive. <strong>Hua Zhi,</strong> the young lady of the family, steps up to lead them through hardship, keeping them safe from starvation and danger. Along the way, she wins the heart of the feared Commander of the Security Bureau, <strong>Gu Yan Xi,</strong> who becomes her beloved <u>\"Mr. Yan.\" </u>As Hua Zhi evolves from a sheltered girl to a strong leader, she <u>guides her family toward a brighter future.</u></p>', '15_Blossoms in Adversity.jpg', '2025-06-18 21:18:56', 15, '2025-06-25 21:46:01', 0, 'published'),
(39, 'aliahlim', 'ICT', '<p><strong>Information and Communication Technology </strong>(ICT) encompasses a wide range of technologies used to handle information and aid communication. It includes both hardware and software components, such as computers, the internet, mobile phones, and various applications that enable users to access, store, transmit, and manipulate information. <br><br><strong>Here\'s a more detailed breakdown:</strong><br><strong>Components of ICT:</strong><br><br><strong><em><u>Hardware:</u></em></strong><br>This includes physical devices like computers, servers, routers, smartphones, and networking equipment. <br><br><strong><em><u>Software:</u></em></strong><br>This refers to the programs and applications that run on the hardware, such as operating systems, web browsers, and communication applications. <br><br><strong><em><u>Networks:</u></em></strong><br>ICT relies on various networks for communication, including the internet, cellular networks, and local area networks (LANs). </p>', '17_ICT.webp', '2025-06-18 21:26:33', 17, '2025-06-25 21:46:01', 0, 'published'),
(40, 'aliahlim', 'Website Development', '<p><strong>Website development</strong> is the process of creating, building, and maintaining websites. It involves a combination of programming, design, and content creation to produce functional and visually appealing web pages. Developers use various technologies such as <em>HTML, CSS, JavaScript, and back-end languages like PHP or Python</em> to code websites, ensuring they<u> run smoothly and meet user needs</u>. Website development also includes configuring databases, setting up hosting environments, and implementing features like user authentication, payment systems, or content management tools.<br><br>This field is crucial in today’s digital world, where a strong online presence is essential for businesses, organizations, and individuals. Effective website development focuses not only on functionality but also on <strong>user experience (UX</strong>), accessibility, and performance. <em>Developers often work closely with designers</em> and content creators to deliver responsive, mobile-friendly websites that load quickly and are easy to navigate. Whether it\'s a personal blog, an e-commerce store, or a corporate site, web development plays a central role in connecting people and ideas on the internet.</p>', '17_Website Development.jpg', '2025-06-18 21:31:48', 17, '2025-06-25 21:46:01', 0, 'published'),
(41, 'aliahlim', 'Importance of Technology', '<p>Technology has witnessed <strong>impressive evolution </strong>in the past few decades, which has in turn transformed our lives and helped us evolve with it. Right from roadways, railways, and aircraft for seamless travel to making communication effortless from any part of the world, technology has contributed more than anything to help mankind live a life of luxury and convenience.<br><br>It is also because of technology that we know our world and outer space better. Every field owes its advancement to technology, and this clearly indicates the importance of technology in every aspect of our lives, including the highest paying tech jobs. In the upcoming sections, we elaborate on the importance, benefits, and impact of technology. <br><br>It is impossible to exaggerate the significance of technology in today\'s fast-paced world on all fronts. The way we work, communicate, and solve complicated problems has changed dramatically as a result, making technical proficiency and digital literacy more important than ever. Enrolling in a java full stack developer course can be a big step for people who want to succeed in the IT business.<br><br><strong>Importance and Benefits of Technology</strong><br><br>There is continuous work and progress in the area of technology as it offers significant benefits. And these benefits have a huge impact on our day-to-day lives and the operations of countless industries, such as healthcare, automobile, communication, manufacturing, and business, among others. With that said, here are ways in which technology is both important and immensely beneficial:<br><br><strong><em>1. Added Efficiency</em></strong><br>Organizations constantly struggle with the goal of maximizing their output while reducing the inputs. This is where technology is a game changer, especially automation. With automated processes, repetitive and redundant operations take minimal time or labor while ensuring expected output.<br><br><strong><em>2. Faster Decision Making</em></strong><br>With technologies such as artificial intelligence and machine learning, it has become easier than ever to handle large volumes of data and make crucial business decisions based on the insights derived from the data. In addition to this, technological resources add accuracy to the decision-making process as they reduce the scope of errors from manual operations.<br><br><strong><em>3. Cost and Time-Saving</em></strong><br>Since machines are way faster than humans, certain tasks that may require an incredible amount of manual work and attention to detail can be easily accomplished with the help of technology. Technology also ensures improved accuracy.<br><br>Further, the use of technology in certain areas can also help save significant costs. For instance, transitioning to digital communication from paper-based communication and engaging machines in tasks that might take a lot more time to complete can help save costs.<br><br><strong><em>4. Competitive Edge</em></strong><br>In today’s day and age when organizations compete neck and neck, technology can be one aspect that empowers a company to outdo its competition. Oftentimes, technology also serves as a USP or something that sets the company apart from others in the eyes of potential clients and customers. With access to advanced technology, companies have the opportunity to create better products, which can ultimately help them improve their sales. <br><br><strong><em>5. Increased Innovation</em></strong><br>Technology has proven to be the most useful resource for almost any industry to move forward and make progress. Upgrades not only help organizations step up but they also ease the operations for employees as well as people in general. This underlines the importance of technology in making innovations, which has a large-scale benefit.</p>', '17_Importance of Technology.jpg', '2025-06-18 21:35:57', 17, '2025-06-25 21:46:01', 0, 'published'),
(42, 'kurtpagatpat', 'Healthy Living', '<p>Healthy living <strong>encompasses a range of practices that contribute to physical and mental well-being, reducing the risk of disease and enhancing overall quality of life</strong>. Key elements include a balanced diet, regular physical activity, sufficient sleep, stress management, and avoiding harmful substances like tobacco and excessive alcohol. </p>', '18_Healthy Living.jpg', '2025-06-18 21:43:05', 18, '2025-06-25 21:46:01', 0, 'published'),
(43, 'joanaoademis', 'Love Like The Galaxy', '<p><strong>Ling Bu Yi,</strong> the foster son of <em>Emperor Wen</em> and a talented &amp; ruthless general of the<u> Black Armour Army, </u>was on a personal mission to uncover hidden truths about the past, which had led to the massacre of an entire city and of his clan when he met Cheng Shao Shang during a mission to apprehend a fugitive in a rural village.<br><br>The young <strong>Cheng Shao Shang</strong> had been left behind because her parents had gone off to fight in the war. <em>Neglected and uneducated</em>, she had been a thorn in her aunt\'s and grandmother\'s side until the unexpected return of her parents after many years on the frontlines.<br><br>Having always lacked a loving family, Shao Shang had been looking forward to being reunited with her parents, but the many years of separation inevitably led to some estrangement. Shao Shang, however, is unfazed and determined to live for herself, and her unruly and audacious nature, and her quick wits, begin to catch the attention of quite a few people in the capital, including Ling Bu Yi.<br></p>', '15_Love Like The Galaxy.jpg', '2025-06-18 23:51:05', 15, '2025-06-25 21:46:01', 0, 'published'),
(44, 'joanaoademis', 'Love 020', '<p><strong>Xiao Nai </strong>is a gaming expert who, courtesy of his <em>basketball skills, academic excellence, swimming talent, and game company presidency,</em> also happens to be the <em>most popular student </em>on campus. When he first comes across the gorgeous computer science major <strong>Bei Wei Wei, </strong>the infinitely talented wunderkind immediately falls in love.<br><br>But it’s not Wei Wei’s looks that he notices; it’s the ridiculous mastery with which she is commanding her guild and owning everyone in an online multiplayer game that makes her impossible to forget. Now, Xiao Nai must use his skills both in real life and online to capture the adorable but dorky Wei Wei’s heart. But does their love have the XP to succeed, or will this relationship never level up?<br></p>', '15_Love 020.webp', '2025-06-18 23:56:51', 15, '2025-06-25 21:46:01', 0, 'published'),
(45, 'joanaoademis', 'Moon Lovers', '<p>When a <strong><em><u>total eclipse of the sun</u></em></strong> takes place, <strong>Go Ha Jin</strong> is transported back in time to the start of the <em>Goryeo Dynasty of Korea</em> during King Taejo\'s rule. She wakes up in the body of the 16-year-old Hae Soo and finds herself living in the house of the 8th <strong>Prince Wang Wook</strong>, who is married to <strong>Hae Soo\'s </strong>cousin. She soon befriends several of the princes and meets the ostracized 4th Prince, Wang So. Although knowing she should not get involved in palace intrigues over the succession to the throne, she inadvertently becomes a pawn in the struggle, as several of the Princes fall in love with her.</p>', '15_Moon Lovers.webp', '2025-06-19 00:06:25', 15, '2025-06-25 21:46:01', 0, 'published'),
(46, 'kurtpagatpat', 'Lifestyle', '<p>A<strong> healthy lifestyle </strong>encompasses various habits that promote <em>physical, mental, and social well-being. </em>These include maintaining a <u>balanced diet,</u> engaging in regular physical activity, getting enough sleep, managing stress, and avoiding harmful substances like tobacco and excessive alcohol. Adopting these habits can reduce the risk of chronic diseases, improve overall quality of life, and contribute to a more fulfilling life. </p>', '18_Lifestyle.png', '2025-06-19 00:07:44', 18, '2025-06-25 21:46:01', 0, 'published'),
(47, 'joanaoademis', 'Encantadia', '<p><strong>Encantadia </strong>is a Filipino fantasy television series aired and produced by GMA Network. It was dubbed as the grandest, most ambitious, and most expensive production for Philippine television during its time of release. The pilot episode was aired on <em>May 2, 2005</em>. Its last episode was aired on December 9 of the same year to give way to its second book, Etheria. This series aired its pilot episode on December 12, and its last episode on <em>February 18, 2006</em>. The third installment of the Encantadia saga, entitled Encantadia: Pag-ibig Hanggang Wakas, aired its pilot on February 20, 2006 and the series ended on April 28, 2006. The series garnered both popular and critical recognition at home and abroad, including winning the 2005 Teleserye of the Year at the Los Angeles-based Gawad Amerika Awards. The series then had a requel (or re-telling sequel), entitled with the same name. It aired its pilot on July 18, 2016 and its final episode on<em> May 19, 2017.</em></p>', '15_Encantadia.avif', '2025-06-19 02:45:16', 15, '2025-06-25 21:46:01', 0, 'published'),
(53, 'josemarioademis', 'Welcome', '<p><em>This is about</em> <strong>Park Jimin</strong></p>', '21_Welcome.jpg', '2025-06-20 22:35:35', 21, '2025-06-25 21:46:01', 0, 'published'),
(54, 'kurtpagatpat', 'ako si tanggol, nakatira sa korea', '<p><u>tatakbo akong mayor</u> kasama ka!</p>', '18_ako si tanggol, nakatira sa korea.webp', '2025-06-21 09:44:35', 18, '2025-06-25 21:46:01', 0, 'published'),
(55, 'kurt', 'AI', '<p><strong>Artificial Intelligence (AI)</strong> is no longer a concept of the future —<strong><em><u> it’s shaping our present in ways we couldn’t have imagined just a decade ago. From smart assistants like Siri and Alexa to personalized recommendations on Netflix and Spotify, AI is deeply embedded in ou</u></em></strong>r daily routines.<br><br>In the business world, AI is revolutionizing customer service through chatbots and automating repetitive tasks, allowing humans to focus on more strategic roles. Meanwhile, in healthcare, AI-powered diagnostics are helping doctors detect diseases faster and more accurately.<br><br>But with great power comes great responsibility. Ethical concerns such as data privacy, algorithmic bias, and job displacement are becoming more prominent. It’s crucial that as technology evolves, regulations and ethical guidelines keep pace.<br><br>In the coming years, we can expect AI to become even more integrated into our homes, workplaces, and cities. The key is to ensure it\'s used responsibly — enhancing human potential without replacing it.<br></p>', '26_AI.jpg', '2025-06-21 11:51:23', 26, '2025-06-25 21:46:01', 0, 'published'),
(56, 'joanaoademis', 'dada', '<p>igouhilkn</p>', '15_dada.jpg', '2025-06-25 21:44:19', 15, '2025-06-25 22:18:48', 1, 'published'),
(57, 'joanaoademis', 'lkhjvm', '<p>hkvuglhk. iknmf plkn polknw </p>', '15_lkhjvm.png', '2025-06-25 21:46:23', 15, '2025-06-25 22:18:53', 1, 'published'),
(58, 'joanaoademis', 'jbkhvjbm ', '<p>bjgckhb</p>', '15_jbkhvjbm .png', '2025-06-25 21:47:49', 15, '2025-06-25 22:12:12', 1, 'published'),
(59, 'joanaoademis', ';ihughjvb', '<p>gfyouh</p>', '15_;ihughjvb.jpg', '2025-06-25 21:48:09', 15, '2025-06-25 22:11:59', 1, 'draft'),
(60, 'joanaoademis', 'Park Jimin', '<p><strong>Bangtan </strong>Sonyeondan <br>Bangtan <strong>Sonyeondan </strong><br><strong>Bangtan </strong>Sonyeondan <br>Bangtan <strong>Sonyeondan </strong><br><strong>Bangtan </strong>Sonyeondan </p>', '15_Park Jimin.gif', '2025-06-25 22:19:51', 15, '2025-06-25 22:26:05', 0, 'published'),
(61, 'joanaoademis', 'hhmn', '<p>ouigyjhvm</p>', '15_hhmn.jpg', '2025-06-25 23:39:39', 15, '2025-06-25 23:41:22', 1, 'draft'),
(62, 'joanaoademis', 'u8978tygjh', '<p>8yuigkj</p>', '15_u8978tygjh.jpg', '2025-06-25 23:50:33', 15, '2025-06-25 23:50:33', 0, 'published'),
(63, 'joanaoademis', 'khjb', '<p>jiyguohik</p>', '15_khjb.jpg', '2025-06-25 23:50:46', 15, '2025-06-25 23:50:46', 0, 'draft'),
(64, 'joanaoademis', 'ji', '<p>ihbn</p>', '15_ji.jpg', '2025-06-26 00:12:49', 15, '2025-06-26 00:13:41', 1, 'draft');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT curtime(),
  `role` varchar(50) DEFAULT NULL,
  `gender` enum('male','female','not-specified') DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `pass`, `email`, `created_at`, `role`, `gender`, `bio`, `profile_picture`, `deleted`, `status`) VALUES
(13, 'ColdGelo', '$2y$10$zuamKyBQYZwR0jen9q9ZKe8n1abXTw4ON0pIgy74cI2d/PLZR.DUS', 'angelocumbe345@gmail.com', '2025-06-16 09:55:35', 'author', 'male', 'Sample', '13_2025-06-18.jpg', 0, 'active'),
(15, 'joanaoademis', '$2y$10$0.MxAl90DOja91fWCCQvLurmN7sb7zSo3IG1JOli6DE9WkqRxrvMK', 'joanamarieoademis@gmail.com', '2025-06-18 20:55:41', 'author', 'female', 'Writes romantic fantasy stories that blend mythical elements with real-world emotions. When she\'s not building worlds or crafting strong female leads, she’s daydreaming by the sea or reading ancient legends. Phoenix\'s Heart is her debut novel.', '15_2025-06-18.jpg', 0, 'active'),
(16, 'realynmitra', '$2y$10$nauVXdS0wufZ8rTaacMAt.SxeBsdSHw0inf1A991YzZWyVEmdylyi', 'realyn@gmail.com', '2025-06-18 20:55:58', 'reader', 'female', NULL, '16_2025-06-18.jpg', 0, 'active'),
(17, 'aliahlim', '$2y$10$yxbpRV4dzP1N7WSuaxdMRO.cAEUjFePA2KTNG1cyCeJLTrvUirLGC', 'aliah@gmail.com', '2025-06-18 20:56:32', 'author', 'female', 'A passionate tech enthusiast and content creator behind TechTala. With a love for writing and a knack for simplifying complex topics, she shares tutorials, insights, and tips for students, developers, and curious minds.', '17_2025-06-18.jpg', 0, 'active'),
(18, 'kurtpagatpat', '$2y$10$LnGClFsxNx0YnAdp4554heOa9uariETrVr2CbsbvyB5m8l8WAkP5C', 'kurt@gmail.com', '2025-06-18 21:06:51', 'author', 'male', 'Just a guy who believes in clean eating, staying active, and living life one healthy habit at a time. 💪🥗🏃‍♂️', '18_2025-06-18.jpg', 0, 'active'),
(19, 'angelocumbe', '$2y$10$wP2Drj1If/ho7SWh4OJ7kOhQrulvwdBr/64vyv6yUnw5hdJ2HZAE.', 'cumbe@gmail.com', '2025-06-18 21:08:19', 'admin', NULL, NULL, '19_2025-06-18.jpg', 0, 'active'),
(20, 'jomaroademis', '$2y$10$F5W60A/HmXZCfbbIREZtHuXP/.sJHjpZNkX3NQ48qwwnt10rcj/by', 'jomaroademis@gmail.com', '2025-06-19 11:24:24', 'reader', 'male', NULL, '20_2025-06-19.jpg', 0, 'active'),
(21, 'josemarioademis', '$2y$10$hGzNinBm4uZi1/4rydYLc.dtcWRvtPbPv/3zNXT6UhLRTi6hGiQ8.', 'jose@gmail.com', '2025-06-19 11:28:46', 'author', 'male', 'Believe in God', '21_2025-06-19.jpeg', 1, ''),
(22, 'parkjimin', '$2y$10$42jG1hY5V4rc1N18l8X5ReLyWQx.WfUjTA.PwxiBEp8ETRjlJmvT.', 'jimin@gmail.com', '2025-06-19 11:31:13', 'admin', NULL, NULL, '22_2025-06-19.jpg', 0, 'active'),
(23, 'dummy', '$2y$10$efUog/2s4IHfEuhFbP50fe/dWWU6s0.6iai.bqFmkf92WcyuuaiEe', 'dummy@gmail.com', '2025-06-21 08:45:25', 'reader', NULL, NULL, NULL, 1, 'active'),
(24, 'gerard', '$2y$10$2lIIbu2ZI.MrFzLgb9WIYecv8tvswRKXFRbNBSEBH94ftNPqSMAO2', 'gerard@gmail.com', '2025-06-21 09:39:36', 'admin', NULL, NULL, '24_2025-06-21.webp', 0, 'active'),
(25, 'joana', '$2y$10$4Ah10WmQl4DxkhggrEci3.JfH7lDLTtLMkhYq2wTSDRlcsthaQLhe', 'oademis@gmail.com', '2025-06-21 11:45:27', 'reader', NULL, NULL, NULL, 0, 'active'),
(26, 'kurt', '$2y$10$FRwzGOIZUG1TStXYPFDIJe9bIs3fQYPyzZZE.cn3w9R4d0GF1SoES', 'k@gmail.com', '2025-06-21 11:49:36', 'author', NULL, NULL, NULL, 0, 'active'),
(27, 'cumbe', '$2y$10$uxvfNE1pj9npTvqLnhuWLeJb7zPSco./jJZyVrELYYJoWxsEpC7IW', 'c@gmail.com', '2025-06-21 11:53:48', 'admin', NULL, NULL, NULL, 0, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `fk_post_comment` (`post_id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_post_comment` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
