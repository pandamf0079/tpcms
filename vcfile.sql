/*
Navicat MySQL Data Transfer

Source Server         : mysql5.6
Source Server Version : 50628
Source Host           : localhost:3306
Source Database       : vcfile

Target Server Type    : MYSQL
Target Server Version : 50628
File Encoding         : 65001

Date: 2021-06-04 15:08:31
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `vc_admin`
-- ----------------------------
DROP TABLE IF EXISTS `vc_admin`;
CREATE TABLE `vc_admin` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `pwd` varchar(100) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  `salt` varchar(20) DEFAULT NULL,
  `create_time` int(50) DEFAULT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `thumb` varchar(100) DEFAULT NULL,
  `last_login_time` int(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vc_admin
-- ----------------------------
INSERT INTO `vc_admin` VALUES ('1', 'admin', '121c62a126c2375d6d642a8d4c191cc9', '1', 'soQdOjreIB2AKFwv6Lgc', null, '112312312@qq.com', '11010101101', null, '1622625054');
INSERT INTO `vc_admin` VALUES ('2', 'ggbone', '1a34ce28253dcd99e6e9d8a43e497919', '1', 'cGR20lTBWfvHyKg6j9s5', null, '', '12345678112', null, '1622776470');

-- ----------------------------
-- Table structure for `vc_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `vc_auth_group`;
CREATE TABLE `vc_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vc_auth_group
-- ----------------------------
INSERT INTO `vc_auth_group` VALUES ('1', '超级管理员', '1', '1,2,13,10,3,4,11,12,14,15,16,17,18,19');
INSERT INTO `vc_auth_group` VALUES ('3', '文章录入员', '1', '11,12,14,18');

-- ----------------------------
-- Table structure for `vc_auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `vc_auth_group_access`;
CREATE TABLE `vc_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vc_auth_group_access
-- ----------------------------
INSERT INTO `vc_auth_group_access` VALUES ('1', '1');
INSERT INTO `vc_auth_group_access` VALUES ('2', '3');
INSERT INTO `vc_auth_group_access` VALUES ('3', '1');
INSERT INTO `vc_auth_group_access` VALUES ('6', '0');
INSERT INTO `vc_auth_group_access` VALUES ('7', '3');
INSERT INTO `vc_auth_group_access` VALUES ('8', '1');
INSERT INTO `vc_auth_group_access` VALUES ('9', '1');
INSERT INTO `vc_auth_group_access` VALUES ('10', '3');

-- ----------------------------
-- Table structure for `vc_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `vc_auth_rule`;
CREATE TABLE `vc_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) DEFAULT NULL,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vc_auth_rule
-- ----------------------------
INSERT INTO `vc_auth_rule` VALUES ('1', '0', 'admin/Huser/index', '客户列表', '1', '1', '');
INSERT INTO `vc_auth_rule` VALUES ('2', '1', 'admin/Huser/edit_user', '查看客户信息', '1', '1', '');
INSERT INTO `vc_auth_rule` VALUES ('3', '0', 'admin/Hborrow/index', '项目列表查看', '1', '1', '');
INSERT INTO `vc_auth_rule` VALUES ('4', '3', 'admin/Hborrow/edit_borrows', '项目编辑', '1', '1', '');
INSERT INTO `vc_auth_rule` VALUES ('10', '1', 'admin/Hmanage/index', '查看客户经理', '1', '1', '');
INSERT INTO `vc_auth_rule` VALUES ('11', '0', 'admin/Hhetong/index', '合同查看', '1', '1', '');
INSERT INTO `vc_auth_rule` VALUES ('12', '11', 'admin/Hetong/index', '合同下载', '1', '1', '');
INSERT INTO `vc_auth_rule` VALUES ('13', '2', 'Admin/Huser/delete', '删除客户', '1', '1', '');
INSERT INTO `vc_auth_rule` VALUES ('14', '0', 'Admin/index/index', '后台首页', '1', '1', '');
INSERT INTO `vc_auth_rule` VALUES ('15', '0', 'admin/Rulelist/index', '权限管理', '1', '1', '');
INSERT INTO `vc_auth_rule` VALUES ('16', '15', 'admin/Rulegroup/index', '角色管理', '1', '1', '');
INSERT INTO `vc_auth_rule` VALUES ('17', '15', 'admin/Adminlist/index', '管理员列表', '1', '1', '');
INSERT INTO `vc_auth_rule` VALUES ('18', '0', 'admin/Hnews/index', '新闻列表', '1', '1', '');
INSERT INTO `vc_auth_rule` VALUES ('19', '0', 'admin/Hcompany/index', '公司信息', '1', '1', '');

-- ----------------------------
-- Table structure for `vc_borrow`
-- ----------------------------
DROP TABLE IF EXISTS `vc_borrow`;
CREATE TABLE `vc_borrow` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `account` decimal(10,2) DEFAULT NULL,
  `account_other` decimal(10,2) DEFAULT NULL,
  `apr` decimal(5,2) DEFAULT NULL,
  `months` int(10) DEFAULT NULL,
  `limit` decimal(10,0) DEFAULT NULL,
  `qixianleixing` int(10) DEFAULT NULL COMMENT '期限类型',
  `yejibijiao` varchar(200) DEFAULT NULL,
  `risk_level` int(10) DEFAULT NULL,
  `allow_member` int(10) DEFAULT NULL,
  `apr_start` int(10) DEFAULT NULL,
  `style` int(10) DEFAULT NULL,
  `intro` text,
  `mid` int(5) DEFAULT NULL,
  `addtime` int(20) DEFAULT NULL,
  `addip` varchar(30) DEFAULT NULL,
  `status` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vc_borrow
-- ----------------------------
INSERT INTO `vc_borrow` VALUES ('1', '橘子计划1期', '1000000.00', null, '10.80', '6', '100000', '1', null, '1', '1', '1', '1', '1', '1', '1617072386', null, null);
INSERT INTO `vc_borrow` VALUES ('2', '房产包销0期', '2000000.00', null, '9.80', '12', '50000', '1', null, null, null, null, null, null, '1', '1617052386', null, null);
INSERT INTO `vc_borrow` VALUES ('3', '23232', '1000000.00', null, '11.40', '12', '50000', null, null, null, null, null, '1', 'sdfasdfasdfasdfasdf', '1', null, null, null);
INSERT INTO `vc_borrow` VALUES ('6', '221341234', '23234234.00', null, '22.00', '22', '222', null, null, null, null, null, '1', '2222', '2', '1617244095', null, null);
INSERT INTO `vc_borrow` VALUES ('7', '8888888111111111111111', '10000.00', null, '0.00', '0', '0', null, null, null, null, null, '0', '', '1', '1617245463', null, null);
INSERT INTO `vc_borrow` VALUES ('8', '3456345634563456', '10000000.00', null, '12.00', '6', '123', null, null, null, null, null, '0', '', '1', '1617249902', null, '2');
INSERT INTO `vc_borrow` VALUES ('9', '橘子房产包销计划5期', '10000.00', null, '10.00', '12', '500000', null, null, null, null, null, '1', 'sdfsdfgsdfgsdgsdfgsdfgsdfgsdfgsdfgsdfgsdfg', '1', '1617250470', null, '2');
INSERT INTO `vc_borrow` VALUES ('10', '京云链计划', '100000.00', null, '10.50', '22', '3000', null, null, null, null, null, '1', '111111111111111111', '1', '1617251827', null, '2');
INSERT INTO `vc_borrow` VALUES ('11', '橘子房产包销计划6期', '150000.00', null, '10.00', '12', '100000', '1', null, null, null, null, '2', '22222222', '1', '1617252936', null, '1');

-- ----------------------------
-- Table structure for `vc_borrow_tender`
-- ----------------------------
DROP TABLE IF EXISTS `vc_borrow_tender`;
CREATE TABLE `vc_borrow_tender` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `money` decimal(10,2) DEFAULT NULL,
  `bid` int(10) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `addtime` int(20) DEFAULT NULL,
  `addip` varchar(0) DEFAULT NULL,
  `manager` int(10) DEFAULT NULL,
  `remark` varchar(200) DEFAULT NULL,
  `appoint_time` varchar(100) DEFAULT NULL,
  `status` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vc_borrow_tender
-- ----------------------------
INSERT INTO `vc_borrow_tender` VALUES ('1', '90000.00', '0', '6', '1619515027', null, null, '', '2021-04-27', '1');
INSERT INTO `vc_borrow_tender` VALUES ('2', '200000.00', '9', '6', '1619579460', null, null, '', '2021-04-28', '1');
INSERT INTO `vc_borrow_tender` VALUES ('3', '100000.00', '11', '6', '1619590830', null, null, '', '2021-04-28', '1');
INSERT INTO `vc_borrow_tender` VALUES ('4', '70000.00', '11', '6', '1619683101', null, null, '', '2021-04-29', '1');
INSERT INTO `vc_borrow_tender` VALUES ('5', '50000.00', '11', '6', '1620455055', null, null, '', '2021-05-08', '1');
INSERT INTO `vc_borrow_tender` VALUES ('6', '50000.00', '11', '6', '1620455952', null, null, '', '2021-05-08', '1');
INSERT INTO `vc_borrow_tender` VALUES ('7', '10000.00', '11', '6', '1620612744', null, null, '', '2021-05-10', '0');

-- ----------------------------
-- Table structure for `vc_company`
-- ----------------------------
DROP TABLE IF EXISTS `vc_company`;
CREATE TABLE `vc_company` (
  `id` int(20) NOT NULL DEFAULT '0',
  `company_name` varchar(100) DEFAULT NULL,
  `company_no` varchar(100) DEFAULT NULL,
  `company_faren` varchar(50) DEFAULT NULL,
  `company_tel` varchar(50) DEFAULT NULL,
  `company_intro` text,
  `company_address` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vc_company
-- ----------------------------
INSERT INTO `vc_company` VALUES ('1', '深圳新域资产管理有限公司1', 'hb8888888881', '邹玉春1', '0750-288888881', '这里是公司介绍1', '广东省深圳罗湖特区腾讯写字楼11号1');

-- ----------------------------
-- Table structure for `vc_hetong`
-- ----------------------------
DROP TABLE IF EXISTS `vc_hetong`;
CREATE TABLE `vc_hetong` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `hetong_name` varchar(50) DEFAULT NULL,
  `status` int(5) DEFAULT NULL,
  `account_id` varchar(150) DEFAULT NULL,
  `token` varchar(150) DEFAULT NULL,
  `uid` int(20) DEFAULT NULL,
  `download` varchar(150) DEFAULT NULL,
  `mid` int(10) DEFAULT NULL,
  `addtime` int(100) DEFAULT NULL,
  `flowid` varchar(150) DEFAULT NULL,
  `expire_time` int(100) DEFAULT NULL,
  `bid` int(20) DEFAULT NULL,
  `yid` int(20) DEFAULT NULL,
  `ymoney` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vc_hetong
-- ----------------------------
INSERT INTO `vc_hetong` VALUES ('1', 'dfgdfgdfg', '2', null, null, '6', null, '1', '1619060658', null, null, '1', null, null);
INSERT INTO `vc_hetong` VALUES ('2', null, '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6491619338636.pdf', null, '1619338640', 'd0900262bc8149c9acb1cdf36652cbb1', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('3', '橘子计划1期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6221619339653.pdf', null, '1619339657', '6ffdf4c842ec4120bab41b0d6c347cb2', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('4', '橘子计划1期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6251619339881.pdf', null, '1619339884', '287ccbeef97f4de7861b6caaa1f23383', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('5', '橘子计划1期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6841619340113.pdf', null, '1619340118', 'ee58ae458c854beeaecff56231a51da6', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('6', '橘子计划1期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6321619341257.pdf', null, '1619341261', '3d2f70250e484816a9bf2b157809be79', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('7', '橘子计划1期', '1', '5504f72bcdc149dc81dd9425c2e0e82f', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiJjOGVlODM4M2YzNzM0YmZlOTZlYTg1ZmVmZDQ5ODc3MCIsImFwcElkIjoiNzQzODg1MzY1NiIsIm9JZCI6ImFkYTkwNTUyZDEwZjQwM', '6', './useragreement/6131619341309.pdf', null, '1619341313', '14e1552528be4e6cb7fd80a057175ed2', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('8', '橘子计划1期', '1', '5504f72bcdc149dc81dd9425c2e0e82f', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiJjOGVlODM4M2YzNzM0YmZlOTZlYTg1ZmVmZDQ5ODc3MCIsImFwcElkIjoiNzQzODg1MzY1NiIsIm9JZCI6ImFkYTkwNTUyZDEwZjQwM', '6', './useragreement/6361619341474.pdf', null, '1619341479', 'cc1ddd68dff74329ad69028bd7ac02fb', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('9', '橘子计划1期', '1', '5504f72bcdc149dc81dd9425c2e0e82f', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiJjOGVlODM4M2YzNzM0YmZlOTZlYTg1ZmVmZDQ5ODc3MCIsImFwcElkIjoiNzQzODg1MzY1NiIsIm9JZCI6ImFkYTkwNTUyZDEwZjQwM', '6', './useragreement/6911619341689.pdf', null, '1619341693', 'a42f31eafb2649c1869fa1a91c4cf6c0', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('10', '橘子计划1期', '1', '5504f72bcdc149dc81dd9425c2e0e82f', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiJjOGVlODM4M2YzNzM0YmZlOTZlYTg1ZmVmZDQ5ODc3MCIsImFwcElkIjoiNzQzODg1MzY1NiIsIm9JZCI6ImFkYTkwNTUyZDEwZjQwM', '6', './useragreement/6631619341830.pdf', null, '1619341834', '339e9a57d57a459a915136744e5a4ec7', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('11', '橘子计划1期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6501619342498.pdf', null, '1619342502', '45b27fe23a4643fe8158c7e87289d115', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('12', '橘子计划1期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6481619342593.pdf', null, '1619342597', '3c841632673141858b8ce148fa0165cc', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('13', '橘子计划1期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6261619342670.pdf', null, '1619342674', 'e2d1380235854004b6061f8e3a4b8c74', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('14', '橘子计划1期', '1', '5504f72bcdc149dc81dd9425c2e0e82f', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiJjOGVlODM4M2YzNzM0YmZlOTZlYTg1ZmVmZDQ5ODc3MCIsImFwcElkIjoiNzQzODg1MzY1NiIsIm9JZCI6ImFkYTkwNTUyZDEwZjQwM', '6', './useragreement/681619342736.pdf', null, '1619342740', 'c93d4aa066f84507aed35e0a199c7ca2', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('15', '橘子房产包销计划6期', '1', null, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6991620268604.pdf', null, '1620268606', null, null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('16', '橘子房产包销计划6期', '1', null, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6241620268999.pdf', null, '1620269000', null, null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('17', '橘子房产包销计划6期', '1', null, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6311620269267.pdf', null, '1620269269', null, null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('18', '橘子房产包销计划6期', '1', null, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6321620269327.pdf', null, '1620269329', null, null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('19', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/631620269836.pdf', null, '1620269841', '049024d3eb3f411fa9643d82a6e5b739', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('20', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6841620272867.pdf', null, '1620272873', '4057d66ad9394017b2ab7688e18b79b0', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('21', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6491620282298.pdf', null, '1620282303', 'b8b075157c8147b6a56885674653a49d', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('22', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6531620286237.pdf', null, '1620286242', '81197aac9a1642b4b8b7567013bc695a', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('23', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6951620286926.pdf', null, '1620286931', '0fef54068d3246fab7f43b336d9c8955', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('24', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6431620288288.pdf', null, '1620288294', '0eed7188921f4f56a5bf3979169de8db', null, null, null, null);
INSERT INTO `vc_hetong` VALUES ('25', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6211620289803.pdf', null, '1620289808', '73f696d7b72740a38f87bcadb8b1eacd', null, '11', null, null);
INSERT INTO `vc_hetong` VALUES ('26', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6941620291656.pdf', null, '1620291660', '709fe56f902a4cb6a96036c71b2efbe7', null, '11', null, null);
INSERT INTO `vc_hetong` VALUES ('27', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6851620292380.pdf', null, '1620292385', 'ff639fc9b12e4c2e8d26ac49e76a2314', null, '11', null, null);
INSERT INTO `vc_hetong` VALUES ('28', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6931620293841.pdf', null, '1620293846', '4b44c13e25ac4e21a2b675e252465511', null, '11', null, null);
INSERT INTO `vc_hetong` VALUES ('29', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6541620293983.pdf', null, '1620293988', 'a388cc67230542c193ea47ee81c3ef2a', null, '11', '4', null);
INSERT INTO `vc_hetong` VALUES ('30', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6741620368261.pdf', '1', '1620368266', '43f28dddbfde4c7bab02e285cc5c1e8a', null, '11', '4', null);
INSERT INTO `vc_hetong` VALUES ('31', '橘子房产包销计划6期', '1', null, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6971620380844.pdf', '1', '1620380846', null, null, '11', '4', null);
INSERT INTO `vc_hetong` VALUES ('32', '橘子房产包销计划6期', '1', null, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6291620380939.pdf', '1', '1620380941', null, null, '11', '4', null);
INSERT INTO `vc_hetong` VALUES ('33', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6271620381118.pdf', '1', '1620381123', '3c8292e36a9c412d8d987a457a93ecd1', null, '11', '4', null);
INSERT INTO `vc_hetong` VALUES ('34', '橘子房产包销计划6期', '2', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6611620439573.pdf', '1', '1620439578', '4c8d3dceeae64e099282d9dfa4c4bf95', null, '11', '4', null);
INSERT INTO `vc_hetong` VALUES ('35', '橘子房产包销计划6期', '2', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6461620455073.pdf', '1', '1620455078', '19298820cd1b4e76970a701fe515cdd9', null, '11', '5', null);
INSERT INTO `vc_hetong` VALUES ('36', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6591620455965.pdf', '1', '1620455971', '0cbd4b510dc94ba683ec3acda83e4f51', null, '11', '6', null);
INSERT INTO `vc_hetong` VALUES ('37', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6861620456565.pdf', '1', '1620456570', '25afc6ab510d450abcbcbce8c6e035e9', null, '11', '6', null);
INSERT INTO `vc_hetong` VALUES ('38', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6831620456968.pdf', '1', '1620456973', '2f57262f6c184b2a8d1cd2f10d8583be', null, '11', '6', null);
INSERT INTO `vc_hetong` VALUES ('39', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6141620457116.pdf', '1', '1620457121', 'ea768b13b6f34001b21452509970436e', null, '11', '6', null);
INSERT INTO `vc_hetong` VALUES ('40', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/621620457283.pdf', '1', '1620457288', '85fc33ec3c734b4d881884c70fb1313d', null, '11', '6', null);
INSERT INTO `vc_hetong` VALUES ('41', '橘子房产包销计划6期', '2', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/complete/16204588765106.pdf', '1', '1620458676', 'aa868afec9584080b5b057c39293ccf7', null, '11', '6', null);
INSERT INTO `vc_hetong` VALUES ('42', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6701620612758.pdf', '1', '1620612765', '5e4e34733f0e47408e4bc39228a95d15', null, '11', '7', null);
INSERT INTO `vc_hetong` VALUES ('43', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6391620613364.pdf', '1', '1620613370', '08b7d35de2e74a55bb0714c3fd13ad75', null, '11', '7', null);
INSERT INTO `vc_hetong` VALUES ('44', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6671620613597.pdf', '1', '1620613602', '38f7be55c22a40ae9ce1bd2b98df7f08', null, '11', '7', null);
INSERT INTO `vc_hetong` VALUES ('45', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6701620613789.pdf', '1', '1620613794', 'a8c3481013614b8d86c1f025b0f15fa3', null, '11', '7', null);
INSERT INTO `vc_hetong` VALUES ('46', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6931620615590.pdf', '1', '1620615597', '0bbc01f3aa10428b952ca0e6f79ca9bc', null, '11', '7', null);
INSERT INTO `vc_hetong` VALUES ('47', '橘子房产包销计划6期', '1', '22efc7def5c940f4a7bef4e4623a0c6c', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6211620618722.pdf', '1', '1620618729', '7b6d4d3a5dd34e7eac1e533b1eb99ec3', null, '11', '7', null);
INSERT INTO `vc_hetong` VALUES ('48', '橘子房产包销计划6期', '1', null, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiIzYzhkYjYzYTRmNzA0MTliYjdjOGU3MTFkOWZkM2ZlMyIsImFwcElkIjoiNzQzODg1MjA5OCIsIm9JZCI6ImIyMjA4MjRkZTEwNzQ0O', '6', './useragreement/6511620805746.pdf', '1', '1620805749', null, null, '11', '7', null);

-- ----------------------------
-- Table structure for `vc_news`
-- ----------------------------
DROP TABLE IF EXISTS `vc_news`;
CREATE TABLE `vc_news` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `tags` varchar(50) DEFAULT NULL,
  `summary` varchar(150) DEFAULT NULL,
  `content` text,
  `author` varchar(100) DEFAULT NULL,
  `hits` int(20) NOT NULL DEFAULT '0',
  `addtime` int(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vc_news
-- ----------------------------
INSERT INTO `vc_news` VALUES ('1', '进击的巨人什么时候更新', null, '4月9号迎来进击巨人更新1..', '<p style=\"text-align: center; \">进击的巨人烂尾了吗</p><p style=\"text-align: left;\"><b>fgsdfgsdfgsdf</b>gsdfgsdfg</p><p style=\"text-align: left;\">sdgsdfgsd<a target=\"_self\" href=\"http://www.baidu.com\">fgsdfg</a></p><p>99999</p>', '谏山创1', '0', '1617865274');
INSERT INTO `vc_news` VALUES ('3', 'testtesttesttesttesttest', null, 'asadasdsd', '<p><strike>asdasdfsadfsadfasdfasf</strike></p><p><img src=\"\\upfiles\\20210602\\ae00d6388047604b78c72d9795cd852e.jpg\" alt=\"\"><br></p>', 'peng', '0', '1617867236');

-- ----------------------------
-- Table structure for `vc_sys`
-- ----------------------------
DROP TABLE IF EXISTS `vc_sys`;
CREATE TABLE `vc_sys` (
  `id` int(10) NOT NULL DEFAULT '1',
  `site_status` int(10) DEFAULT NULL,
  `site_name` varchar(50) DEFAULT NULL,
  `site_keyword` varchar(50) DEFAULT NULL,
  `site_desc` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vc_sys
-- ----------------------------
INSERT INTO `vc_sys` VALUES ('1', '1', '大鹏的cms', 'cms,不错的cms', '这就是一个描述2333');

-- ----------------------------
-- Table structure for `vc_user`
-- ----------------------------
DROP TABLE IF EXISTS `vc_user`;
CREATE TABLE `vc_user` (
  `uid` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `type` int(2) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `cardid` varchar(100) DEFAULT NULL,
  `realname` varchar(80) DEFAULT NULL,
  `token` varchar(150) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `sex` int(2) DEFAULT NULL,
  `addtime` int(50) DEFAULT NULL,
  `is_real` int(10) NOT NULL DEFAULT '0',
  `manage_id` int(10) DEFAULT NULL,
  `area` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vc_user
-- ----------------------------
INSERT INTO `vc_user` VALUES ('1', '大鹏', '1', '888888888888', '4408988898444443345', '何志鹏', '34dsfgsdfgdf2134234t', null, '大鹏', '1', '1617936865', '1', '2', null);
INSERT INTO `vc_user` VALUES ('2', '小菊110', '2', '159165897110', '4408988898444443110', '大局110', 'dfasft345sdffsdfsdf12a', null, '小菊110', '2', '1617936965', '0', '0', null);
INSERT INTO `vc_user` VALUES ('3', '郑大强', '2', '15916582988', '4408988898444443345', '大强哥', 'sdfsadfgsdfgds', null, '故意', '1', '1614936965', '1', null, null);
INSERT INTO `vc_user` VALUES ('5', '微诚集团CEO1', '2', '15916582988', '4408988898444443300', '大局00', null, null, '微诚集团CEO1', null, '1618195986', '0', null, null);
INSERT INTO `vc_user` VALUES ('6', '大鹏', '1', '18916582911', '440680000000000000', '何智鹏', 'ojicS5JhuiZSARKPKvGe0qfsO8xg', 'https://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqU3YqAKTAQtAicYrh3FXeyofGg3mfTSovuVEVGEX22wtCoN7mbBdEdGcSV5RDMZ4Ae8SlBDiajKicAA/132', '大鹏', '1', '1618467826', '1', '5', 'Foshan');
