-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2024 at 09:10 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `a_id` int(11) NOT NULL,
  `a_name` text NOT NULL,
  `a_email` text NOT NULL,
  `a_contact` text NOT NULL,
  `a_password` text NOT NULL,
  `joining_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`a_id`, `a_name`, `a_email`, `a_contact`, `a_password`, `joining_date`) VALUES
(1, 'Parvez Mosarof', 'admin@gmail.com', '01630411972', '$2y$10$gR9tim6URNG1pqWurgEsze9OZxtu/WRgs81bvA4YK8xmSZ73YUdTe', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bookedtable`
--

CREATE TABLE `tbl_bookedtable` (
  `t_id` int(11) NOT NULL,
  `t_category` varchar(100) NOT NULL,
  `t_seat` varchar(40) NOT NULL,
  `t_status` varchar(255) NOT NULL,
  `u_id` int(11) NOT NULL,
  `u_name` varchar(255) NOT NULL,
  `u_email` varchar(255) NOT NULL,
  `u_contact` varchar(100) NOT NULL,
  `t_desc` varchar(255) NOT NULL,
  `t_bookeddate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_bookedtable`
--

INSERT INTO `tbl_bookedtable` (`t_id`, `t_category`, `t_seat`, `t_status`, `u_id`, `u_name`, `u_email`, `u_contact`, `t_desc`, `t_bookeddate`) VALUES
(52, 'medium', '5 seats', 'Accept', 12, 'Parvez Mosarof', 'example@gmail.com', '01630411972', 'Subject', '2024-02-02 22:16:00'),
(53, 'medium', '5 seats', 'Cancel', 12, 'Parvez Mosarof', 'parvezmosarof195@gmail.com', '01630411972', 'SS', '2024-02-02 22:04:00'),
(54, 'large', '10', '', 12, 'Parvez Mosarof', 'parvezmosarof195@gmail.com', '01630411972', 'This is my family party.', '2024-02-10 23:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_catagory`
--

CREATE TABLE `tbl_catagory` (
  `c_id` int(11) NOT NULL,
  `c_name` varchar(100) NOT NULL,
  `c_title` varchar(100) NOT NULL,
  `c_image` varchar(255) NOT NULL,
  `c_active` varchar(100) NOT NULL,
  `c_featured` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_catagory`
--

INSERT INTO `tbl_catagory` (`c_id`, `c_name`, `c_title`, `c_image`, `c_active`, `c_featured`) VALUES
(11, 'Starters', 'Starters', 'pexels-david-disponett-2161643 (1).jpg', 'Active', 'Display'),
(12, 'Main Course', 'Lunch', 'lunch.jpg', 'active', 'Display'),
(13, 'Juice', 'Juice', 'image17.jpg', 'active', 'Display'),
(14, 'Soft Drinks', 'Mojo', 'imagemojo.jpg', 'active', 'Display'),
(15, 'Snacks', 'Snacks', 'snacks.jpg', 'active', 'Display'),
(17, 'Soft Starters', 'Starters new', 'image17.jpg', 'active', 'Display'),
(18, 'Hot Drinks', 'Spicy Hot Drinks', 'spicy.jpg', 'Active', 'Display'),
(19, 'Dessert', 'Sweet Pop', 'sweet.jpg', 'Unactive', 'Non Display');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contactus`
--

CREATE TABLE `tbl_contactus` (
  `ctus_id` int(11) NOT NULL,
  `ctus_name` varchar(255) NOT NULL,
  `ctus_email` varchar(255) NOT NULL,
  `ctus_desc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_contactus`
--

INSERT INTO `tbl_contactus` (`ctus_id`, `ctus_name`, `ctus_email`, `ctus_desc`) VALUES
(89, 'Parvez Miah', 'rafsan@example.com', 'zero');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_feedback`
--

CREATE TABLE `tbl_feedback` (
  `f_id` int(11) NOT NULL,
  `f_content` varchar(255) NOT NULL,
  `u_id` int(11) NOT NULL,
  `f_rate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_feedback`
--

INSERT INTO `tbl_feedback` (`f_id`, `f_content`, `u_id`, `f_rate`) VALUES
(3, 'I like this restaurant.', 12, 5),
(4, 'Exquisite flavors coupled with impeccable service! A dining experience at this restaurant is nothing short of delightful, consistently earning my four-star recommendation.', 12, 4),
(5, 'I\'m not that happy. Because they don\'t have that much hospitality.', 12, 3),
(6, 'Foods are very delicious.', 12, 5),
(7, 'They are not making any offers for students.', 12, 2),
(8, 'This restaurant is too much noisy.', 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_food`
--

CREATE TABLE `tbl_food` (
  `f_id` int(11) NOT NULL,
  `f_title` varchar(255) NOT NULL,
  `f_desc` varchar(255) NOT NULL,
  `f_price` decimal(10,2) NOT NULL,
  `f_disctprice` decimal(10,2) NOT NULL,
  `f_vat` decimal(10,2) NOT NULL,
  `f_image` varchar(255) NOT NULL,
  `c_id` int(11) NOT NULL,
  `c_name` varchar(255) NOT NULL,
  `f_featured` varchar(100) NOT NULL,
  `f_active` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_food`
--

INSERT INTO `tbl_food` (`f_id`, `f_title`, `f_desc`, `f_price`, `f_disctprice`, `f_vat`, `f_image`, `c_id`, `c_name`, `f_featured`, `f_active`) VALUES
(5, 'Chocolate venilla', 'Chocolate ice cream is made by blending in vanilla essence in along with the eggs (optional), cream, milk and sugar. The vanilla essence added gives the ice cream a very natural aroma and vanilla flavour.', '4.00', '3.25', '3.00', 'image15.jpg', 15, 'Snacks', 'Display', 'Active'),
(6, 'Mojo', 'A soft drink is a drink that usually contains water, a sweetener, and a natural and/or artificial flavoring.', '3.50', '3.00', '4.15', 'imagemojo.jpg', 13, 'Soft Drinks', 'Display', 'Active'),
(7, 'Hot Drinks', 'Hu said that moderate coffee intake—about 2–5 cups a day—is linked to a lower likelihood of type 2 diabetes, heart disease, liver and endometrial cancers, Parkinson\\\'s disease, and depression. It\\\'s even possible that people who drink coffee can reduce th', '4.00', '3.55', '2.25', 'coffe.jpg', 18, 'Hot Drinks', 'Display', 'Unactive'),
(8, 'Snacks', 'A snack is a small portion of food eaten between meals. They may be simple, prepackaged items, raw fruits or vegetables or more complicated dishes but they are traditionally considered less than a full meal.', '5.25', '5.00', '1.25', 'snacks.jpg', 15, 'Snacks', 'Display', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification`
--

CREATE TABLE `tbl_notification` (
  `n_id` int(11) NOT NULL,
  `n_type` varchar(100) NOT NULL,
  `n_desc` varchar(255) NOT NULL,
  `u_id` int(11) NOT NULL,
  `t_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `o_id` int(11) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `f_title` varchar(150) NOT NULL,
  `f_disctprice` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `o_quantity` varchar(255) NOT NULL,
  `f_vat` decimal(10,2) NOT NULL,
  `o_date` date NOT NULL,
  `u_id` int(11) NOT NULL,
  `u_name` varchar(255) NOT NULL,
  `ta_id` int(11) DEFAULT NULL,
  `t_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`o_id`, `order_id`, `f_title`, `f_disctprice`, `total_amount`, `o_quantity`, `f_vat`, `o_date`, `u_id`, `u_name`, `ta_id`, `t_name`) VALUES
(105, '8PXC6IJA', 'Chocolate venilla', '3.25', '26.78', '8', '3.00', '2024-02-08', 12, 'Parvez Mosarof', NULL, NULL),
(106, 'ZEIURFHT', 'Mojo', '3.00', '9.37', '3', '4.15', '2024-02-08', 12, 'Parvez Mosarof', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment`
--

CREATE TABLE `tbl_payment` (
  `p_id` int(11) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `f_title` varchar(100) NOT NULL,
  `f_disctprice` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `o_quantity` varchar(255) NOT NULL,
  `f_vat` decimal(10,2) NOT NULL,
  `o_date` date NOT NULL,
  `payment_status` varchar(100) NOT NULL,
  `u_id` int(11) NOT NULL,
  `u_name` varchar(255) NOT NULL,
  `ta_id` int(11) NOT NULL,
  `t_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_table`
--

CREATE TABLE `tbl_table` (
  `ta_id` int(11) NOT NULL,
  `t_name` varchar(100) NOT NULL,
  `t_seat` int(11) NOT NULL,
  `t_status` varchar(50) NOT NULL,
  `t_category` varchar(100) NOT NULL,
  `t_id` int(11) DEFAULT NULL,
  `u_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_table`
--

INSERT INTO `tbl_table` (`ta_id`, `t_name`, `t_seat`, `t_status`, `t_category`, `t_id`, `u_name`) VALUES
(32, 'Table 1', 13, 'Enable', 'Booked', 51, 'Parvez Mosarof'),
(34, 'Table 2', 4, 'Enable', 'Regular', NULL, NULL),
(35, 'Table 3', 6, 'Enable', 'Regular', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` int(11) NOT NULL,
  `u_name` varchar(255) NOT NULL,
  `u_contact` varchar(255) NOT NULL,
  `u_email` varchar(255) NOT NULL,
  `u_occ` varchar(255) NOT NULL,
  `u_paddress` varchar(255) NOT NULL,
  `u_dob` date NOT NULL,
  `u_img` text NOT NULL,
  `u_password` varchar(255) NOT NULL,
  `verification_code` varchar(255) NOT NULL,
  `email_verified_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `u_name`, `u_contact`, `u_email`, `u_occ`, `u_paddress`, `u_dob`, `u_img`, `u_password`, `verification_code`, `email_verified_at`) VALUES
(12, 'Parvez Mosarof', '01630411972', 'parvezmosarof195@gmail.com', 'Software Engineer', 'Shibchar, Madaripur', '1998-10-07', '2213337.jpg', '$2y$10$YxXvAiI5zQ.L2GdHgmp12ueHtJBvSRh1X2LQRl7e39/sWuspvFigO', '169022', '2023-12-27 17:50:33'),
(26, 'Parvez', '01630411972', 'example@gmail.com', '', '', '0000-00-00', '', '$2y$10$sgDv1VeLRl1h.4ABhdfNF.BC8J.b2LulJcwRnQWea/2dvCsKDQZjq', '125523', '2024-02-08 18:21:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `tbl_bookedtable`
--
ALTER TABLE `tbl_bookedtable`
  ADD PRIMARY KEY (`t_id`);

--
-- Indexes for table `tbl_catagory`
--
ALTER TABLE `tbl_catagory`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `tbl_contactus`
--
ALTER TABLE `tbl_contactus`
  ADD PRIMARY KEY (`ctus_id`);

--
-- Indexes for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  ADD PRIMARY KEY (`f_id`);

--
-- Indexes for table `tbl_food`
--
ALTER TABLE `tbl_food`
  ADD PRIMARY KEY (`f_id`);

--
-- Indexes for table `tbl_notification`
--
ALTER TABLE `tbl_notification`
  ADD PRIMARY KEY (`n_id`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`o_id`);

--
-- Indexes for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `tbl_table`
--
ALTER TABLE `tbl_table`
  ADD PRIMARY KEY (`ta_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_bookedtable`
--
ALTER TABLE `tbl_bookedtable`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `tbl_catagory`
--
ALTER TABLE `tbl_catagory`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_contactus`
--
ALTER TABLE `tbl_contactus`
  MODIFY `ctus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_food`
--
ALTER TABLE `tbl_food`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_notification`
--
ALTER TABLE `tbl_notification`
  MODIFY `n_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `o_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_table`
--
ALTER TABLE `tbl_table`
  MODIFY `ta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
