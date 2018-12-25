/*
Navicat MySQL Data Transfer

Source Server         : 119.23.48.183_we7
Source Server Version : 50554
Source Host           : 119.23.48.183:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50554
File Encoding         : 65001

Date: 2017-07-11 18:12:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_tporders`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tporders`;
CREATE TABLE `ims_tporders` (
  `ordersid` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) DEFAULT NULL,
  `orderscreatetime` datetime DEFAULT NULL,
  `orderscreatepersonid` varchar(255) DEFAULT NULL,
  `ordersmodtime` datetime DEFAULT NULL,
  `ordersmodpersonid` varchar(255) DEFAULT NULL,
  `ordersstatus` smallint(6) DEFAULT '0' COMMENT '0：未领取；1已领取；',
  `ordersquantity` int(11) DEFAULT NULL,
  `orderstype` smallint(6) DEFAULT '0' COMMENT '0：充值订单；1：奖励订单',
  `forthwithgoodsprice` decimal(14,2) DEFAULT '0.00',
  `ordersallotpersonid` int(11) DEFAULT NULL COMMENT '派发房卡用户ID（订单类型为派发订单时写入）',
  `order_no` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`ordersid`),
  KEY `ims_tporders_index_orderscreateperson` (`orderscreatepersonid`),
  KEY `ims_tporders_index_ordersstatus` (`ordersstatus`),
  KEY `FK_FK_ims_tporders_on_ims_tpgoods` (`goodsid`),
  CONSTRAINT `FK_FK_ims_tporders_on_ims_tpgoods` FOREIGN KEY (`goodsid`) REFERENCES `ims_tpgoods` (`goodsid`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tporders
-- ----------------------------
INSERT INTO `ims_tporders` VALUES ('1', '1', '2017-06-19 14:34:04', '14', '2017-07-11 15:28:58', '1', '1', '10', '3', '10.00', null, null);
INSERT INTO `ims_tporders` VALUES ('2', '1', '2017-06-19 14:34:28', '8', '2017-06-19 14:42:50', '1', '0', '1', '1', '10.00', null, null);
INSERT INTO `ims_tporders` VALUES ('5', '1', '2017-06-23 22:51:43', '1', null, null, '0', '-10', '2', '10.00', null, null);
INSERT INTO `ims_tporders` VALUES ('6', '1', '2017-06-23 22:51:43', '7', null, null, '0', '10', '2', '10.00', null, null);
INSERT INTO `ims_tporders` VALUES ('7', '1', '2017-06-29 18:15:49', '1', null, null, '0', '-10', '2', '0.60', null, null);
INSERT INTO `ims_tporders` VALUES ('8', '1', '2017-06-29 18:15:49', '14', null, null, '0', '10', '2', '0.60', null, null);
INSERT INTO `ims_tporders` VALUES ('9', '1', '2017-06-30 17:11:06', '100668', null, null, '0', '-10', '2', '0.00', null, null);
INSERT INTO `ims_tporders` VALUES ('10', '1', '2017-06-30 17:11:06', '100443', null, null, '0', '10', '2', '0.00', null, null);
INSERT INTO `ims_tporders` VALUES ('11', '1', '2017-06-30 17:22:47', '100668', null, null, '0', '-10', '2', '0.00', null, null);
INSERT INTO `ims_tporders` VALUES ('12', '1', '2017-06-30 17:22:47', '100443', null, null, '0', '10', '2', '0.00', null, null);
INSERT INTO `ims_tporders` VALUES ('13', '1', '2017-07-03 16:13:57', '1', null, null, '0', '-10', '2', '0.20', null, null);
INSERT INTO `ims_tporders` VALUES ('14', '1', '2017-07-03 16:13:57', '14', null, null, '0', '10', '2', '0.20', null, null);
INSERT INTO `ims_tporders` VALUES ('25', '1', '2017-07-03 17:57:34', '372', null, null, '0', '-10', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('26', '1', '2017-07-03 17:57:34', '395', null, null, '0', '10', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('27', '1', '2017-07-03 18:03:40', '372', null, null, '0', '-10', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('28', '1', '2017-07-03 18:03:40', '396', null, null, '0', '10', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('29', '1', '2017-07-03 20:22:01', '397', null, null, '0', '-1', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('30', '1', '2017-07-03 20:22:01', '398', null, null, '0', '1', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('31', '1', '2017-07-03 20:47:15', '397', null, null, '0', '-49', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('32', '1', '2017-07-03 20:47:15', '398', null, null, '0', '49', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('33', '1', '2017-07-04 15:02:28', '397', null, null, '0', '-50', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('34', '1', '2017-07-04 15:02:28', '398', null, null, '0', '50', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('35', '1', '2017-07-05 10:20:03', '397', null, null, '0', '-1', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('36', '1', '2017-07-05 10:20:03', '100418', null, null, '0', '1', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('37', '1', '2017-07-05 11:14:15', '397', null, null, '0', '-100', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('38', '1', '2017-07-05 11:14:15', '400', null, null, '0', '100', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('39', '1', '2017-07-05 11:17:56', '397', null, null, '0', '-100', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('40', '1', '2017-07-05 11:17:56', '100506', null, null, '0', '100', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('41', '1', '2017-07-05 11:22:55', '397', null, null, '0', '-99', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('42', '1', '2017-07-05 11:22:55', '100506', null, null, '0', '99', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('43', '1', '2017-07-05 11:35:31', '397', null, null, '0', '-1', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('44', '1', '2017-07-05 11:35:31', '100342', null, null, '0', '1', '2', '0.01', null, null);
INSERT INTO `ims_tporders` VALUES ('57', '1', '2017-07-07 11:31:00', '414', null, null, '0', '1', '2', '1.00', '222', null);
INSERT INTO `ims_tporders` VALUES ('58', '1', '2017-07-07 11:40:59', '403', null, null, '0', '1', '2', '1.00', '444', null);
INSERT INTO `ims_tporders` VALUES ('59', '1', '2017-07-07 11:41:29', '414', null, null, '0', '1', '2', '1.00', '222', null);
INSERT INTO `ims_tporders` VALUES ('60', '1', '2017-07-07 11:45:37', '414', null, null, '0', '1', '2', '1.00', '222', null);
INSERT INTO `ims_tporders` VALUES ('61', '1', '2017-07-07 11:54:18', '414', null, null, '0', '1', '2', '1.00', '213', null);
INSERT INTO `ims_tporders` VALUES ('62', '1', '2017-07-07 11:58:05', '414', null, null, '0', '1', '2', '1.00', '222', null);
INSERT INTO `ims_tporders` VALUES ('64', '1', '2017-07-07 15:19:53', '414', null, null, '0', '5', '2', '1.00', '213', null);
INSERT INTO `ims_tporders` VALUES ('67', '2', '2017-07-07 16:13:32', '132', null, null, '1', '1', '0', '0.01', null, '20170707161254');
INSERT INTO `ims_tporders` VALUES ('68', '1', '2017-07-07 17:46:19', '37', null, null, '0', '100', '2', '1.00', '224', null);
INSERT INTO `ims_tporders` VALUES ('69', '3', '2017-07-07 17:55:35', '132', null, null, '1', '1', '0', '0.01', null, '20170707175505');
INSERT INTO `ims_tporders` VALUES ('70', '3', '2017-07-07 18:11:32', '132', null, null, '1', '1', '0', '0.01', null, '20170707181110');
INSERT INTO `ims_tporders` VALUES ('71', '2', '2017-07-07 18:15:29', '132', null, null, '1', '1', '0', '0.01', null, '20170707181452');
INSERT INTO `ims_tporders` VALUES ('72', '2', '2017-07-07 18:35:22', '132', null, null, '1', '1', '0', '1.00', null, '20170707183448');
INSERT INTO `ims_tporders` VALUES ('73', '2', '2017-07-08 18:01:35', '132', null, null, '1', '1', '0', '0.01', null, '20170708180107');
INSERT INTO `ims_tporders` VALUES ('74', '1', '2017-07-09 16:51:00', '432', null, null, '0', '100', '2', '1.00', '1', null);
INSERT INTO `ims_tporders` VALUES ('75', '1', '2017-07-09 16:52:55', '432', null, null, '0', '100', '2', '1.00', '527', null);
INSERT INTO `ims_tporders` VALUES ('76', '1', '2017-07-09 16:54:10', '432', null, null, '0', '20', '2', '1.00', '527', null);
INSERT INTO `ims_tporders` VALUES ('77', '1', '2017-07-10 10:03:07', '432', null, null, '0', '1', '2', '1.00', '523', null);
INSERT INTO `ims_tporders` VALUES ('78', '2', '2017-07-10 14:21:48', '132', null, null, '1', '1', '0', '0.01', null, '20170710142125');
INSERT INTO `ims_tporders` VALUES ('79', '1', '2017-07-11 13:55:53', '432', null, null, '0', '1', '2', '0.50', '532', null);
INSERT INTO `ims_tporders` VALUES ('80', '1', '2017-07-11 13:58:55', '432', null, null, '0', '1', '2', '0.50', '533', null);
INSERT INTO `ims_tporders` VALUES ('81', '2', '2017-07-11 14:02:06', '132', null, null, '1', '1', '0', '0.01', null, '20170711135859');
INSERT INTO `ims_tporders` VALUES ('82', '1', '2017-07-11 14:02:27', '432', null, null, '0', '1', '2', '0.50', '534', null);
INSERT INTO `ims_tporders` VALUES ('83', '1', '2017-07-11 14:04:29', '432', null, null, '0', '1', '2', '0.50', '535', null);
INSERT INTO `ims_tporders` VALUES ('88', '5', '2017-07-11 14:56:31', '1', null, null, '0', '1', '0', '0.00', null, null);
INSERT INTO `ims_tporders` VALUES ('89', '1', '2017-07-11 15:56:14', '535', null, null, '0', '1', '2', '1.00', '536', null);
INSERT INTO `ims_tporders` VALUES ('90', '9', '2017-07-11 16:21:48', '132', null, null, '1', '1', '3', '0.01', null, '20170711162056');
INSERT INTO `ims_tporders` VALUES ('91', '2', '2017-07-11 17:18:12', '28', null, null, '1', '1', '0', '3.00', null, '20170711170901');
INSERT INTO `ims_tporders` VALUES ('92', '9', '2017-07-11 17:32:53', '404', null, null, '1', '1', '3', '0.01', null, '20170711173112');
INSERT INTO `ims_tporders` VALUES ('93', '5', '2017-07-11 17:41:11', '404', '2017-07-11 17:44:16', '1', '1', '1', '3', '9999.00', null, null);
INSERT INTO `ims_tporders` VALUES ('94', '1', '2017-07-11 18:01:45', '537', null, null, '0', '1', '1', '0.00', '535', null);
