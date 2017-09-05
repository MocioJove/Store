-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2017-08-21 01:11:35
-- 服务器版本： 5.7.9
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `store`
--

-- --------------------------------------------------------

--
-- 表的结构 `st_activegoods`
--

DROP TABLE IF EXISTS `st_activegoods`;
CREATE TABLE IF NOT EXISTS `st_activegoods` (
  `activegoodsid` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `goodsinfo` varchar(100) NOT NULL,
  PRIMARY KEY (`activegoodsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_activeinfo`
--

DROP TABLE IF EXISTS `st_activeinfo`;
CREATE TABLE IF NOT EXISTS `st_activeinfo` (
  `activeinfoid` int(11) NOT NULL AUTO_INCREMENT,
  `activeinfo` int(11) NOT NULL,
  PRIMARY KEY (`activeinfoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_aftersales`
--

DROP TABLE IF EXISTS `st_aftersales`;
CREATE TABLE IF NOT EXISTS `st_aftersales` (
  `aftersalesid` int(11) NOT NULL AUTO_INCREMENT,
  `orderformid` int(11) NOT NULL,
  `indentid` int(11) NOT NULL,
  `goodsid` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`aftersalesid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_brand`
--

DROP TABLE IF EXISTS `st_brand`;
CREATE TABLE IF NOT EXISTS `st_brand` (
  `brandid` int(11) NOT NULL AUTO_INCREMENT,
  `brandname` int(11) NOT NULL,
  PRIMARY KEY (`brandid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_car`
--

DROP TABLE IF EXISTS `st_car`;
CREATE TABLE IF NOT EXISTS `st_car` (
  `carid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `goodsid` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY (`carid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_collect`
--

DROP TABLE IF EXISTS `st_collect`;
CREATE TABLE IF NOT EXISTS `st_collect` (
  `collectid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `goodsid` int(11) NOT NULL,
  PRIMARY KEY (`collectid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_comments`
--

DROP TABLE IF EXISTS `st_comments`;
CREATE TABLE IF NOT EXISTS `st_comments` (
  `commentsid` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `comments` varchar(200) NOT NULL,
  `uid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`commentsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_coupons`
--

DROP TABLE IF EXISTS `st_coupons`;
CREATE TABLE IF NOT EXISTS `st_coupons` (
  `couponsid` int(11) NOT NULL AUTO_INCREMENT,
  `coupons` int(11) NOT NULL,
  `couponsinfo` int(11) NOT NULL,
  PRIMARY KEY (`couponsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_discount`
--

DROP TABLE IF EXISTS `st_discount`;
CREATE TABLE IF NOT EXISTS `st_discount` (
  `discountid` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  PRIMARY KEY (`discountid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_goodsinfo`
--

DROP TABLE IF EXISTS `st_goodsinfo`;
CREATE TABLE IF NOT EXISTS `st_goodsinfo` (
  `infoid` int(11) NOT NULL AUTO_INCREMENT,
  `color` char(16) NOT NULL,
  `size` char(16) NOT NULL,
  `number` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`infoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_order`
--

DROP TABLE IF EXISTS `st_order`;
CREATE TABLE IF NOT EXISTS `st_order` (
  `orderid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `adderss` varchar(100) NOT NULL,
  `person` varchar(16) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `ordertime` int(11) NOT NULL,
  `arrivetime` int(11) NOT NULL,
  PRIMARY KEY (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_orderinfo`
--

DROP TABLE IF EXISTS `st_orderinfo`;
CREATE TABLE IF NOT EXISTS `st_orderinfo` (
  `orderinfoid` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `goodsinfo` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY (`orderinfoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_permissions`
--

DROP TABLE IF EXISTS `st_permissions`;
CREATE TABLE IF NOT EXISTS `st_permissions` (
  `permissionsid` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`permissionsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_role`
--

DROP TABLE IF EXISTS `st_role`;
CREATE TABLE IF NOT EXISTS `st_role` (
  `roleid` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`roleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_type`
--

DROP TABLE IF EXISTS `st_type`;
CREATE TABLE IF NOT EXISTS `st_type` (
  `typeid` int(11) NOT NULL AUTO_INCREMENT,
  `type` char(32) NOT NULL,
  PRIMARY KEY (`typeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `st_user`
--

DROP TABLE IF EXISTS `st_user`;
CREATE TABLE IF NOT EXISTS `st_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(20) NOT NULL,
  `mobile` char(11) NOT NULL,
  `password` char(32) NOT NULL,
  `agree` tinyint(2) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `st_user`
--

INSERT INTO `st_user` (`uid`, `username`, `mobile`, `password`, `agree`) VALUES
(1, 'aaaa', '15863074925', '3ce65df4849a71820f7499d08fddb5b2', 1),
(2, '你好啊', '15863074925', '3ce65df4849a71820f7499d08fddb5b2', 1),
(3, 'wwww', '15863074925', '3ce65df4849a71820f7499d08fddb5b2', 1),
(4, '多对多', '15863074924', 'd785c99d298a4e9e6e13fe99e602ef42', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
