/*
Navicat MySQL Data Transfer

Source Server         : 119.23.48.183_we7
Source Server Version : 50554
Source Host           : 119.23.48.183:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50554
File Encoding         : 65001

Date: 2017-07-11 18:12:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_tpcommissions`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tpcommissions`;
CREATE TABLE `ims_tpcommissions` (
  `commissionsid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) DEFAULT NULL,
  `commissions2` smallint(6) DEFAULT '0',
  `commissions3` smallint(6) DEFAULT '0',
  `commissionscardpaydiscount` smallint(6) DEFAULT '0',
  `commissionsmodtime` datetime DEFAULT NULL,
  `commissionsmodperson` int(11) DEFAULT NULL,
  PRIMARY KEY (`commissionsid`),
  KEY `FK_FK_ims_tpcommissions_on_ims_tprole` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tpcommissions
-- ----------------------------
INSERT INTO `ims_tpcommissions` VALUES ('1', '7', '3', '2', '100', '2017-07-08 10:20:34', '404');
INSERT INTO `ims_tpcommissions` VALUES ('2', '8', '30', '20', '20', '2017-07-05 14:57:25', '1');
INSERT INTO `ims_tpcommissions` VALUES ('3', '9', '11', '0', '100', '2017-07-07 11:26:20', '1');
INSERT INTO `ims_tpcommissions` VALUES ('4', '1', '50', '30', '20', '2017-06-23 23:31:36', '1');
INSERT INTO `ims_tpcommissions` VALUES ('5', '11', '10', '5', '30', '2017-07-05 14:55:25', '1');
INSERT INTO `ims_tpcommissions` VALUES ('6', '12', '25', '15', '30', '2017-07-05 14:57:09', '1');
INSERT INTO `ims_tpcommissions` VALUES ('7', '13', '20', '10', '30', '2017-07-05 14:56:47', '1');
INSERT INTO `ims_tpcommissions` VALUES ('8', '14', '40', '25', '10', '2017-07-05 14:54:36', '1');
INSERT INTO `ims_tpcommissions` VALUES ('9', '1001', '3', '2', '50', '2017-07-10 17:16:35', '1');
INSERT INTO `ims_tpcommissions` VALUES ('10', '0', '3', '2', '100', '2017-07-10 14:12:57', '1');
INSERT INTO `ims_tpcommissions` VALUES ('11', '1002', '3', '2', '100', '2017-07-10 15:23:24', '1');
