/*
Navicat MySQL Data Transfer

Source Server         : 119.23.48.183_we7
Source Server Version : 50554
Source Host           : 119.23.48.183:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50554
File Encoding         : 65001

Date: 2017-07-11 18:13:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_tpuser_agent`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tpuser_agent`;
CREATE TABLE `ims_tpuser_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wechat_name` varchar(50) DEFAULT NULL COMMENT '微信号',
  `typeid` int(11) DEFAULT '0' COMMENT '代理级别',
  `area` varchar(100) DEFAULT NULL COMMENT '省市区',
  `address` varchar(250) DEFAULT NULL COMMENT '详细地址',
  `service_address` varchar(150) DEFAULT NULL COMMENT '服务地区',
  `addtime` int(50) DEFAULT '0' COMMENT '提交时间',
  `openid` varchar(50) DEFAULT NULL COMMENT '微信openid',
  `unionid` varchar(50) DEFAULT NULL COMMENT '微信unionid',
  `tpuserid` int(11) DEFAULT '0' COMMENT 'tupuser表 对应id',
  `userid` int(11) DEFAULT '0' COMMENT '游戏服务器userid',
  `status` int(11) DEFAULT '0' COMMENT '审核状态 0[审核中] 1[通过] 2[未通过]',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='申请成为代理表';

-- ----------------------------
-- Records of ims_tpuser_agent
-- ----------------------------
