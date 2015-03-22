-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Mar 22, 2015 at 08:55 AM
-- Server version: 5.5.34
-- PHP Version: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `email` (`email`),
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'MEMBER' COMMENT 'SUPER_ADMIN, ADMIN, MEMBER',
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'admin', 'admin@testapp.com', 'ADMIN', '86266ee937d97f812a8e57d22b62ee29', 'd96514017aa708bea5017d7b0417516f', '2015-03-19 22:54:41', '2015-03-21 00:02:18'),
(3, 'member', 'member@testapp.com', 'MEMBER', 'aa08769cdcb26674c6706093503ff0a3', NULL, '2015-03-20 22:38:52', '2015-03-20 22:38:52'),
(4, 'superadmin', 'superadmin@testapp.com', 'SUPER_ADMIN', '17c4520f6cfd1ab53d8745e84681eb49', NULL, '2015-03-20 22:39:08', '2015-03-20 22:39:08');
