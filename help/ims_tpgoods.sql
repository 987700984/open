/*
Navicat MySQL Data Transfer

Source Server         : 119.23.48.183_we7
Source Server Version : 50554
Source Host           : 119.23.48.183:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50554
File Encoding         : 65001

Date: 2017-07-11 18:12:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_tpgoods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tpgoods`;
CREATE TABLE `ims_tpgoods` (
  `goodsid` int(11) NOT NULL AUTO_INCREMENT,
  `goodsname` varchar(255) DEFAULT NULL,
  `goodsstatus` smallint(6) DEFAULT '0' COMMENT '0：启用，1：停用',
  `goodsprice` decimal(14,2) DEFAULT NULL,
  `goodscreatetime` datetime DEFAULT NULL,
  `goodscreateperson` varchar(255) DEFAULT NULL,
  `goodsmodtime` datetime DEFAULT NULL,
  `goodsmodperson` varchar(255) DEFAULT NULL,
  `goodsnum` int(11) DEFAULT '1' COMMENT '商品默认数量',
  `goodsnumgive` int(11) DEFAULT '0' COMMENT '商品赠送数量',
  `roleid` int(11) DEFAULT '0' COMMENT '角色id',
  `iscommission` int(11) DEFAULT '0' COMMENT '是否计算分佣 0[不计算] 1[计算]',
  PRIMARY KEY (`goodsid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tpgoods
-- ----------------------------
INSERT INTO `ims_tpgoods` VALUES ('1', '单张房卡', '0', '3.00', '2017-06-19 11:23:01', '1', '2017-07-11 17:04:36', '1', '1', '0', '0', '0');
INSERT INTO `ims_tpgoods` VALUES ('2', '七彩棒棒糖', '0', '3.00', '2017-07-03 17:38:43', '1', '2017-07-11 17:04:45', '1', '1', '0', '1', '0');
INSERT INTO `ims_tpgoods` VALUES ('3', '冰爽可乐', '0', '30.00', '2017-07-03 17:39:17', '1', '2017-07-07 18:22:38', '1', '10', '0', '0', '0');
INSERT INTO `ims_tpgoods` VALUES ('4', '可口薯条', '0', '150.00', '2017-07-03 17:40:24', '1', '2017-07-07 18:23:07', '1', '50', '0', '0', '0');
INSERT INTO `ims_tpgoods` VALUES ('5', '代理合伙人套餐', '0', '9999.00', '2017-07-07 10:04:17', '404', '2017-07-07 10:04:56', '404', '3333', '0', '14', '1');
INSERT INTO `ims_tpgoods` VALUES ('6', '钻石代理套餐', '0', '4999.00', '2017-07-07 10:05:59', '404', '2017-07-07 10:06:18', '404', '1666', '0', '8', '1');
INSERT INTO `ims_tpgoods` VALUES ('7', '黄金代理套餐', '0', '2999.00', '2017-07-07 10:06:46', '404', '2017-07-07 10:09:00', '404', '999', '0', '12', '1');
INSERT INTO `ims_tpgoods` VALUES ('8', '白银代理套餐', '0', '999.00', '2017-07-07 10:08:13', '404', '2017-07-07 10:09:10', '404', '333', '0', '13', '1');
INSERT INTO `ims_tpgoods` VALUES ('9', '体验代理套餐', '0', '39.00', '2017-07-07 10:08:37', '404', '2017-07-11 18:00:49', '1', '13', '0', '11', '1');
