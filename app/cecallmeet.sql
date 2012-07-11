-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2012 年 07 月 10 日 14:01
-- 服务器版本: 5.1.56
-- PHP 版本: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `cecallmeet`
--

-- --------------------------------------------------------

--
-- 表的结构 `vitime_admin`
--

CREATE TABLE IF NOT EXISTS `vitime_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `email` varchar(64) DEFAULT NULL COMMENT 'email',
  `mobile` varchar(16) NOT NULL COMMENT '用户手机号码',
  `regtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='系统管理员表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `vitime_admin`
--

INSERT INTO `vitime_admin` (`id`, `username`, `password`, `email`, `mobile`, `regtime`) VALUES
(1, 'admin', '3ceb33aa48c41c64bf4f550f86b2ba3a', 'gaoomei@gmail.com', '13800138001', '2012-04-21 11:54:57');

-- --------------------------------------------------------

--
-- 表的结构 `vitime_company`
--

CREATE TABLE IF NOT EXISTS `vitime_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(64) DEFAULT NULL,
  `company_mark` varchar(16) NOT NULL,
  `onTry` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否在试用期间: 0:非试用期，1：试用期，2：试用期结束',
  `tryDate` date DEFAULT NULL COMMENT '试用过期日',
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_mark` (`company_mark`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- 转存表中的数据 `vitime_company`
--

INSERT INTO `vitime_company` (`id`, `company_name`, `company_mark`, `onTry`, `tryDate`) VALUES
(10, '高鸿', 'gh', 1, '2012-07-09'),
(11, 'ND', 'ND', 0, NULL),
(12, 'ozing公司', 'ozing', 2, '2012-07-07'),
(13, 'web co.,ltd', 'web', 0, NULL),
(14, 'web co.,ltd', 'chinadrtv', 0, NULL),
(15, 'web co.,ltd', 'chinadrtv2', 0, NULL),
(16, '云浮天工', 'yun', 1, '2012-07-09'),
(17, '雨天', 'yutian', 0, NULL),
(18, 'webc', 'webc', 0, NULL),
(19, '雨天', 'aaaa', 1, '2012-07-09');

-- --------------------------------------------------------

--
-- 表的结构 `vitime_getpassword`
--

CREATE TABLE IF NOT EXISTS `vitime_getpassword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `code` char(10) NOT NULL,
  `uptime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `vitime_getpassword`
--


-- --------------------------------------------------------

--
-- 表的结构 `vitime_meeting`
--

CREATE TABLE IF NOT EXISTS `vitime_meeting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT '0',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `title` varchar(64) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `type` char(1) DEFAULT '0' COMMENT '会议类型:0为公共会议，1为企业会议',
  `state` char(1) DEFAULT '1' COMMENT '0：删除，1 可修改，2 锁定',
  `password` varchar(32) DEFAULT NULL,
  `usercount` int(8) NOT NULL DEFAULT '0',
  `time_length` smallint(6) NOT NULL DEFAULT '0' COMMENT '会议时间长度',
  PRIMARY KEY (`id`),
  KEY `start_time` (`start_time`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=81 ;

--
-- 转存表中的数据 `vitime_meeting`
--

INSERT INTO `vitime_meeting` (`id`, `company_id`, `user_id`, `title`, `start_time`, `end_time`, `type`, `state`, `password`, `usercount`, `time_length`) VALUES
(1, 0, 24, '测试会议', '2012-04-11 16:25:50', '2012-04-11 16:25:53', '0', NULL, NULL, 0, 0),
(2, 0, 15, 'tw测试会议', '2012-04-12 16:11:47', '2012-04-12 16:11:50', '0', NULL, NULL, 22, 0),
(3, 0, 15, 'tw测试会议1', '2012-04-12 16:11:47', '2012-04-12 16:11:50', '0', NULL, NULL, 22, 0),
(4, 0, 15, '啊啊啊啊啊啊啊啊', '2012-04-12 16:20:01', '2012-04-12 16:20:03', '0', NULL, NULL, 11, 0),
(5, 0, 15, '巴巴爸爸', '2012-04-12 16:20:38', '2012-04-12 16:20:41', '0', NULL, NULL, 33, 0),
(6, 0, 15, '淡淡的', '2012-04-12 16:22:09', '2012-04-12 16:22:10', '0', NULL, NULL, 44, 0),
(7, 0, 15, '刚刚刚', '2012-04-12 16:24:30', '2012-04-12 16:24:32', '0', NULL, NULL, 33, 0),
(8, 0, 15, '11111', '2012-04-12 16:25:55', '2012-04-12 16:25:57', '0', NULL, NULL, 11, 0),
(9, 0, 15, '11111', '2012-04-12 16:25:55', '2012-04-12 16:25:57', '0', NULL, NULL, 11, 0),
(10, 0, 15, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', NULL, NULL, 0, 0),
(11, 10, 16, '企业会议', '2012-04-12 17:06:50', '2012-04-12 17:06:54', '0', NULL, NULL, 0, 0),
(12, 10, 16, '2222222', '2012-04-12 17:10:25', '2012-04-12 17:10:27', '0', NULL, NULL, 0, 0),
(13, 10, 16, '33333333', '2012-04-12 17:10:52', '2012-04-12 17:10:54', '0', NULL, NULL, 0, 0),
(14, 10, 16, '444', '2012-04-12 17:12:19', '2012-04-12 17:12:21', '0', NULL, NULL, 0, 0),
(15, 0, 16, '11111', '2012-04-12 17:14:21', '2012-04-12 17:14:23', '0', NULL, '33333333', 33, 0),
(16, 10, 16, '444444', '2012-04-12 17:14:51', '2012-04-12 17:14:53', '0', NULL, NULL, 0, 0),
(17, 10, 16, '11111', '2012-04-12 17:16:23', '2012-04-12 17:16:25', '0', NULL, NULL, 0, 0),
(18, 10, 16, '222222222', '2012-04-12 17:17:32', '2012-04-12 17:17:34', '0', NULL, NULL, 0, 0),
(19, 10, 16, '3333333333', '2012-04-12 17:25:01', '2012-04-12 17:25:03', '0', NULL, NULL, 0, 0),
(20, 10, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(21, 0, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(22, 10, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(23, 10, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(24, 10, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(25, 10, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(26, 10, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(27, 10, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(28, 10, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(29, 0, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(30, 0, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(31, 10, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(32, 10, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(33, 10, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(34, 10, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(35, 10, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(36, 10, 16, '22222222', '2012-04-12 17:25:25', '2012-04-12 17:25:27', '0', NULL, NULL, 0, 0),
(37, 10, 16, '333333333', '2012-04-12 17:37:53', '2012-04-12 17:37:55', '0', NULL, NULL, 0, 0),
(38, 10, 16, '333333333', '2012-04-12 17:37:53', '2012-04-12 17:37:55', '0', NULL, NULL, 0, 0),
(39, 10, 16, '333333333', '2012-04-12 17:37:53', '2012-04-12 17:37:55', '0', NULL, NULL, 0, 0),
(40, 10, 16, '333333333', '2012-04-12 17:37:53', '2012-04-12 17:37:55', '0', NULL, NULL, 0, 0),
(41, 10, 16, '333333333', '2012-04-12 17:37:53', '2012-04-12 17:37:55', '0', NULL, NULL, 0, 0),
(42, 10, 16, '333333333', '2012-04-12 17:37:53', '2012-04-12 17:37:55', '0', NULL, NULL, 0, 0),
(43, 10, 16, '333333333', '2012-04-12 17:37:53', '2012-04-12 17:37:55', '0', NULL, NULL, 0, 0),
(44, 10, 16, '333333333', '2012-04-12 17:37:53', '2012-04-12 17:37:55', '0', NULL, NULL, 0, 0),
(45, 10, 16, '1111111111', '2012-04-12 17:48:44', '2012-04-12 17:48:46', '0', NULL, '123', 0, 0),
(46, 10, 16, '222222222', '2012-04-12 17:49:00', '2012-04-12 17:49:01', '0', NULL, NULL, 0, 0),
(47, 10, 16, '11111111', '2012-04-12 18:01:03', '2012-04-12 18:01:05', '0', NULL, NULL, 0, 0),
(48, 12, 26, 'test测试会议', '2012-04-25 08:00:00', '2012-04-25 09:00:00', '1', '1', NULL, 2, 60),
(49, 12, 31, '明日产品发布会', '1970-01-01 08:33:32', '1970-01-01 08:33:32', '1', '0', NULL, 1, 0),
(50, 12, 31, '明天项目上线', '2012-04-26 17:00:00', '2012-04-26 17:40:00', '1', '1', NULL, 2, 40),
(51, 12, 31, '明天开会', '2012-04-24 16:40:00', '2012-04-24 17:20:00', '1', '1', NULL, 1, 40),
(52, 12, 31, '明天开二次会议', '2012-04-24 13:35:00', '2012-04-24 15:05:00', '1', '1', NULL, 1, 90),
(53, 12, 31, '预约公共会议', '2012-05-11 21:20:00', '2012-05-11 21:20:00', '0', '1', '123', 12, 0),
(54, 12, 31, '明日产品发布会', '2012-05-15 18:25:00', '2012-05-15 19:45:00', '1', '1', NULL, 2, 80),
(55, 12, 26, '测试会议发布', '2012-05-03 19:00:00', '2012-05-03 19:00:00', '1', '0', NULL, 0, 0),
(56, 12, 26, 'gray来测评', '2012-05-10 09:00:00', '2012-05-10 09:55:00', '1', '0', NULL, 0, 55),
(57, 12, 26, '测评2', '2012-05-10 08:00:00', '2012-05-10 08:30:00', '1', '0', NULL, 0, 30),
(58, 12, 26, '测评3', '2012-05-10 10:00:00', '2012-05-10 10:20:00', '1', '1', NULL, 1, 20),
(59, 12, 26, '测评3', '2012-05-10 12:00:00', '2012-05-10 12:20:00', '1', '1', NULL, 1, 20),
(60, 12, 26, '测评4', '2012-05-10 11:00:00', '2012-05-10 11:40:00', '1', '1', NULL, 0, 40),
(61, 12, 26, '测评6', '2012-05-10 12:00:00', '2012-05-10 12:40:00', '1', '1', NULL, 0, 40),
(62, 12, 26, '测评', '2012-05-10 17:00:00', '2012-05-10 17:30:00', '1', '1', NULL, 0, 30),
(63, 12, 26, '测评7', '2012-05-11 20:00:00', '2012-05-11 21:00:00', '1', '1', NULL, 3, 60),
(64, 12, 26, '测试邮件发送', '2012-06-28 00:00:00', '2012-06-28 00:30:00', '0', '1', '1234=', 12, 30),
(65, 12, 26, '测试', '2012-06-23 10:00:00', '2012-06-23 12:00:00', '1', '1', NULL, 4, 120),
(66, 12, 29, '测试会议', '2012-06-27 10:00:00', '2012-06-27 11:40:00', '1', '1', NULL, 1, 100),
(67, 12, 29, '测试公共会议', '2012-06-25 14:00:00', '2012-06-25 14:30:00', '0', '1', '123', 10, 30),
(68, 12, 26, '测试会议', '2012-06-30 22:20:00', '2012-07-01 00:00:00', '1', '1', NULL, 2, 100),
(69, 12, 26, '会议发布测试', '2012-06-26 08:40:00', '2012-06-26 10:10:00', '1', '1', NULL, 1, 90),
(70, 12, 26, '再次测试短信发送', '2012-06-26 14:15:00', '2012-06-26 15:15:00', '1', '1', NULL, 1, 60),
(71, 12, 33, '我的会议', '2012-06-27 16:00:00', '2012-06-27 16:10:00', '1', '1', NULL, 2, 10),
(72, 12, 33, '我的公共会议室', '2012-06-27 00:00:00', '2012-06-27 00:10:00', '0', '1', '1234=', 10, 10),
(73, 12, 26, '测试', '2012-06-28 08:00:00', '2012-06-28 10:00:00', '0', '1', '123', 4, 120),
(74, 12, 33, '这是公共会议', '2012-07-10 22:00:00', '2012-07-10 22:10:00', '0', '1', '123', 4, 10),
(75, 12, 33, '公共会议', '2012-07-10 22:00:00', '2012-07-10 22:40:00', '0', '1', '123', 5, 40),
(76, 12, 26, '预约', '2012-06-29 10:00:00', '2012-06-29 10:01:00', '0', '1', '1234', 2, 1),
(77, 12, 26, '测试选择用户', '2012-07-26 12:00:00', '2012-07-26 13:30:00', '1', '1', NULL, 2, 90),
(78, 12, 26, '阿萨德发', '2012-07-12 00:00:00', '2012-07-12 00:10:00', '1', '1', NULL, 0, 10),
(79, 12, 26, '我的公共会议室', '2012-07-26 17:00:00', '2012-07-26 19:03:00', '1', '1', NULL, 0, 123),
(80, 12, 26, 'cssfasdf', '2012-07-26 15:00:00', '2012-07-26 15:12:00', '1', '1', NULL, 0, 12);

-- --------------------------------------------------------

--
-- 表的结构 `vitime_meeting_userlog`
--

CREATE TABLE IF NOT EXISTS `vitime_meeting_userlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meet_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `date` timestamp NULL DEFAULT NULL COMMENT '如果该字段为空，则说明该人员没有参加这场会议',
  PRIMARY KEY (`meet_id`,`user_id`),
  UNIQUE KEY `userlog_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=106 ;

--
-- 转存表中的数据 `vitime_meeting_userlog`
--

INSERT INTO `vitime_meeting_userlog` (`id`, `meet_id`, `user_id`, `date`) VALUES
(7, 48, 26, NULL),
(6, 48, 29, NULL),
(8, 50, 26, NULL),
(9, 50, 29, NULL),
(4, 51, 29, NULL),
(12, 52, 26, NULL),
(49, 54, 26, NULL),
(48, 54, 29, NULL),
(50, 58, 29, NULL),
(51, 59, 29, NULL),
(54, 63, 29, NULL),
(53, 63, 32, NULL),
(52, 63, 33, NULL),
(58, 65, 26, NULL),
(56, 65, 29, NULL),
(57, 65, 32, NULL),
(55, 65, 33, NULL),
(86, 66, 26, NULL),
(101, 68, 26, NULL),
(100, 68, 29, NULL),
(63, 69, 26, NULL),
(91, 70, 33, NULL),
(105, 71, 26, NULL),
(104, 71, 29, NULL),
(103, 77, 34, NULL),
(102, 77, 35, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `vitime_member`
--

CREATE TABLE IF NOT EXISTS `vitime_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(24) DEFAULT NULL COMMENT '用户名称',
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(32) DEFAULT NULL,
  `company_id` varchar(16) NOT NULL DEFAULT '' COMMENT '公司ID',
  `v_money` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `mobile` varchar(11) DEFAULT NULL,
  `email` varchar(32) DEFAULT NULL,
  `priority` char(1) DEFAULT '0' COMMENT '优先级，1 企业管理与员，2 企业普通员工',
  `status` char(1) DEFAULT '1' COMMENT '1：正常用户，0：非正常用户，3：未激活',
  `create_time` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`username`,`company_id`),
  UNIQUE KEY `member_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- 转存表中的数据 `vitime_member`
--

INSERT INTO `vitime_member` (`id`, `name`, `username`, `password`, `company_id`, `v_money`, `mobile`, `email`, `priority`, `status`, `create_time`) VALUES
(16, NULL, 'add1', 'eca3f2a96c228f9015ccfd4ae6f7ebda', '10', 0.00, '13609501926', 'add1@qq.com', '2', NULL, '1334045904'),
(17, NULL, 'add2', 'e7d12b0c17d58b491706fdada281b4a7', '10', 0.00, '13555555555', 'add2@qq.com', '2', NULL, '1334046026'),
(18, NULL, 'add3', '98d8ac32d54d6cc22c3af07afa06897a', '10', 0.00, '1222222222', 'add3@qq.com', '2', NULL, '1334046390'),
(19, NULL, 'add4', 'ba74da4320275b751517a55bc0cbea9c', '10', 0.00, '123333333', 'add4@qq.com', '2', NULL, '1334046504'),
(20, NULL, 'add5', 'b4dc0bda68e29c85195eda73391613ae', '10', 0.00, '1111111111', 'add5@qq.com', '2', NULL, '1334046650'),
(21, NULL, 'add6', '2840cabb54a08bb8f55c638832d6f347', '10', 0.00, '222222222', 'add6@qq.com', '2', NULL, '1334046869'),
(22, NULL, 'add7', 'a6be4bc70b254219249aa1eee6a8887e', '10', 0.00, '15555555', 'add7@qq.com', '2', NULL, '1334046956'),
(30, NULL, 'faiinlove', '9631efa73051a3064e93c912b3bbf0ed', '15', 0.00, '13800138000', 'web@qq.2com', '1', '0', '1335071487'),
(26, '刘大头', 'gray', '65c95a2b334dfdf77ed9a9a636c81ec3', '12', 40.00, '15814476752', 'gaoomei@gmail.com', '1', '1', '2012-04-21 16:15'),
(31, '劳力', 'laoli', '6bfee2faee1e94b7cbd068cfb7a386b0', '12', 0.00, '13800138000', 'adfad@adfa.com', '2', '0', '1335074076'),
(29, '老刘', 'laoliu2', '02afa8e8922d085f2ebfbcd8d1f5195c', '12', 0.00, '13800138000', 'laoliu@qq.com', '2', '1', '1335022340'),
(32, '老刘3', 'laoliu3', 'd8fe2fe332c3511dd56eeb11d1a978c6', '12', 0.00, '13800138000', 'adfad@adfa.com', '2', '1', '1336580635'),
(33, '老刘5', 'laoliu5', 'cc1b9c7e528058d31fa7d765dadada9d', '12', 870.00, '13800138000', 'web@qq.com', '2', '1', '1336583991'),
(39, NULL, 'lasdij', 'a6474b38463960cfbbeb861381642a90', '19', 0.00, '13800138000', 'faiinlove@qq.com', '1', '1', '1340896536'),
(38, NULL, 'lei', '5b84449647fe52c40958ce4552ccc5cc', '18', 0.00, '13800138000', 'web@qq.com', '1', '1', '1340896270'),
(34, '廖总', 'liaoz', 'ce01a464717eec03a4eeaf35289e1c32', '12', 0.00, '13800138000', '13800@qq.com', '2', '1', '1340807834'),
(35, '廖总2', 'liaoz2', 'fb1363d007c3a1f136d208fd9f84c5e6', '12', 0.00, '13800138001', 'web@qq.2com', '2', '1', '1340817677'),
(28, NULL, 'liu', '393bec92acd3f546298273ba3a0ce007', '14', 0.00, '13800138000', 'web@qq.com', '0', NULL, '1335001611'),
(36, NULL, 'liuyunf', 'f9f0d20354e858c4b97574772d7b45ee', '16', 0.00, '13800138000', 'faiinlove@qq.com', '1', '1', '1340888904'),
(25, NULL, 'test4', '86985e105f79b95d6bc918fb45ec7727', '10', 0.00, '44444444', 'test4@qq.com', '2', NULL, '1334133058'),
(15, NULL, 'tw198611', 'fbe990e973f1ee9732c5226f9a6ae78f', '10', 0.00, '13609501926', 'tw198611@163.com', '1', NULL, '1334023459'),
(23, NULL, 'tww', 'fc89965d47de0e136dc0d29e7e19c2ba', '11', 0.00, '11111111', 'tww@qq.com', '1', NULL, '1334131799'),
(24, NULL, 'tww1', '4fec87a124146259a4d2aba210a197c3', '11', 0.00, '11111111', 'tww1@qq.com', '2', NULL, '1334131856'),
(27, NULL, 'web', 'b14f67117248667af5de3ec19a38ed8e', '13', 0.00, '13800138000', 'web@qq.com', '0', NULL, '1335000359'),
(37, NULL, 'yunfeng', 'f540b08ea293ddc9ea7a81e17fe4ac80', '17', 0.00, '13800138000', 'faiinlove@qq.com', '1', '1', '1340890095');

-- --------------------------------------------------------

--
-- 表的结构 `vitime_member_emailset`
--

CREATE TABLE IF NOT EXISTS `vitime_member_emailset` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `member_id` int(11) NOT NULL COMMENT '用户id',
  `email` varchar(128) NOT NULL COMMENT '邮箱帐号',
  `password` varchar(64) NOT NULL COMMENT '邮箱密码',
  `smtp` varchar(128) NOT NULL COMMENT 'smtp地址',
  `port` smallint(6) NOT NULL COMMENT 'smtp端口',
  `is_ssl` char(1) NOT NULL DEFAULT '0' COMMENT '是否ssl模式',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户发送邮件帐号密码设置' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `vitime_member_emailset`
--

INSERT INTO `vitime_member_emailset` (`id`, `member_id`, `email`, `password`, `smtp`, `port`, `is_ssl`) VALUES
(1, 26, 'i_cecall_cc@163.com', 'i_cecall_cc123', 'smtp.163.com', 25, '0'),
(2, 29, '', '', '', 25, '0'),
(3, 33, 'i.cecall.cc', 'i.cecall.cc123', 'smtp.qq.com', 25, '0');

-- --------------------------------------------------------

--
-- 表的结构 `vitime_member_monthpay`
--

CREATE TABLE IF NOT EXISTS `vitime_member_monthpay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` char(6) NOT NULL COMMENT '年月，已付费年月',
  `member_id` int(11) DEFAULT NULL COMMENT '用户id',
  `pay_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '支付时间',
  `start_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始日期',
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束日期',
  PRIMARY KEY (`id`),
  KEY `month` (`month`,`member_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='企业用户月租记录' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `vitime_member_monthpay`
