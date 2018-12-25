/*
Navicat MySQL Data Transfer

Source Server         : 119.23.48.183_we7
Source Server Version : 50554
Source Host           : 119.23.48.183:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50554
File Encoding         : 65001

Date: 2017-07-11 18:13:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_tprole`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tprole`;
CREATE TABLE `ims_tprole` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `rolename` varchar(155) NOT NULL COMMENT '角色名称',
  `rule` varchar(255) DEFAULT '' COMMENT '权限节点数据',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1003 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tprole
-- ----------------------------
INSERT INTO `ims_tprole` VALUES ('1', '超级管理员', '');
INSERT INTO `ims_tprole` VALUES ('8', '钻石代理', '600,602,700,702,704,706,708,710,714,20000,20002,30000,30002');
INSERT INTO `ims_tprole` VALUES ('9', '业务员', '600,602,700,702,704,706,708,710,714,20000,20002,30000,30002');
INSERT INTO `ims_tprole` VALUES ('7', '会员', '200,202,204,20000,20002,30000,30002');
INSERT INTO `ims_tprole` VALUES ('10', '运营', '300,302,304,306,308');
INSERT INTO `ims_tprole` VALUES ('11', '体验代理', '600,602,700,702,704,706,708,710,712,714,20000,20002');
INSERT INTO `ims_tprole` VALUES ('12', '黄金代理', '600,602,700,702,704,706,708,710,701,714,20000,20002,30000,30002');
INSERT INTO `ims_tprole` VALUES ('13', '白银代理', '600,602,700,702,704,706,708,710,701,714,20000,20002,30000,30002');
INSERT INTO `ims_tprole` VALUES ('14', '合伙人', '600,602,700,702,704,706,708,710,701,714,20000,20002,30000,30002');
INSERT INTO `ims_tprole` VALUES ('0', '游戏服务器同步用户', '200,202,204,600,602');
INSERT INTO `ims_tprole` VALUES ('1001', '线下渠道A级', '700,702,704,706,708,710,701,714');
INSERT INTO `ims_tprole` VALUES ('1002', '线下渠道B级', '700,708,710,714');
