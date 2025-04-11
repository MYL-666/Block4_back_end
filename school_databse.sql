-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2025 at 04:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school`
--

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_book`
--

CREATE TABLE `borrowed_book` (
  `borrow_id` int(11) NOT NULL,
  `Student_id` int(11) NOT NULL,
  `Book_id` int(11) NOT NULL,
  `borrowDate` date DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `returnDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed_book`
--

INSERT INTO `borrowed_book` (`borrow_id`, `Student_id`, `Book_id`, `borrowDate`, `DueDate`, `returnDate`) VALUES
(2, 1, 5, '2025-04-07', '2025-04-21', '2025-04-07'),
(4, 1, 9, '2025-04-07', '2025-04-21', '2025-04-07'),
(6, 1, 17, '2025-04-07', '2025-04-21', '2025-04-07'),
(7, 3, 17, '2025-03-13', '2025-04-29', NULL),
(8, 4, 1, '2025-03-18', '2025-03-21', NULL),
(9, 4, 3, '2025-03-17', '2025-03-26', NULL),
(10, 5, 4, '2025-03-21', '2025-03-17', NULL),
(12, 1, 10, '2025-04-07', '2025-04-21', '2025-04-07'),
(17, 1, 17, '2025-04-07', '2025-04-21', '2025-04-07'),
(18, 1, 11, '2025-04-07', '2025-04-21', '2025-04-09'),
(19, 1, 71, '2025-04-07', '2025-04-21', NULL),
(20, 1, 75, '2025-04-07', '2025-04-21', NULL),
(22, 1, 122, '2025-04-09', '2025-04-23', NULL),
(23, 6, 5, '2025-04-09', '2025-04-23', '2025-04-17');

-- --------------------------------------------------------

--
-- Table structure for table `chat_board`
--

CREATE TABLE `chat_board` (
  `chat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('message','annoucement') DEFAULT 'message',
  `title` varchar(30) DEFAULT NULL,
  `content` text NOT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_board`
--

INSERT INTO `chat_board` (`chat_id`, `user_id`, `type`, `title`, `content`, `created_date`) VALUES
(13, 1, 'annoucement', 'School Reopens', 'School will reopen on April 1st, 2025. Please arrive by 8:00 AM.', '2025-03-29 16:02:21'),
(14, 1, 'message', ' Suggestion Box', 'Can we have more time for art classes?', '2025-03-29 16:02:21'),
(15, 1, 'annoucement', ' Exam Schedule', 'Midterm exams will be held from May 10th to May 20th.', '2025-03-29 16:02:21'),
(16, 1, 'annoucement', 'Welcome Back to School', 'We are excited to start a new term! Please check your timetable.', '2025-03-29 17:13:56'),
(17, 1, 'annoucement', 'Library Maintenance', 'The library will be closed for maintenance on April 5th.', '2025-03-29 17:13:56'),
(18, 1, 'annoucement', 'Fire Drill Reminder', 'There will be a fire drill on April 10th. Follow all safety instructions.', '2025-03-29 17:13:56'),
(19, 1, 'annoucement', 'Sports Day Registration', 'Students can now register for Sports Day via the portal.', '2025-03-29 17:13:56'),
(20, 1, 'annoucement', 'Parent-Teacher Meeting', 'PTM will be held on April 15th. Please confirm attendance.', '2025-03-29 17:13:56'),
(21, 1, 'annoucement', 'New Cafeteria Menu', 'The cafeteria menu has been updated for the new term.', '2025-03-29 17:13:56'),
(22, 1, 'annoucement', 'Exam Timetable Released', 'The exam schedule is now available under your profile section.', '2025-03-29 17:13:56'),
(23, 1, 'annoucement', 'Club Sign-Ups Open', 'Interested in joining a club? Sign-ups are open until April 12th.', '2025-03-29 17:13:56'),
(24, 1, 'annoucement', 'Field Trip Permission', 'Please submit the field trip consent form by April 8th.', '2025-03-29 17:13:56'),
(25, 1, 'annoucement', 'Happy Easter!', 'School will be closed on April 1st for Easter Holiday.', '2025-03-29 17:13:56'),
(86, 2, 'message', 'Suggestion', 'Could we extend library hours during exam week?', '2025-03-29 21:38:16'),
(87, 3, 'message', 'Lost Item', 'I lost my water bottle near the playground.', '2025-03-29 21:38:16'),
(88, 7, 'message', 'Request', 'Can we have more field trips this year?', '2025-03-29 21:38:16'),
(89, 8, 'message', 'Feedback', 'The new canteen menu is really good!', '2025-03-29 21:38:16'),
(90, 2, 'message', 'Classroom Issue', 'Projector in Room 305 is not working.', '2025-03-29 21:38:16'),
(91, 3, 'message', 'Event Idea', 'Let’s organize a cultural day next month.', '2025-03-29 21:38:16'),
(92, 9, 'message', 'Praise', 'Thank you to all teachers for helping us prepare for exams!', '2025-03-29 21:38:16'),
(93, 7, 'message', 'Wi-Fi', 'The Wi-Fi in the library is unstable.', '2025-03-29 21:38:16'),
(94, 2, 'message', 'Facilities', 'Can we get bean bags in the common area?', '2025-03-29 21:38:16'),
(95, 3, 'message', 'Shuttle Timing', 'Can the morning shuttle come 10 minutes later?', '2025-03-29 21:38:16'),
(96, 10, 'message', 'Green Campus', 'Add more plants around the school for a greener vibe.', '2025-03-29 21:38:16'),
(97, 11, 'message', 'Clubs', 'When will science club activities resume?', '2025-03-29 21:38:16'),
(98, 2, 'message', 'Complaint', 'AC in lab block is too cold.', '2025-03-29 21:38:16'),
(99, 3, 'message', 'Query', 'What are the criteria for sports team selection?', '2025-03-29 21:38:16'),
(100, 8, 'message', 'Thank You', 'Appreciate the new book additions in the library!', '2025-03-29 21:38:16'),
(101, 7, 'message', 'Homework', 'Could teachers upload homework earlier?', '2025-03-29 21:38:16'),
(102, 2, 'message', 'Suggestion Box', 'A suggestion box should be placed in each block.', '2025-03-29 21:38:16'),
(103, 3, 'message', 'Water Dispenser', 'The one in Block C is leaking.', '2025-03-29 21:38:16'),
(104, 11, 'message', 'Recycle', 'Can we start a recycling club?', '2025-03-29 21:38:16'),
(105, 10, 'message', 'Exam', 'Will the exam results be released this week?', '2025-03-29 21:38:16'),
(106, 2, 'message', 'Exam Stress', 'I’m feeling overwhelmed with all the upcoming tests. Could we have a review session or maybe some extra help classes?', '2025-03-29 22:54:38'),
(107, 3, 'message', 'Lost Earbuds', 'I lost my wireless earbuds in the gym after PE class yesterday. They’re black with a silver case. Please let me know if you find them!', '2025-03-29 22:54:38'),
(108, 2, 'message', 'Cafeteria Feedback', 'The spaghetti on Wednesday was really good! Can we have more days with that option?', '2025-03-29 22:54:38'),
(109, 8, 'message', 'Wi-Fi Issues', 'Wi-Fi in Block B is still very unstable. It’s affecting our ability to access online materials during class.', '2025-03-29 22:54:38'),
(110, 9, 'message', 'Group Study Room Booking', 'Can we have an online system to reserve study rooms in the library? Sometimes they’re all full and it’s hard to plan group work.', '2025-03-29 22:54:38'),
(112, 1, 'message', '你哥哥的网站屌不屌啊', '喜欢查看你哥哥的超级无敌晴天霹雳天下无双小母牛坐飞机坐火箭带闪电的网站吗', '2025-03-31 17:06:20'),
(114, 3, 'message', 'gege haolihaio', '66666666666666666666666666666666666666666666666666666666666666666666666666666666666666666666666666666666666666666', '2025-03-31 20:40:22'),
(115, 7, 'message', '八嘎', '瓦达西瓦摩西摩西', '2025-04-03 20:07:17'),
(116, 2, 'message', NULL, 'Baska Emma!@!!!', '2025-04-07 00:34:12'),
(117, 2, 'message', 'Vittu!!!!!', 'BASKA EMMA ', '2025-04-07 00:36:20'),
(118, 2, 'message', 'Bruh', '哈？', '2025-04-07 00:39:16'),
(119, 2, 'message', '还有一周提交DDL！！！！！', '报告分文未动，吃着火锅唱着锅，然后调到水里头', '2025-04-07 00:41:49'),
(121, 2, 'message', 'haiyou 4 tian', 'npooooooooooooooooooooo', '2025-04-07 23:33:38'),
(127, 3, 'message', 'Last 3 Day', 'GOGOGO!', '2025-04-09 02:52:57');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(30) NOT NULL,
  `capacity` int(30) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `Grade` enum('Reception Year','Year One','Year Two','Year Three','Year Four','Year Five','Year Six') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `capacity`, `teacher_id`, `Grade`) VALUES
(1, 'class A', 20, 1, 'Year One'),
(2, 'Class B', 22, 6, 'Year One'),
(3, 'Class C', 33, 3, 'Year Two'),
(4, 'Class D', 33, 5, 'Year Three'),
(5, 'Class E', 44, 13, 'Year Four'),
(6, 'Class F', 20, 10, 'Year Five'),
(7, 'Class G', 50, 11, 'Year Six');

-- --------------------------------------------------------

--
-- Table structure for table `library`
--

CREATE TABLE `library` (
  `Book_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `Author` varchar(50) DEFAULT NULL,
  `publishDate` date DEFAULT NULL,
  `status` enum('Borrowed','Available') NOT NULL DEFAULT 'Available',
  `cover` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `library`
--

INSERT INTO `library` (`Book_id`, `title`, `Author`, `publishDate`, `status`, `cover`) VALUES
(1, 'The Midnight Library', 'Matt Haig', '2020-08-13', 'Borrowed', 'http://books.google.com/books/content?id=OwA7BAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(3, 'Journey to the Future', 'Michael Brown', '2021-12-10', 'Borrowed', 'http://books.google.com/books/content?id=SUCdwEPiMC8C&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(4, 'History of Ancient Civilizations', 'Sarah Davis', '2019-03-18', 'Borrowed', 'http://books.google.com/books/content?id=6NMrAQAAIAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(5, 'The Art of Programming', 'David Wilson', '2022-07-01', 'Available', 'http://books.google.com/books/content?id=jHg9SfB68hgC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(7, 'Mastering JavaScript', 'James Anderson', '2020-10-05', 'Available', 'http://books.google.com/books/content?id=DprLDwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(9, 'Gardening 101', 'Susan Thomas', '2018-06-12', 'Available', 'http://books.google.com/books/content?id=Tjj4ak2QOd0C&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(10, 'World Atlas', 'Steven Jackson', '2023-01-20', 'Available', 'http://books.google.com/books/content?id=GK7azQEACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(11, 'A Maze of Stars and Spring Water', 'Bing Xin', '1924-04-11', 'Available', 'http://books.google.com/books/content?id=U3R-AgAAQBAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(14, 'Science Experiments', 'Laura Thompson', '2021-09-30', 'Available', 'http://books.google.com/books/content?id=vDMjWXAk-o0C&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(17, 'Travel Around the World', 'Thomas Robinson', '2017-07-19', 'Available', 'http://books.google.com/books/content?id=zjLNDwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(20, 'Photography Basics', 'Christopher Lewis', '2018-10-25', 'Available', 'http://books.google.com/books/content?id=NO0GP-2XQQYC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(71, 'Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', '2013-02-01', 'Borrowed', 'http://books.google.com/books/content?id=lTrDMgEACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(72, 'Harry Potter and the Chamber of Secrets', 'J.K. Rowling', '1920-04-08', 'Available', 'http://books.google.com/books/content?id=BQVbEAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(73, 'Harry Potter and the Prisoner of Azkaban', 'J.K. Rowling', '1885-12-04', 'Available', 'http://books.google.com/books/content?id=y6DeDQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(74, 'Harry Potter and the Goblet of Fire', 'J.K. Rowling', '1989-02-19', 'Available', 'http://books.google.com/books/content?id=etukl7GfrxQC&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(75, 'Harry Potter and the Order of the Phoenix', 'J.K. Rowling', '1958-01-01', 'Borrowed', 'http://books.google.com/books/content?id=p6YyEAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(76, 'Harry Potter and the Half-Blood Prince', 'J.K. Rowling', '1873-04-08', 'Available', 'http://books.google.com/books/content?id=bZnHBAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(77, 'Harry Potter and the Deathly Hallows', 'J.K. Rowling', '1979-10-01', 'Available', 'http://books.google.com/books/content?id=2ctjDwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(78, 'Pride and Prejudice', 'Jane Austen', '1993-04-23', 'Available', 'http://books.google.com/books/content?id=ydULEQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(79, 'Sense and Sensibility', 'Jane Austen', '2016-12-18', 'Available', 'http://books.google.com/books/content?id=bcUNAAAAQAAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(80, 'Wuthering Heights', 'Emily Brontë', '1957-04-15', 'Available', 'http://books.google.com/books/content?id=7wXy0iWQhmUC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(81, 'The Alchemist', 'Paulo Coelho', '2000-05-26', 'Available', 'http://books.google.com/books/content?id=yYfqzgEACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(82, 'The Great Gatsby', 'F. Scott Fitzgerald', '1851-03-23', 'Available', 'http://books.google.com/books/content?id=0yfauGsKOjAC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(83, 'To Kill a Mockingbird', 'Harper Lee', '1958-06-09', 'Available', 'http://books.google.com/books/content?id=qiCrCQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(84, '1984', 'George Orwell', '1889-04-25', 'Available', 'http://books.google.com/books/content?id=kotPYEqx7kMC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(85, 'Animal Farm', 'George Orwell', '1936-02-03', 'Available', 'http://books.google.com/books/content?id=cyfRDwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(86, 'Brave New World', 'Aldous Huxley', '1947-02-12', 'Available', 'http://books.google.com/books/content?id=3zl4oJMUskoC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(87, 'The Catcher in the Rye', 'J.D. Salinger', '1938-10-09', 'Available', 'http://books.google.com/books/content?id=M3dJfixJ6lIC&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(88, 'The Hobbit', 'J.R.R. Tolkien', '1861-12-15', 'Available', 'http://books.google.com/books/content?id=U799AY3yfqcC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(89, 'The Lord of the Rings: The Fellowship of the Ring', 'J.R.R. Tolkien', '1987-02-13', 'Available', 'http://books.google.com/books/content?id=ILsWC2CLZLwC&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(90, 'The Lord of the Rings: The Two Towers', 'J.R.R. Tolkien', '1870-09-10', 'Available', 'http://books.google.com/books/content?id=12e8PJ2T7sQC&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(91, 'The Lord of the Rings: The Return of the King', 'J.R.R. Tolkien', '2010-10-28', 'Available', 'http://books.google.com/books/content?id=WZ0f_yUgc0UC&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(92, 'Moby Dick', 'Herman Melville', '1942-10-07', 'Available', 'http://books.google.com/books/content?id=XV8XAAAAYAAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(93, 'War and Peace', 'Leo Tolstoy', '1867-01-22', 'Available', 'http://books.google.com/books/content?id=s-OQ2yHDIMQC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(94, 'Anna Karenina', 'Leo Tolstoy', '1908-05-03', 'Available', 'http://books.google.com/books/content?id=1DooDwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(95, 'Crime and Punishment', 'Fyodor Dostoevsky', '1909-02-13', 'Available', 'http://books.google.com/books/content?id=XKyOEAAAQBAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(96, 'The Alchemist', 'Fyodor Dostoevsky', '1921-08-21', 'Available', 'http://books.google.com/books/content?id=oFnFFiTVST4C&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(97, 'Great Expectations', 'Charles Dickens', '1943-03-12', 'Available', 'http://books.google.com/books/content?id=fhUXAAAAYAAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(98, 'A Tale of Two Cities', 'Charles Dickens', '1940-04-22', 'Available', 'http://books.google.com/books/content?id=5EIPAAAAQAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(99, 'David Copperfield', 'Charles Dickens', '1918-12-22', 'Available', 'http://books.google.com/books/content?id=GkCpLIk7aisC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(100, 'Frankenstein', 'Mary Shelley', '2015-02-20', 'Available', 'http://books.google.com/books/content?id=UL6Cwv2Z1iQC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(101, 'Dracula', 'Bram Stoker', '2012-03-18', 'Available', 'http://books.google.com/books/content?id=8U49ADLcL0EC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(102, 'The Picture of Dorian Gray', 'Oscar Wilde', '1912-03-15', 'Available', 'http://books.google.com/books/content?id=iOheSL_H3hEC&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(103, 'The Adventures of Huckleberry Finn', 'Mark Twain', '1947-05-21', 'Available', 'http://books.google.com/books/content?id=s9q60rLUbRcC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(104, 'The Adventures of Tom Sawyer', 'Mark Twain', '1992-04-22', 'Available', 'http://books.google.com/books/content?id=8AXw9GY3DJgC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(105, 'Hamlet', 'William Shakespeare', '1933-01-08', 'Available', 'http://books.google.com/books/content?id=RGLhuHLF7QMC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(106, 'Macbeth', 'William Shakespeare', '1858-06-13', 'Available', 'http://books.google.com/books/content?id=MjQ7AQAAMAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(107, 'Othello', 'William Shakespeare', '1918-02-07', 'Available', 'http://books.google.com/books/content?id=Fb1aDwAAQBAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(108, 'Romeo and Juliet', 'William Shakespeare', '1995-12-11', 'Available', 'http://books.google.com/books/content?id=AvYUAAAAQAAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(109, 'King Lear', 'William Shakespeare', '1904-11-16', 'Available', 'http://books.google.com/books/content?id=9AzK2-3Qhc8C&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(110, 'The Tempest', 'William Shakespeare', '1951-11-15', 'Available', 'http://books.google.com/books/content?id=0cE5AQAAMAAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(111, 'Don Quixote', 'Miguel de Cervantes', '1886-05-05', 'Available', 'http://books.google.com/books/content?id=SGAwEAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(112, 'The Divine Comedy', 'Dante Alighieri', '1913-12-18', 'Available', 'http://books.google.com/books/content?id=wmacUwleJv8C&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(113, 'Les Misérables', 'Victor Hugo', '1987-05-24', 'Available', 'http://books.google.com/books/content?id=cR_uAAAAMAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(114, 'The Hunchback of Notre-Dame', 'Victor Hugo', '1999-07-19', 'Available', 'http://books.google.com/books/content?id=zCZqAwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(115, 'The Count of Monte Cristo', 'Alexandre Dumas', '1952-06-08', 'Available', 'http://books.google.com/books/content?id=JZGvEAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(116, 'The Three Musketeers', 'Alexandre Dumas', '1885-09-16', 'Available', 'http://books.google.com/books/content?id=7KoHDheyeLwC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(117, 'Ulysses', 'James Joyce', '1873-01-28', 'Available', 'http://books.google.com/books/content?id=WVofz29Hx9UC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(118, 'The Old Man and the Sea', 'Ernest Hemingway', '1878-03-21', 'Available', 'http://books.google.com/books/content?id=CqYIf-Fs5lkC&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(119, 'A Farewell to Arms', 'Ernest Hemingway', '1890-11-14', 'Available', 'http://books.google.com/books/content?id=vy_mEAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(120, 'The Alchemist', 'Paulo Coelho', '2002-02-13', 'Available', 'http://books.google.com/books/content?id=H4tI9nzXkQ0C&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(121, 'The Midnight Library', 'Matt Haig', '2020-08-13', 'Available', 'http://books.google.com/books/content?id=bvB1-MmhEjQC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(122, 'The Three-Body Problem', 'Cixin Liu', '2014-11-11', 'Borrowed', 'http://books.google.com/books/content?id=Z7GfEAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(123, 'Ball Lightning', 'Cixin Liu', '2018-08-14', 'Available', 'http://books.google.com/books/content?id=rohhDwAAQBAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(124, 'To Hold Up the Sky', 'Cixin Liu', '2020-06-08', 'Available', 'http://books.google.com/books/content?id=TVTEDwAAQBAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `parents_id` int(11) NOT NULL,
  `Last_Name` varchar(20) NOT NULL,
  `First_Name` varchar(20) NOT NULL,
  `parents_phone` varchar(20) NOT NULL,
  `Job` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`parents_id`, `Last_Name`, `First_Name`, `parents_phone`, `Job`) VALUES
(1, 'John', 'Smith', '+4477827374343', 'police'),
(2, 'Ace', 'Alice', '+447782737465', 'Docotor'),
(3, 'Wax', 'Wish', '+447285901422', 'firefighter'),
(4, 'Axam', 'Alison', '+447624827362', 'homeless'),
(5, 'baska', 'Emma', '+447283746251', 'Engineer'),
(6, 'BA', 'JI', '+447782737434', 'web designer'),
(7, 'Oscar', 'Aly', '+447826274822', 'professor'),
(8, 'Oliver', 'Ommar', '+447283748190', 'pilot'),
(10, 'Lumi', 'Emma', '+447782737432', 'seller'),
(11, 'Brown', 'Linda', '+447786523000', 'teacher'),
(12, 'Green', 'Daniel', '+447786523001', 'lawyer'),
(13, 'Black', 'Sophia', '+447786523002', 'accountant'),
(14, 'Lee', 'Michael', '+447786523003', 'chef'),
(15, 'Clark', 'Olivia', '+447786523004', 'nurse'),
(16, 'Turner', 'Ethan', '+447786523005', 'artist'),
(17, 'Walker', 'Emma', '+447786523006', 'writer'),
(18, 'Young', 'Liam', '+447786523007', 'scientist'),
(19, 'Hill', 'Ava', '+447786523008', 'developer'),
(20, 'Scott', 'Noah', '+447786523009', 'mechanic'),
(21, 'Morgan', 'Jack', '+447786523010', 'architect'),
(22, 'Bennett', 'Mia', '+447786523011', 'designer'),
(23, 'Knight', 'Lucas', '+447786523012', 'doctor'),
(24, 'Gray', 'Amelia', '+447786523013', 'journalist'),
(25, 'Reed', 'James', '+447786523014', 'barista'),
(26, 'Foster', 'Chloe', '+447786523015', 'photographer'),
(27, 'Harper', 'Henry', '+447786523016', 'driver'),
(28, 'Adams', 'Ella', '+447786523017', 'dentist'),
(29, 'Ross', 'Benjamin', '+447786523018', 'engineer'),
(30, 'Campbell', 'Zoe', '+447786523019', 'receptionist');

-- --------------------------------------------------------

--
-- Table structure for table `salaries`
--

CREATE TABLE `salaries` (
  `salaries_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `expected_amount` decimal(10,2) NOT NULL,
  `penalty_amount` decimal(10,2) DEFAULT 0.00,
  `actual_amount` decimal(10,2) NOT NULL,
  `if_paid` tinyint(1) NOT NULL,
  `salary_month` date NOT NULL DEFAULT '2025-03-01'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salaries`
--

INSERT INTO `salaries` (`salaries_id`, `teacher_id`, `expected_amount`, `penalty_amount`, `actual_amount`, `if_paid`, `salary_month`) VALUES
(1, 1, 9000.00, 6.00, 8994.00, 1, '2025-03-01'),
(4, 1, 9181.00, 34.00, 9147.00, 1, '2025-01-01'),
(5, 1, 8999.00, 260.00, 8739.00, 1, '2025-02-01'),
(7, 1, 8975.00, 170.00, 8805.00, 1, '2025-04-01'),
(8, 1, 9163.00, 70.00, 9093.00, 1, '2025-05-01'),
(9, 1, 8978.00, 291.00, 8687.00, 1, '2025-06-01'),
(10, 1, 9674.00, 264.00, 9410.00, 1, '2025-07-01'),
(11, 1, 9344.00, 77.00, 9267.00, 1, '2025-08-01'),
(12, 1, 9304.00, 230.00, 9074.00, 1, '2025-09-01'),
(13, 1, 9659.00, 71.00, 9588.00, 1, '2025-10-01'),
(14, 1, 9320.00, 279.00, 9041.00, 1, '2025-11-01'),
(15, 1, 22222.00, 252.00, 21970.00, 1, '2025-12-01'),
(16, 2, 9168.00, 197.00, 8971.00, 1, '2025-01-01'),
(17, 2, 8952.00, 109.00, 8843.00, 1, '2025-02-01'),
(18, 2, 9043.00, 250.00, 8793.00, 1, '2025-03-01'),
(19, 2, 9070.00, 84.00, 8986.00, 1, '2025-04-01'),
(20, 2, 8842.00, 228.00, 8614.00, 1, '2025-05-01'),
(21, 2, 8930.00, 289.00, 8641.00, 1, '2025-06-01'),
(22, 2, 9385.00, 16.00, 9369.00, 1, '2025-07-01'),
(23, 2, 9313.00, 165.00, 9148.00, 1, '2025-08-01'),
(24, 2, 9576.00, 106.00, 9470.00, 1, '2025-09-01'),
(25, 2, 9502.00, 140.00, 9362.00, 1, '2025-10-01'),
(26, 2, 9694.00, 120.00, 9574.00, 1, '2025-11-01'),
(27, 2, 9382.00, 202.00, 9180.00, 1, '2025-12-01'),
(28, 3, 8931.00, 69.00, 8862.00, 1, '2025-01-01'),
(29, 3, 9110.00, 4.00, 9106.00, 1, '2025-02-01'),
(30, 3, 9078.00, 285.00, 8793.00, 1, '2025-03-01'),
(31, 3, 8878.00, 6.00, 8872.00, 1, '2025-04-01'),
(32, 3, 8947.00, 177.00, 8770.00, 1, '2025-05-01'),
(33, 3, 9126.00, 133.00, 8993.00, 1, '2025-06-01'),
(34, 3, 9371.00, 212.00, 9159.00, 1, '2025-07-01'),
(35, 3, 9530.00, 87.00, 9443.00, 1, '2025-08-01'),
(36, 3, 9301.00, 192.00, 9109.00, 1, '2025-09-01'),
(37, 3, 9475.00, 58.00, 9417.00, 1, '2025-10-01'),
(38, 3, 9352.00, 38.00, 9314.00, 1, '2025-11-01'),
(39, 3, 9506.00, 85.00, 9421.00, 1, '2025-12-01'),
(52, 4, 9080.00, 87.00, 8993.00, 1, '2025-01-01'),
(53, 4, 9165.00, 153.00, 9012.00, 1, '2025-02-01'),
(54, 4, 8906.00, 245.00, 8661.00, 1, '2025-03-01'),
(55, 4, 8945.00, 58.00, 8887.00, 1, '2025-04-01'),
(56, 4, 9130.00, 50.00, 9080.00, 1, '2025-05-01'),
(57, 4, 9079.00, 180.00, 8899.00, 1, '2025-06-01'),
(58, 4, 9311.00, 81.00, 9230.00, 1, '2025-07-01'),
(59, 4, 9379.00, 134.00, 9245.00, 1, '2025-08-01'),
(60, 4, 9555.00, 203.00, 9352.00, 1, '2025-09-01'),
(61, 4, 9673.00, 178.00, 9495.00, 1, '2025-10-01'),
(62, 4, 9380.00, 31.00, 9349.00, 1, '2025-11-01'),
(63, 4, 9380.00, 103.00, 9277.00, 1, '2025-12-01'),
(64, 13, 9028.00, 120.00, 8908.00, 1, '2025-01-01'),
(65, 13, 8901.00, 117.00, 8784.00, 1, '2025-02-01'),
(66, 13, 9168.00, 239.00, 8929.00, 1, '2025-03-01'),
(67, 13, 9187.00, 41.00, 9146.00, 1, '2025-04-01'),
(68, 13, 8842.00, 49.00, 8793.00, 1, '2025-05-01'),
(69, 13, 9076.00, 290.00, 8786.00, 1, '2025-06-01'),
(70, 13, 9583.00, 193.00, 9390.00, 1, '2025-07-01'),
(71, 13, 9538.00, 140.00, 9398.00, 1, '2025-08-01'),
(72, 13, 9488.00, 23.00, 9465.00, 1, '2025-09-01'),
(73, 13, 9559.00, 198.00, 9361.00, 1, '2025-10-01'),
(74, 13, 9591.00, 1.00, 9590.00, 1, '2025-11-01'),
(75, 13, 9625.00, 95.00, 9530.00, 1, '2025-12-01'),
(76, 5, 8988.00, 38.00, 8950.00, 1, '2025-01-01'),
(77, 5, 8801.00, 111.00, 8690.00, 1, '2025-02-01'),
(78, 5, 9039.00, 233.00, 8806.00, 1, '2025-03-01'),
(79, 5, 9034.00, 240.00, 8794.00, 1, '2025-04-01'),
(80, 5, 8962.00, 79.00, 8883.00, 1, '2025-05-01'),
(81, 5, 8989.00, 101.00, 8888.00, 1, '2025-06-01'),
(82, 5, 9538.00, 41.00, 9497.00, 1, '2025-07-01'),
(83, 5, 9409.00, 160.00, 9249.00, 1, '2025-08-01'),
(84, 5, 9664.00, 259.00, 9405.00, 1, '2025-09-01'),
(85, 5, 9307.00, 50.00, 9257.00, 1, '2025-10-01'),
(86, 5, 9347.00, 245.00, 9102.00, 1, '2025-11-01'),
(87, 5, 9565.00, 102.00, 9463.00, 1, '2025-12-01'),
(88, 6, 9030.00, 261.00, 8769.00, 1, '2025-01-01'),
(89, 6, 9012.00, 273.00, 8739.00, 1, '2025-02-01'),
(90, 6, 9053.00, 140.00, 8913.00, 1, '2025-03-01'),
(91, 6, 9075.00, 6.00, 9069.00, 1, '2025-04-01'),
(92, 6, 9057.00, 240.00, 8817.00, 1, '2025-05-01'),
(93, 6, 9081.00, 47.00, 9034.00, 1, '2025-06-01'),
(94, 6, 9501.00, 236.00, 9265.00, 1, '2025-07-01'),
(95, 6, 9592.00, 240.00, 9352.00, 1, '2025-08-01'),
(96, 6, 9650.00, 168.00, 9482.00, 1, '2025-09-01'),
(97, 6, 9509.00, 176.00, 9333.00, 1, '2025-10-01'),
(98, 6, 9312.00, 260.00, 9052.00, 1, '2025-11-01'),
(99, 6, 9302.00, 49.00, 9253.00, 1, '2025-12-01'),
(112, 8, 8944.00, 243.00, 8701.00, 1, '2025-01-01'),
(113, 8, 9052.00, 208.00, 8844.00, 1, '2025-02-01'),
(114, 8, 9156.00, 153.00, 9003.00, 1, '2025-03-01'),
(115, 8, 9040.00, 105.00, 8935.00, 1, '2025-04-01'),
(116, 8, 8872.00, 143.00, 8729.00, 1, '2025-05-01'),
(117, 8, 9083.00, 39.00, 9044.00, 1, '2025-06-01'),
(118, 8, 9649.00, 148.00, 9501.00, 1, '2025-07-01'),
(119, 8, 9517.00, 156.00, 9361.00, 1, '2025-08-01'),
(120, 8, 9446.00, 44.00, 9402.00, 1, '2025-09-01'),
(121, 8, 9678.00, 233.00, 9445.00, 1, '2025-10-01'),
(122, 8, 9608.00, 250.00, 9358.00, 1, '2025-11-01'),
(123, 8, 9497.00, 31.00, 9466.00, 1, '2025-12-01'),
(124, 9, 8988.00, 128.00, 8860.00, 1, '2025-01-01'),
(125, 9, 8940.00, 118.00, 8822.00, 1, '2025-02-01'),
(126, 9, 9198.00, 171.00, 9027.00, 1, '2025-03-01'),
(127, 9, 8961.00, 132.00, 8829.00, 1, '2025-04-01'),
(128, 9, 9172.00, 131.00, 9041.00, 1, '2025-05-01'),
(129, 9, 8993.00, 282.00, 8711.00, 1, '2025-06-01'),
(130, 9, 9384.00, 76.00, 9308.00, 1, '2025-07-01'),
(131, 9, 9691.00, 156.00, 9535.00, 1, '2025-08-01'),
(132, 9, 9603.00, 144.00, 9459.00, 1, '2025-09-01'),
(133, 9, 9545.00, 245.00, 9300.00, 1, '2025-10-01'),
(134, 9, 9572.00, 22.00, 9550.00, 1, '2025-11-01'),
(135, 9, 9301.00, 287.00, 9014.00, 1, '2025-12-01'),
(136, 10, 8835.00, 52.00, 8783.00, 1, '2025-01-01'),
(137, 10, 8961.00, 258.00, 8703.00, 1, '2025-02-01'),
(138, 10, 9022.00, 87.00, 8935.00, 1, '2025-03-01'),
(139, 10, 8886.00, 220.00, 8666.00, 1, '2025-04-01'),
(140, 10, 9178.00, 8.00, 9170.00, 1, '2025-05-01'),
(141, 10, 8940.00, 6.00, 8934.00, 1, '2025-06-01'),
(142, 10, 9563.00, 296.00, 9267.00, 1, '2025-07-01'),
(143, 10, 9628.00, 16.00, 9612.00, 1, '2025-08-01'),
(144, 10, 9311.00, 109.00, 9202.00, 1, '2025-09-01'),
(145, 10, 9434.00, 118.00, 9316.00, 1, '2025-10-01'),
(146, 10, 9361.00, 47.00, 9314.00, 1, '2025-11-01'),
(147, 10, 9597.00, 256.00, 9341.00, 1, '2025-12-01'),
(148, 11, 9061.00, 276.00, 8785.00, 1, '2025-01-01'),
(149, 11, 8876.00, 98.00, 8778.00, 1, '2025-02-01'),
(150, 11, 8887.00, 144.00, 8743.00, 1, '2025-03-01'),
(151, 11, 9182.00, 63.00, 9119.00, 1, '2025-04-01'),
(152, 11, 9196.00, 248.00, 8948.00, 1, '2025-05-01'),
(153, 11, 8949.00, 169.00, 8780.00, 1, '2025-06-01'),
(154, 11, 9317.00, 67.00, 9250.00, 1, '2025-07-01'),
(155, 11, 9455.00, 84.00, 9371.00, 1, '2025-08-01'),
(156, 11, 9347.00, 137.00, 9210.00, 1, '2025-09-01'),
(157, 11, 9417.00, 244.00, 9173.00, 1, '2025-10-01'),
(158, 11, 9388.00, 120.00, 9268.00, 1, '2025-11-01'),
(159, 11, 9538.00, 28.00, 9510.00, 1, '2025-12-01'),
(268, 14, 9039.00, 93.00, 8946.00, 1, '2025-01-01'),
(269, 14, 9014.00, 193.00, 8821.00, 1, '2025-02-01'),
(270, 14, 9081.00, 74.00, 9007.00, 1, '2025-03-01'),
(271, 14, 9087.00, 233.00, 8854.00, 1, '2025-04-01'),
(272, 14, 9030.00, 12.00, 9018.00, 1, '2025-05-01'),
(273, 14, 9061.00, 153.00, 8908.00, 1, '2025-06-01'),
(274, 14, 9679.00, 116.00, 9563.00, 1, '2025-07-01'),
(275, 14, 9546.00, 110.00, 9436.00, 1, '2025-08-01'),
(276, 14, 9382.00, 70.00, 9312.00, 1, '2025-09-01'),
(277, 14, 9456.00, 152.00, 9304.00, 1, '2025-10-01'),
(278, 14, 9482.00, 74.00, 9408.00, 1, '2025-11-01'),
(279, 14, 9670.00, 64.00, 9606.00, 1, '2025-12-01');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL COMMENT 'students''ID',
  `Last_Name` varchar(20) NOT NULL COMMENT 'students'' name',
  `First_Name` varchar(20) NOT NULL,
  `address` varchar(500) NOT NULL COMMENT 'students''address',
  `medical_information` varchar(500) DEFAULT NULL COMMENT 'students''medical information',
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `Last_Name`, `First_Name`, `address`, `medical_information`, `class_id`) VALUES
(1, 'Smith', 'John', 'Manchester 1111', 'nope', 1),
(2, 'Johnson', 'Emma', '456 Oak St', 'Allergic to peanuts', 5),
(3, 'Williams', 'Oliver', '789 Pine St', 'Asthma', 1),
(4, 'Brown', 'Sophia', '321 Maple St', 'None', 3),
(5, 'Jones', 'Liam', '654 Cedar St', 'None', 2),
(6, 'Garcia', 'Ava', '987 Birch St', 'Peanut allergy', 1),
(7, 'Miller', 'Mason', '111 Elm St', 'None', 3),
(8, 'Davis', 'Isabella', '222 Walnut St', 'Diabetic', 2),
(9, 'Rodriguez', 'Lucas', '333 Poplar St', 'None', 1),
(10, 'Martinez', 'Mia', '444 Spruce St', 'None', 2),
(11, 'Hernandez', 'Ethan', '555 Chestnut St', 'Heart condition', 3),
(12, 'Lopez', 'Amelia', '666 Willow St', 'Asthma', 1),
(13, 'Gonzalez', 'Logan', '777 Ash St', 'None', 2),
(14, 'Wilson', 'Charlotte', '888 Sycamore St', 'None', 3),
(15, 'Anderson', 'Aiden', '999 Fir St', 'None', 1),
(16, 'Thomas', 'Harper', '123 Aspen St', 'Peanut allergy', 7),
(17, 'Taylor', 'Elijah', '456 Beech St', 'None', 3),
(18, 'Moore', 'Evelyn', '789 Hickory St', 'None', 7),
(19, 'Jackson', 'James', '101 Dogwood St', 'Allergic to dairy', 3),
(20, 'Martin', 'Abigail', '202 Magnolia St', 'None', 6),
(22, 'Allen', 'Joseph', '4125 Cedar Ln, Chicago, IL 73483', NULL, 3),
(23, 'Young', 'Harper', '661 Main St, San Diego, IL 46533', 'No known medical conditions', 4),
(24, 'Robinson', 'Paul', '361 Cedar Ln, San Antonio, FL 99741', 'Asthma', 3),
(25, 'King', 'Emma', '6503 Oak Ave, Chicago, NY 34418', 'Diabetes Type 1', 2),
(26, 'Garcia', 'Mia', '2767 Washington Ave, Phoenix, FL 86123', 'No known medical conditions', 4),
(27, 'Lewis', 'Charlotte', '7666 Elm Dr, Los Angeles, PA 42002', 'Asthma', 6),
(28, 'Martin', 'George', '6960 Washington Ave, San Antonio, TX 95659', 'Allergies', 1),
(29, 'Martinez', 'Jennifer', '5301 Park Rd, San Diego, OH 80775', 'No known medical conditions', 3),
(30, 'Green', 'Daniel', '8898 Elm Dr, San Diego, CA 63754', 'No known medical conditions', 4),
(31, 'Miller', 'Charles', '1960 Main St, San Diego, AZ 41016', NULL, 6),
(32, 'Robinson', 'Emma', '3464 Park Rd, Los Angeles, CA 44064', 'No known medical conditions', 3),
(33, 'Martinez', 'Sophia', '6754 Maple Rd, Los Angeles, TX 44274', 'No known medical conditions', 3),
(34, 'Martin', 'Brian', '9792 Pine St, Phoenix, IL 83577', 'Allergies', 3),
(35, 'Miller', 'Richard', '2853 Cedar Ln, New York, NY 54679', 'Asthma', 1),
(36, 'Rodriguez', 'Emma', '1880 Cedar Ln, San Antonio, NY 32581', 'Asthma', 6),
(37, 'Brown', 'Elizabeth', '7587 Elm Dr, Chicago, IL 89855', 'None', 5),
(38, 'Robinson', 'Mia', '8227 Washington Ave, Los Angeles, OH 52719', 'None', 4),
(39, 'Martin', 'Jessica', '9142 Elm Dr, Philadelphia, AZ 35064', 'Allergies', 7),
(40, 'Martin', 'Edward', '294 Main St, Chicago, FL 15647', 'No known medical conditions', 4),
(41, 'Thomas', 'Richard', '2170 Elm Dr, Houston, TX 96319', 'Diabetes Type 1', 5),
(42, 'Williams', 'Patricia', '806 Maple Rd, Phoenix, IL 99528', 'Diabetes Type 1', 4),
(43, 'Green', 'Charles', '7981 Oak Ave, Houston, TX 37448', NULL, 5),
(44, 'Jackson', 'Robert', '5933 Washington Ave, Houston, CA 67523', 'Asthma', 4),
(45, 'Thomas', 'Steven', '1686 Washington Ave, San Antonio, TX 32979', NULL, 7),
(46, 'Rodriguez', 'Jennifer', '7571 Cedar Ln, Philadelphia, CA 95382', NULL, 1),
(47, 'King', 'Elizabeth', '9382 Washington Ave, Philadelphia, AZ 75336', NULL, 2),
(48, 'Rodriguez', 'Jessica', '9882 Main St, Houston, OH 88951', 'Diabetes Type 1', 7),
(49, 'Robinson', 'John', '2557 Maple Rd, Philadelphia, AZ 63029', 'None', 5),
(50, 'Thomas', 'Brian', '7783 Maple Rd, Houston, IL 68861', NULL, 4),
(51, 'Scott', 'Steven', '8666 Oak Ave, Los Angeles, CA 63663', 'No known medical conditions', 3),
(52, 'Torres', 'Jennifer', '6371 Washington Ave, Philadelphia, FL 43423', 'No known medical conditions', 3),
(53, 'Allen', 'Amelia', '7822 Pine St, Los Angeles, CA 16134', 'Diabetes Type 1', 5),
(54, 'Baker', 'Daniel', '4266 Washington Ave, Los Angeles, NY 59604', 'Diabetes Type 1', 4),
(55, 'Clark', 'Thomas', '4231 Maple Rd, San Antonio, PA 54692', 'No known medical conditions', 2),
(56, 'Allen', 'Jessica', '5298 Pine St, Philadelphia, PA 90087', 'None', 7),
(57, 'Jackson', 'Thomas', '8921 Main St, New York, TX 59159', 'Allergies', 4),
(58, 'Davis', 'Steven', '4754 Maple Rd, New York, NY 10208', 'Allergies', 7),
(59, 'Martin', 'Charles', '3242 Elm Dr, Houston, PA 77741', 'Diabetes Type 1', 1),
(60, 'Martin', 'Edward', '5445 Cedar Ln, San Diego, OH 52193', NULL, 4),
(61, 'Thomas', 'Paul', '932 Cedar Ln, New York, AZ 77267', 'Asthma', 6),
(62, 'King', 'Michael', '3349 Pine St, Chicago, IL 42974', 'None', 7),
(63, 'Scott', 'Jennifer', '2404 Oak Ave, Phoenix, NY 48260', 'Allergies', 7),
(64, 'Walker', 'Olivia', '7248 Park Rd, New York, FL 22564', 'Allergies', 3),
(65, 'Adams', 'Isabella', '2846 Main St, New York, AZ 55128', 'No known medical conditions', 2),
(66, 'Brown', 'John', '1703 Pine St, San Antonio, CA 26544', 'Asthma', 4),
(67, 'Taylor', 'James', '3332 Washington Ave, Houston, TX 88239', 'Diabetes Type 1', 3),
(68, 'White', 'Elizabeth', '6163 Maple Rd, San Diego, OH 66154', 'Allergies', 4),
(69, 'Garcia', 'Sophia', '8426 Maple Rd, Philadelphia, TX 68728', 'Asthma', 1),
(70, 'Brown', 'Karen', '3636 Elm Dr, San Antonio, AZ 99944', 'No known medical conditions', 5),
(71, 'Lewis', 'Sarah', '3527 Park Rd, Houston, AZ 63613', 'Diabetes Type 1', 2),
(72, 'Jones', 'Elizabeth', '3631 Cedar Ln, Chicago, PA 62044', NULL, 5),
(73, 'Baker', 'Steven', '2258 Elm Dr, Los Angeles, IL 13037', 'Allergies', 6),
(74, 'White', 'Charlotte', '226 Cedar Ln, Phoenix, FL 51464', 'Allergies', 3),
(75, 'Green', 'Patricia', '4378 Oak Ave, New York, FL 74526', NULL, 2),
(76, 'Miller', 'Mary', '3656 Maple Rd, San Antonio, FL 72064', 'Asthma', 6),
(77, 'Hall', 'Ava', '3404 Pine St, Los Angeles, OH 82619', 'Allergies', 1),
(78, 'Walker', 'Olivia', '4978 Elm Dr, Chicago, IL 44078', NULL, 6),
(79, 'Lee', 'Olivia', '2059 Pine St, Los Angeles, NY 71328', 'No known medical conditions', 7),
(80, 'White', 'Elizabeth', '691 Main St, San Antonio, AZ 26647', 'Diabetes Type 1', 2),
(81, 'Walker', 'Ronald', '3929 Cedar Ln, Phoenix, FL 10791', 'None', 1),
(82, 'Smith', 'Richard', '7966 Main St, Los Angeles, IL 68509', 'Allergies', 2),
(83, 'Carter', 'Patricia', '2648 Park Rd, Los Angeles, PA 89298', 'None', 3),
(84, 'Harris', 'Karen', '6825 Maple Rd, Chicago, CA 20859', 'Allergies', 2),
(85, 'Nelson', 'Edward', '5947 Main St, Los Angeles, AZ 29285', NULL, 2),
(86, 'Allen', 'Charles', '8733 Cedar Ln, Philadelphia, OH 29252', 'Diabetes Type 1', 7),
(87, 'Smith', 'Mia', '7798 Park Rd, Houston, IL 33384', 'Asthma', 6),
(88, 'Harris', 'Richard', '2652 Oak Ave, Houston, CA 70473', 'None', 7),
(89, 'Young', 'Evelyn', '8324 Maple Rd, Los Angeles, CA 67967', 'Diabetes Type 1', 7),
(90, 'Mitchell', 'Mary', '579 Park Rd, San Diego, AZ 58926', 'Allergies', 7),
(91, 'Moore', 'Steven', '6363 Elm Dr, Houston, FL 41625', 'Allergies', 5),
(92, 'Martin', 'Evelyn', '1160 Main St, Chicago, AZ 22458', 'None', 4),
(93, 'Jackson', 'Barbara', '1340 Park Rd, San Diego, IL 20352', 'No known medical conditions', 7),
(94, 'Brown', 'Olivia', '9057 Elm Dr, Phoenix, NY 22358', 'Asthma', 5),
(95, 'Davis', 'Brian', '8643 Main St, Los Angeles, OH 70035', NULL, 7),
(96, 'Davis', 'Thomas', '9322 Park Rd, Phoenix, PA 40232', 'No known medical conditions', 3),
(97, 'Lee', 'Joseph', '280 Main St, San Diego, TX 28445', 'None', 2),
(98, 'Lee', 'Patricia', '2448 Cedar Ln, Houston, AZ 57028', 'None', 1),
(99, 'Nelson', 'Susan', '9205 Cedar Ln, Houston, AZ 70765', 'None', 3),
(100, 'Rodriguez', 'Mark', '2636 Cedar Ln, Los Angeles, IL 95818', 'Diabetes Type 1', 4),
(101, 'Martinez', 'Edward', '1990 Cedar Ln, Chicago, CA 49458', 'None', 3),
(102, 'Johnson', 'Robert', '800 Park Rd, Phoenix, FL 44067', 'Allergies', 6),
(103, 'Johnson', 'Mary', '2725 Main St, Chicago, PA 28980', 'No known medical conditions', 1),
(104, 'Lewis', 'Jessica', '6478 Maple Rd, Los Angeles, TX 43599', 'No known medical conditions', 2),
(105, 'Davis', 'Jessica', '4149 Main St, Houston, TX 75938', 'None', 2),
(106, 'Thompson', 'Jennifer', '773 Park Rd, Phoenix, FL 62905', 'None', 5),
(107, 'Jackson', 'Thomas', '3618 Park Rd, Houston, TX 37160', 'No known medical conditions', 2),
(108, 'Hill', 'Donald', '8941 Maple Rd, Chicago, OH 60247', 'No known medical conditions', 5),
(109, 'Torres', 'Mary', '3501 Main St, San Diego, FL 82688', 'Allergies', 3),
(110, 'Davis', 'David', '754 Oak Ave, San Diego, OH 78247', 'None', 6),
(111, 'Young', 'David', '506 Main St, Philadelphia, FL 96129', 'No known medical conditions', 2),
(112, 'Mitchell', 'Susan', '8689 Maple Rd, San Antonio, PA 41887', 'Asthma', 5),
(113, 'Clark', 'Sophia', '4621 Maple Rd, Los Angeles, CA 61836', NULL, 2),
(114, 'Harris', 'Susan', '2702 Main St, San Antonio, OH 18560', NULL, 1),
(115, 'Clark', 'Evelyn', '7456 Maple Rd, Philadelphia, PA 20527', 'No known medical conditions', 6),
(116, 'Scott', 'Olivia', '5127 Cedar Ln, San Antonio, PA 11816', 'Asthma', 5),
(117, 'Rodriguez', 'Mark', '1369 Washington Ave, San Diego, TX 97510', 'No known medical conditions', 3),
(118, 'Robinson', 'Charlotte', '6101 Cedar Ln, Chicago, TX 26523', 'Asthma', 4),
(119, 'Clark', 'Emma', '799 Main St, Los Angeles, CA 84386', 'Allergies', 5),
(120, 'Jones', 'Thomas', '5063 Washington Ave, San Diego, NY 91400', 'Allergies', 3),
(121, 'Wright', 'Kenneth', '9092 Maple Rd, New York, IL 11874', 'Diabetes Type 1', 6);

-- --------------------------------------------------------

--
-- Table structure for table `student_parents`
--

CREATE TABLE `student_parents` (
  `student_id` int(11) NOT NULL,
  `parents_id` int(11) NOT NULL,
  `relation` enum('father','mother','guardians') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_parents`
--

INSERT INTO `student_parents` (`student_id`, `parents_id`, `relation`) VALUES
(1, 7, 'mother'),
(2, 5, 'mother'),
(3, 2, 'mother'),
(4, 6, 'father'),
(5, 1, 'mother'),
(6, 1, 'mother'),
(7, 2, 'mother'),
(7, 3, 'guardians'),
(8, 10, 'guardians'),
(10, 17, 'father'),
(11, 11, 'father'),
(11, 12, 'father'),
(12, 8, 'father'),
(13, 4, 'mother'),
(14, 3, 'guardians'),
(14, 13, 'father'),
(16, 4, 'father'),
(19, 15, 'father'),
(22, 16, 'father'),
(23, 14, 'father'),
(42, 14, 'father');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL,
  `Last_Name` varchar(20) NOT NULL,
  `First_Name` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `backgroundCheck` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `Last_Name`, `First_Name`, `phone`, `backgroundCheck`) VALUES
(1, 'Miao', 'Emma', '+447786572472', 0),
(2, 'wu', 'Jenny', '+447786572473', 1),
(3, 'Hopkins', 'John', '+447643726382', 1),
(4, 'Jadady', 'Jason', '+447562908562', 0),
(5, 'Cmma', 'Amma', '+447826153922', 1),
(6, 'White', 'Kanye', '+447285628739', 0),
(8, 'White', 'Kanye', '+447285628739', 0),
(9, 'Johnson', 'Linda', '+447725643210', 1),
(10, 'Brown', 'Robert', '+447731987654', 0),
(11, 'Miller', 'Susan', '+447712345678', 1),
(13, 'Lumi', 'Emma', '07786452547', 1),
(14, 'Nijida', 'skuara', '+447265618967', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','teacher','parent','admin') NOT NULL DEFAULT 'student',
  `Gender` enum('Male','Female') DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `parents_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `role`, `Gender`, `birthday`, `teacher_id`, `student_id`, `parents_id`) VALUES
(1, 'TheMuses', 'myl666@gmail.com', '$2y$10$ntM2d2ES1pse.TINNgKCDuHng6SPfdxMKgOTX5vntluerRcF1xl.O', 'admin', 'Male', '1996-07-06', NULL, NULL, NULL),
(2, 'DAshabi', 'johnsmith@gmail.com', '111111', 'student', 'Male', '2011-06-20', NULL, 1, NULL),
(3, 'EmmaLumi', 'emmalumi@gmail.com', '222222', 'teacher', 'Female', '2001-06-05', 13, NULL, NULL),
(7, 'Mark42', 'john@gamil.com', '123123', 'parent', 'Male', '1985-06-21', NULL, NULL, 1),
(8, 'ssfcxddd12', '11111@gaedwa.com', 'wwwwwww', 'teacher', 'Male', '1970-01-01', 8, NULL, NULL),
(9, 'MYL666', 'myl666@666.com', '666666', 'student', 'Male', '1970-01-22', NULL, 3, NULL),
(10, 'Lihua666', 'lihua666@666.com', '333333', 'parent', 'Male', NULL, NULL, NULL, 10),
(11, 'dsssssssddd', '1111@qq.com', 'qqqqqq', 'teacher', '', NULL, NULL, NULL, NULL),
(12, 'emma.johnson', 'emma.johnson@example.com', '111222333', 'student', 'Female', '2011-10-10', NULL, 2, NULL),
(13, 'sophia.brown', 'sophia.brown@example.com', '111222333', 'student', 'Male', '2010-11-26', NULL, 4, NULL),
(14, 'liam.jones', 'liam.jones@example.com', '111222333', 'student', 'Female', '2010-11-14', NULL, 5, NULL),
(15, 'ava.garcia', 'ava.garcia@example.com', '111222333', 'student', 'Male', '2012-04-14', NULL, 6, NULL),
(16, 'mason.miller', 'mason.miller@example.com', '111222333', 'student', 'Female', '2010-07-30', NULL, 7, NULL),
(17, 'isabella.davis', 'isabella.davis@example.com', '111222333', 'student', 'Male', '2012-05-02', NULL, 8, NULL),
(18, 'lucas.rodriguez', 'lucas.rodriguez@example.com', '111222333', 'student', 'Female', '2011-03-27', NULL, 9, NULL),
(19, 'mia.martinez', 'mia.martinez@example.com', '111222333', 'student', 'Male', '2011-05-25', NULL, 10, NULL),
(20, 'ethan.hernandez', 'ethan.hernandez@example.com', '111222333', 'student', 'Male', '2009-11-01', NULL, 11, NULL),
(21, 'amelia.lopez', 'amelia.lopez@example.com', '111222333', 'student', 'Male', '2010-08-23', NULL, 12, NULL),
(22, 'logan.gonzalez', 'logan.gonzalez@example.com', '111222333', 'student', 'Male', '2010-05-12', NULL, 13, NULL),
(23, 'charlotte.wilson', 'charlotte.wilson@example.com', '111222333', 'student', 'Male', '2012-03-18', NULL, 14, NULL),
(24, 'aiden.anderson', 'aiden.anderson@example.com', '111222333', 'student', 'Female', '2009-01-06', NULL, 15, NULL),
(25, 'harper.thomas', 'harper.thomas@example.com', '111222333', 'student', 'Female', '2010-07-31', NULL, 16, NULL),
(26, 'elijah.taylor', 'elijah.taylor@example.com', '111222333', 'student', 'Female', '2009-12-11', NULL, 17, NULL),
(27, 'evelyn.moore', 'evelyn.moore@example.com', '111222333', 'student', 'Male', '2010-01-23', NULL, 18, NULL),
(28, 'james.jackson', 'james.jackson@example.com', '111222333', 'student', 'Female', '2012-10-12', NULL, 19, NULL),
(29, 'abigail.martin', 'abigail.martin@example.com', '111222333', 'student', 'Male', '2011-03-18', NULL, 20, NULL),
(41, 'emma.miao', 'emma.miao@example.com', '111222333', 'teacher', 'Male', '1978-07-01', 1, NULL, NULL),
(42, 'jenny.wu', 'jenny.wu@example.com', '111222333', 'teacher', 'Male', '1974-11-19', 2, NULL, NULL),
(43, 'john.hopkins', 'john.hopkins@example.com', '111222333', 'teacher', 'Male', '1977-10-20', 3, NULL, NULL),
(44, 'jason.jadady', 'jason.jadady@example.com', '111222333', 'teacher', 'Male', '1995-02-28', 4, NULL, NULL),
(45, 'amma.cmma', 'amma.cmma@example.com', '111222333', 'teacher', 'Female', '1976-10-21', 5, NULL, NULL),
(46, 'kanye.white', 'kanye.white@example.com', '111222333', 'teacher', 'Male', '1980-02-29', 6, NULL, NULL),
(47, 'linda.johnson', 'linda.johnson@example.com', '111222333', 'teacher', 'Female', '1966-12-07', 9, NULL, NULL),
(48, 'robert.brown', 'robert.brown@example.com', '111222333', 'teacher', 'Male', '1995-08-03', 10, NULL, NULL),
(49, 'susan.miller', 'susan.miller@example.com', '111222333', 'teacher', 'Male', '1984-06-01', 11, NULL, NULL),
(50, 'skuara.nijida', 'skuara.nijida@example.com', '111222333', 'teacher', 'Female', '1980-10-05', 14, NULL, NULL),
(61, 'joseph.allen', 'joseph.allen@example.com', '111222333', 'student', 'Male', '2012-05-19', NULL, 22, NULL),
(62, 'harper.young', 'harper.young@example.com', '111222333', 'student', 'Female', '2010-04-13', NULL, 23, NULL),
(63, 'paul.robinson', 'paul.robinson@example.com', '111222333', 'student', 'Male', '2012-09-14', NULL, 24, NULL),
(64, 'emma.king', 'emma.king@example.com', '111222333', 'student', 'Female', '2009-06-03', NULL, 25, NULL),
(65, 'mia.garcia', 'mia.garcia@example.com', '111222333', 'student', 'Male', '2011-06-11', NULL, 26, NULL),
(66, 'charlotte.lewis', 'charlotte.lewis@example.com', '111222333', 'student', 'Female', '2010-05-27', NULL, 27, NULL),
(67, 'george.martin', 'george.martin@example.com', '111222333', 'student', 'Male', '2009-05-25', NULL, 28, NULL),
(68, 'jennifer.martinez', 'jennifer.martinez@example.com', '111222333', 'student', 'Female', '2009-01-29', NULL, 29, NULL),
(69, 'daniel.green', 'daniel.green@example.com', '111222333', 'student', 'Female', '2012-12-17', NULL, 30, NULL),
(70, 'charles.miller', 'charles.miller@example.com', '111222333', 'student', 'Female', '2012-08-15', NULL, 31, NULL),
(71, 'emma.robinson', 'emma.robinson@example.com', '111222333', 'student', 'Male', '2010-09-27', NULL, 32, NULL),
(72, 'sophia.martinez', 'sophia.martinez@example.com', '111222333', 'student', 'Female', '2012-06-30', NULL, 33, NULL),
(73, 'brian.martin', 'brian.martin@example.com', '111222333', 'student', 'Male', '2012-06-17', NULL, 34, NULL),
(74, 'richard.miller', 'richard.miller@example.com', '111222333', 'student', 'Female', '2010-11-10', NULL, 35, NULL),
(75, 'emma.rodriguez', 'emma.rodriguez@example.com', '111222333', 'student', 'Female', '2011-09-01', NULL, 36, NULL),
(76, 'elizabeth.brown', 'elizabeth.brown@example.com', '111222333', 'student', 'Female', '2012-03-12', NULL, 37, NULL),
(77, 'mia.robinson', 'mia.robinson@example.com', '111222333', 'student', 'Male', '2009-02-10', NULL, 38, NULL),
(78, 'jessica.martin', 'jessica.martin@example.com', '111222333', 'student', 'Male', '2012-10-26', NULL, 39, NULL),
(79, 'edward.martin', 'edward.martin@example.com', '111222333', 'student', 'Male', '2010-05-22', NULL, 40, NULL),
(80, 'richard.thomas', 'richard.thomas@example.com', '111222333', 'student', 'Female', '2009-03-03', NULL, 41, NULL),
(81, 'patricia.williams', 'patricia.williams@example.com', '111222333', 'student', 'Male', '2011-12-30', NULL, 42, NULL),
(82, 'charles.green', 'charles.green@example.com', '111222333', 'student', 'Male', '2012-02-07', NULL, 43, NULL),
(83, 'robert.jackson', 'robert.jackson@example.com', '111222333', 'student', 'Female', '2012-05-09', NULL, 44, NULL),
(84, 'steven.thomas', 'steven.thomas@example.com', '111222333', 'student', 'Male', '2012-04-04', NULL, 45, NULL),
(85, 'jennifer.rodriguez', 'jennifer.rodriguez@example.com', '111222333', 'student', 'Female', '2010-02-16', NULL, 46, NULL),
(86, 'elizabeth.king', 'elizabeth.king@example.com', '111222333', 'student', 'Female', '2011-06-16', NULL, 47, NULL),
(87, 'jessica.rodriguez', 'jessica.rodriguez@example.com', '111222333', 'student', 'Male', '2010-04-20', NULL, 48, NULL),
(88, 'john.robinson', 'john.robinson@example.com', '111222333', 'student', 'Male', '2010-07-17', NULL, 49, NULL),
(89, 'brian.thomas', 'brian.thomas@example.com', '111222333', 'student', 'Male', '2011-12-20', NULL, 50, NULL),
(90, 'steven.scott', 'steven.scott@example.com', '111222333', 'student', 'Male', '2009-07-27', NULL, 51, NULL),
(91, 'jennifer.torres', 'jennifer.torres@example.com', '111222333', 'student', 'Male', '2010-10-28', NULL, 52, NULL),
(92, 'amelia.allen', 'amelia.allen@example.com', '111222333', 'student', 'Female', '2010-07-17', NULL, 53, NULL),
(93, 'daniel.baker', 'daniel.baker@example.com', '111222333', 'student', 'Male', '2009-10-14', NULL, 54, NULL),
(94, 'thomas.clark', 'thomas.clark@example.com', '111222333', 'student', 'Male', '2010-09-02', NULL, 55, NULL),
(95, 'jessica.allen', 'jessica.allen@example.com', '111222333', 'student', 'Female', '2010-09-18', NULL, 56, NULL),
(96, 'thomas.jackson', 'thomas.jackson@example.com', '111222333', 'student', 'Male', '2010-02-07', NULL, 57, NULL),
(97, 'steven.davis', 'steven.davis@example.com', '111222333', 'student', 'Female', '2011-03-26', NULL, 58, NULL),
(98, 'charles.martin', 'charles.martin@example.com', '111222333', 'student', 'Male', '2010-06-06', NULL, 59, NULL),
(100, 'paul.thomas', 'paul.thomas@example.com', '111222333', 'student', 'Female', '2012-08-25', NULL, 61, NULL),
(101, 'michael.king', 'michael.king@example.com', '111222333', 'student', 'Female', '2012-08-14', NULL, 62, NULL),
(102, 'jennifer.scott', 'jennifer.scott@example.com', '111222333', 'student', 'Female', '2009-10-10', NULL, 63, NULL),
(103, 'olivia.walker', 'olivia.walker@example.com', '111222333', 'student', 'Male', '2012-01-02', NULL, 64, NULL),
(104, 'isabella.adams', 'isabella.adams@example.com', '111222333', 'student', 'Female', '2010-10-04', NULL, 65, NULL),
(105, 'john.brown', 'john.brown@example.com', '111222333', 'student', 'Female', '2010-11-27', NULL, 66, NULL),
(106, 'james.taylor', 'james.taylor@example.com', '111222333', 'student', 'Female', '2010-08-21', NULL, 67, NULL),
(107, 'elizabeth.white', 'elizabeth.white@example.com', '111222333', 'student', 'Female', '2009-05-15', NULL, 68, NULL),
(108, 'sophia.garcia', 'sophia.garcia@example.com', '111222333', 'student', 'Male', '2011-09-03', NULL, 69, NULL),
(109, 'karen.brown', 'karen.brown@example.com', '111222333', 'student', 'Male', '2012-11-13', NULL, 70, NULL),
(110, 'sarah.lewis', 'sarah.lewis@example.com', '111222333', 'student', 'Female', '2010-06-26', NULL, 71, NULL),
(111, 'elizabeth.jones', 'elizabeth.jones@example.com', '111222333', 'student', 'Female', '2012-10-30', NULL, 72, NULL),
(112, 'steven.baker', 'steven.baker@example.com', '111222333', 'student', 'Female', '2010-03-08', NULL, 73, NULL),
(113, 'charlotte.white', 'charlotte.white@example.com', '111222333', 'student', 'Male', '2009-10-26', NULL, 74, NULL),
(114, 'patricia.green', 'patricia.green@example.com', '111222333', 'student', 'Male', '2010-07-26', NULL, 75, NULL),
(115, 'mary.miller', 'mary.miller@example.com', '111222333', 'student', 'Female', '2009-09-24', NULL, 76, NULL),
(116, 'ava.hall', 'ava.hall@example.com', '111222333', 'student', 'Male', '2009-12-03', NULL, 77, NULL),
(118, 'olivia.lee', 'olivia.lee@example.com', '111222333', 'student', 'Male', '2010-05-15', NULL, 79, NULL),
(120, 'ronald.walker', 'ronald.walker@example.com', '111222333', 'student', 'Female', '2009-06-07', NULL, 81, NULL),
(121, 'richard.smith', 'richard.smith@example.com', '111222333', 'student', 'Male', '2011-08-23', NULL, 82, NULL),
(122, 'patricia.carter', 'patricia.carter@example.com', '111222333', 'student', 'Male', '2010-06-01', NULL, 83, NULL),
(123, 'karen.harris', 'karen.harris@example.com', '111222333', 'student', 'Male', '2009-12-30', NULL, 84, NULL),
(124, 'edward.nelson', 'edward.nelson@example.com', '111222333', 'student', 'Female', '2011-12-30', NULL, 85, NULL),
(125, 'charles.allen', 'charles.allen@example.com', '111222333', 'student', 'Female', '2010-04-26', NULL, 86, NULL),
(126, 'mia.smith', 'mia.smith@example.com', '111222333', 'student', 'Female', '2012-10-31', NULL, 87, NULL),
(127, 'richard.harris', 'richard.harris@example.com', '111222333', 'student', 'Female', '2010-06-10', NULL, 88, NULL),
(128, 'evelyn.young', 'evelyn.young@example.com', '111222333', 'student', 'Female', '2010-11-24', NULL, 89, NULL),
(129, 'mary.mitchell', 'mary.mitchell@example.com', '111222333', 'student', 'Male', '2012-04-17', NULL, 90, NULL),
(130, 'steven.moore', 'steven.moore@example.com', '111222333', 'student', 'Female', '2009-02-17', NULL, 91, NULL),
(131, 'evelyn.martin', 'evelyn.martin@example.com', '111222333', 'student', 'Female', '2012-10-24', NULL, 92, NULL),
(132, 'barbara.jackson', 'barbara.jackson@example.com', '111222333', 'student', 'Male', '2009-12-18', NULL, 93, NULL),
(133, 'olivia.brown', 'olivia.brown@example.com', '111222333', 'student', 'Male', '2009-11-28', NULL, 94, NULL),
(134, 'brian.davis', 'brian.davis@example.com', '111222333', 'student', 'Female', '2012-01-01', NULL, 95, NULL),
(135, 'thomas.davis', 'thomas.davis@example.com', '111222333', 'student', 'Male', '2012-11-13', NULL, 96, NULL),
(136, 'joseph.lee', 'joseph.lee@example.com', '111222333', 'student', 'Female', '2011-09-29', NULL, 97, NULL),
(137, 'patricia.lee', 'patricia.lee@example.com', '111222333', 'student', 'Male', '2011-04-29', NULL, 98, NULL),
(138, 'susan.nelson', 'susan.nelson@example.com', '111222333', 'student', 'Male', '2010-07-05', NULL, 99, NULL),
(139, 'mark.rodriguez', 'mark.rodriguez@example.com', '111222333', 'student', 'Female', '2011-01-10', NULL, 100, NULL),
(140, 'edward.martinez', 'edward.martinez@example.com', '111222333', 'student', 'Female', '2009-01-01', NULL, 101, NULL),
(141, 'robert.johnson', 'robert.johnson@example.com', '111222333', 'student', 'Female', '2011-12-07', NULL, 102, NULL),
(142, 'mary.johnson', 'mary.johnson@example.com', '111222333', 'student', 'Male', '2012-01-30', NULL, 103, NULL),
(143, 'jessica.lewis', 'jessica.lewis@example.com', '111222333', 'student', 'Female', '2011-02-06', NULL, 104, NULL),
(144, 'jessica.davis', 'jessica.davis@example.com', '111222333', 'student', 'Female', '2011-03-23', NULL, 105, NULL),
(145, 'jennifer.thompson', 'jennifer.thompson@example.com', '111222333', 'student', 'Female', '2009-08-30', NULL, 106, NULL),
(147, 'donald.hill', 'donald.hill@example.com', '111222333', 'student', 'Male', '2011-11-23', NULL, 108, NULL),
(148, 'mary.torres', 'mary.torres@example.com', '111222333', 'student', 'Female', '2009-11-30', NULL, 109, NULL),
(149, 'david.davis', 'david.davis@example.com', '111222333', 'student', 'Male', '2012-12-06', NULL, 110, NULL),
(150, 'david.young', 'david.young@example.com', '111222333', 'student', 'Male', '2010-04-18', NULL, 111, NULL),
(151, 'susan.mitchell', 'susan.mitchell@example.com', '111222333', 'student', 'Female', '2011-01-26', NULL, 112, NULL),
(152, 'sophia.clark', 'sophia.clark@example.com', '111222333', 'student', 'Female', '2009-01-30', NULL, 113, NULL),
(153, 'susan.harris', 'susan.harris@example.com', '111222333', 'student', 'Female', '2009-10-12', NULL, 114, NULL),
(154, 'evelyn.clark', 'evelyn.clark@example.com', '111222333', 'student', 'Male', '2012-08-29', NULL, 115, NULL),
(155, 'olivia.scott', 'olivia.scott@example.com', '111222333', 'student', 'Female', '2012-11-12', NULL, 116, NULL),
(157, 'charlotte.robinson', 'charlotte.robinson@example.com', '111222333', 'student', 'Female', '2011-07-26', NULL, 118, NULL),
(158, 'emma.clark', 'emma.clark@example.com', '111222333', 'student', 'Male', '2012-03-09', NULL, 119, NULL),
(159, 'thomas.jones', 'thomas.jones@example.com', '111222333', 'student', 'Male', '2012-09-08', NULL, 120, NULL),
(160, 'kenneth.wright', 'kenneth.wright@example.com', '111222333', 'student', 'Female', '2010-06-08', NULL, 121, NULL),
(161, 'edward.martin1', 'edward.martin1@example.com', '111222333', 'student', 'Female', '2009-12-28', NULL, 60, NULL),
(162, 'olivia.walker1', 'olivia.walker1@example.com', '111222333', 'student', 'Female', '2009-01-30', NULL, 78, NULL),
(163, 'elizabeth.white1', 'elizabeth.white1@example.com', '111222333', 'student', 'Male', '2010-10-18', NULL, 80, NULL),
(164, 'thomas.jackson1', 'thomas.jackson1@example.com', '111222333', 'student', 'Male', '2011-08-05', NULL, 107, NULL),
(165, 'mark.rodriguez1', 'mark.rodriguez1@example.com', '111222333', 'student', 'Female', '2011-01-03', NULL, 117, NULL),
(166, 'Alice Ace', 'aliceace@gmail.com', '244466666', 'parent', 'Female', '1995-04-20', NULL, NULL, 2),
(167, 'wwax', 'waswish@example.com', 'parent123', 'parent', 'Female', '1962-11-14', NULL, NULL, 3),
(168, 'aaxam', 'axamalison@example.com', 'parent123', 'parent', 'Female', '1960-12-25', NULL, NULL, 4),
(169, 'ebaska', 'emma66@gmail.com', 'parent123', 'parent', 'Male', '1973-03-01', NULL, NULL, 5),
(170, 'jba', 'jiba@gmail.com', 'parent123', 'parent', 'Male', '1967-11-24', NULL, NULL, 6),
(171, 'aoscar', 'alyoscar@gmail.com', 'parent123', 'parent', 'Male', '1970-02-03', NULL, NULL, 7),
(172, 'ooliver', 'ommaroliver@qq.com', 'parent123', 'parent', 'Female', '1967-08-04', NULL, NULL, 8),
(173, 'wocaow', 'wocao@gamil.com', '222222', 'teacher', NULL, NULL, NULL, NULL, NULL),
(176, 'lbrown', 'lbrown733@example.com', 'parent123', 'parent', 'Male', '1969-12-07', NULL, NULL, 11),
(177, 'dgreen', 'dgreen231@test.com', 'parent123', 'parent', 'Female', '1985-04-24', NULL, NULL, 12),
(178, 'sblack', 'sblack220@test.com', 'parent123', 'parent', 'Female', '1975-12-22', NULL, NULL, 13),
(179, 'mlee', 'mlee950@test.com', 'parent123', 'parent', 'Female', '1988-10-22', NULL, NULL, 14),
(180, 'oclark', 'oclark199@example.com', 'parent123', 'parent', 'Male', '1980-07-17', NULL, NULL, 15),
(181, 'eturner', 'eturner368@demo.com', 'parent123', 'parent', 'Male', '1989-11-24', NULL, NULL, 16),
(182, 'ewalker', 'ewalker207@test.com', 'parent123', 'parent', 'Male', '1977-06-26', NULL, NULL, 17),
(183, 'lyoung', 'lyoung279@example.com', 'parent123', 'parent', 'Female', '1970-07-27', NULL, NULL, 18),
(184, 'ahill', 'ahill153@test.com', 'parent123', 'parent', 'Male', '1961-08-20', NULL, NULL, 19),
(185, 'nscott', 'nscott869@demo.com', 'parent123', 'parent', 'Male', '1986-11-22', NULL, NULL, 20),
(186, 'jmorgan', 'jmorgan127@demo.com', 'parent123', 'parent', 'Male', '1980-02-25', NULL, NULL, 21),
(187, 'mbennett', 'mbennett627@example.com', 'parent123', 'parent', 'Male', '1985-06-02', NULL, NULL, 22),
(188, 'lknight', 'lknight345@test.com', 'parent123', 'parent', 'Female', '1989-12-15', NULL, NULL, 23),
(189, 'agray', 'agray364@demo.com', 'parent123', 'parent', 'Female', '1990-02-13', NULL, NULL, 24),
(190, 'jreed', 'jreed205@example.com', 'parent123', 'parent', 'Male', '1962-10-09', NULL, NULL, 25),
(191, 'cfoster', 'cfoster824@test.com', 'parent123', 'parent', 'Female', '1975-05-04', NULL, NULL, 26),
(192, 'hharper', 'hharper136@example.com', 'parent123', 'parent', 'Female', '1985-09-21', NULL, NULL, 27),
(193, 'eadams', 'eadams795@test.com', 'parent123', 'parent', 'Male', '1980-05-19', NULL, NULL, 28),
(194, 'bross', 'bross139@demo.com', 'parent123', 'parent', 'Male', '1980-05-20', NULL, NULL, 29),
(195, 'zcampbell', 'zcampbell133@test.com', 'parent123', 'parent', 'Female', '1987-05-01', NULL, NULL, 30);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `borrowed_book`
--
ALTER TABLE `borrowed_book`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `fk_student` (`Student_id`),
  ADD KEY `fk_book` (`Book_id`);

--
-- Indexes for table `chat_board`
--
ALTER TABLE `chat_board`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `chat_board_ibfk_1` (`user_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `fk_class_teacher_id` (`teacher_id`);

--
-- Indexes for table `library`
--
ALTER TABLE `library`
  ADD PRIMARY KEY (`Book_id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`parents_id`);

--
-- Indexes for table `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`salaries_id`),
  ADD UNIQUE KEY `unique_teacher_month` (`teacher_id`,`salary_month`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `fk_student_class_id` (`class_id`);

--
-- Indexes for table `student_parents`
--
ALTER TABLE `student_parents`
  ADD PRIMARY KEY (`student_id`,`parents_id`),
  ADD KEY `fk_parents_id` (`parents_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_user_student_id` (`student_id`),
  ADD KEY `fk_user_teacher_id` (`teacher_id`),
  ADD KEY `fk_user_parent_id` (`parents_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrowed_book`
--
ALTER TABLE `borrowed_book`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `chat_board`
--
ALTER TABLE `chat_board`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `library`
--
ALTER TABLE `library`
  MODIFY `Book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `parents_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `salaries_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'students''ID', AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowed_book`
--
ALTER TABLE `borrowed_book`
  ADD CONSTRAINT `fk_book` FOREIGN KEY (`Book_id`) REFERENCES `library` (`Book_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`Student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chat_board`
--
ALTER TABLE `chat_board`
  ADD CONSTRAINT `chat_board_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `fk_class_teacher_id` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`);

--
-- Constraints for table `salaries`
--
ALTER TABLE `salaries`
  ADD CONSTRAINT `fk_salaries_teacher_id` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_student_class_id` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_parents`
--
ALTER TABLE `student_parents`
  ADD CONSTRAINT `fk_parents_id` FOREIGN KEY (`parents_id`) REFERENCES `parents` (`parents_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_parents_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_parents_ibfk_2` FOREIGN KEY (`parents_id`) REFERENCES `parents` (`parents_id`) ON DELETE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_parent_id` FOREIGN KEY (`parents_id`) REFERENCES `parents` (`parents_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_teacher_id` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