--

INSERT INTO `vitime_member_monthpay` (`id`, `month`, `member_id`, `pay_time`, `start_date`, `end_date`) VALUES
(1, '201206', 33, '2012-06-28 00:41:19', '2012-06-28 00:41:19', '2012-07-28 00:41:19');

-- --------------------------------------------------------

--
-- 表的结构 `vitime_pay_records`
--

CREATE TABLE IF NOT EXISTS `vitime_pay_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meet_id` int(11) DEFAULT NULL COMMENT '会议id',
  `member_id` int(11) DEFAULT NULL COMMENT '用户id',
  `company_id` int(11) DEFAULT NULL COMMENT '企业id',
  `r_money` decimal(12,2) DEFAULT '0.00' COMMENT '支付金额',
  `pay_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '支付时间',
  `pay_type` varchar(16) DEFAULT NULL COMMENT '1：会议支出，2：月租',
  `status` char(1) DEFAULT NULL,
  `note` varchar(512) DEFAULT NULL COMMENT '备注',
  `month_uid` int(10) unsigned DEFAULT '0' COMMENT '付月租的用户id',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='会议支付记录' AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `vitime_pay_records`
--

INSERT INTO `vitime_pay_records` (`id`, `meet_id`, `member_id`, `company_id`, `r_money`, `pay_time`, `pay_type`, `status`, `note`, `month_uid`) VALUES
(1, 64, 26, 0, 100.00, '2012-06-24 08:51:14', NULL, NULL, NULL, NULL),
(2, NULL, 33, 12, 60.00, '2012-06-28 00:41:19', '2', NULL, NULL, NULL),
(3, NULL, NULL, 12, 60.00, '2012-06-28 01:21:17', '2', NULL, NULL, NULL),
(4, NULL, 26, 12, 10.00, '2012-06-29 01:08:11', '1', NULL, NULL, NULL),
(5, NULL, 33, 12, 10.00, '2012-07-10 20:13:42', '1', NULL, NULL, NULL),
(6, NULL, 33, 12, 10.00, '2012-07-10 20:18:01', '1', NULL, NULL, NULL),
(7, NULL, 33, 12, 10.00, '2012-07-10 21:41:13', '1', NULL, '公共会议支付费用', 0),
(8, NULL, 33, 12, 50.00, '2012-07-10 21:48:25', '1', NULL, '公共会议支付费用', 0),
(9, NULL, 33, 12, 10.00, '2012-07-10 21:57:47', '1', NULL, '编辑公共会议支付费用', 0);

-- --------------------------------------------------------

--
-- 表的结构 `vitime_recharge`
--

CREATE TABLE IF NOT EXISTS `vitime_recharge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `money` double unsigned NOT NULL COMMENT '金额',
  `way` varchar(10) NOT NULL,
  `status` varchar(20) NOT NULL,
  `trade_no` varchar(100) NOT NULL COMMENT '支付宝交易号',
  `note` varchar(20) NOT NULL,
  `uptime` int(10) unsigned NOT NULL,
  `oid` int(10) unsigned NOT NULL COMMENT '关联订单ID',
  `isdeal` tinyint(3) unsigned NOT NULL COMMENT '是否处理',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='充值记录' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `vitime_recharge`
--

INSERT INTO `vitime_recharge` (`id`, `mid`, `money`, `way`, `status`, `trade_no`, `note`, `uptime`, `oid`, `isdeal`) VALUES
(1, 26, 100, 'alipay', '1', '1000000', '', 1341924554, 0, 1),
(2, 26, 100000, 'alipay', '1', '1000000', '', 1341924554, 100001, 1);

-- --------------------------------------------------------

--
-- 表的结构 `vitime_send_email`
--

CREATE TABLE IF NOT EXISTS `vitime_send_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '用户id',
  `contents` text NOT NULL COMMENT '内容',
  `sendtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发送时间',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `vitime_send_email`
--


-- --------------------------------------------------------

--
-- 表的结构 `vitime_send_sms`
--

CREATE TABLE IF NOT EXISTS `vitime_send_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '用户id',
  `mobile` char(12) NOT NULL,
  `contents` varchar(1024) NOT NULL,
  `sendtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sid` int(10) unsigned NOT NULL COMMENT '短信通道返回id',
  PRIMARY KEY (`id`),
  KEY `mobile` (`mobile`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='发送的短信' AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `vitime_send_sms`
--

INSERT INTO `vitime_send_sms` (`id`, `member_id`, `mobile`, `contents`, `sendtime`, `sid`) VALUES
(1, 26, '15814476752', '测试短信', '2012-06-25 22:37:14', 0),
(2, 26, '15814476752', '测试短信', '2012-06-25 22:37:41', 0),
(3, 26, '15814476752', '测试短信', '2012-06-25 22:38:22', 0),
(4, 26, '15814476752', '测试短信', '2012-06-25 22:38:52', 0),
(5, 26, '15814476752,', '37890', '2012-06-25 23:39:10', 0),
(6, 26, '15814476752,', '会议通知：\n主题：测试，开始时间：2012-06-23 10:00:00，时长：120分钟。请准时参加。', '2012-06-25 23:40:22', 37891),
(7, 26, '15814476752,', '会议通知：\n主题：测试，开始时间：2012-06-23 10:00:00，时长：120分钟。请准时参加。', '2012-06-25 23:50:37', 37892),
(8, 26, '13800138000,', '会议通知：\n主题：测试会议，开始时间：2012-06-30 22:20:00，时长：100分钟。请准时参加。', '2012-06-29 01:05:58', 38829);
