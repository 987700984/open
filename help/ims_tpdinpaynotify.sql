/*
Navicat MySQL Data Transfer

Source Server         : 119.23.48.183_we7
Source Server Version : 50554
Source Host           : 119.23.48.183:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50554
File Encoding         : 65001

Date: 2017-07-11 18:12:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_tpdinpaynotify`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tpdinpaynotify`;
CREATE TABLE `ims_tpdinpaynotify` (
  `order_no` varchar(64) NOT NULL COMMENT '商家订单号',
  `order_time` datetime DEFAULT NULL COMMENT '商家订单时间',
  `order_amount` decimal(14,2) DEFAULT NULL COMMENT '商家订单金额',
  `extra_return_param` varchar(100) DEFAULT NULL COMMENT '回传参数',
  `trade_no` varchar(30) DEFAULT NULL COMMENT '智付订单号',
  `trade_time` datetime DEFAULT NULL COMMENT '智付订单时间',
  `trade_status` varchar(7) DEFAULT NULL COMMENT '订单状态',
  `bank_seq_no` varchar(50) DEFAULT NULL COMMENT '银行交易流水号',
  `merchant_code` varchar(20) DEFAULT NULL COMMENT '商家号',
  `notify_type` varchar(14) DEFAULT NULL COMMENT '通知方式',
  `notify_id` varchar(100) DEFAULT NULL COMMENT '通知校验ID',
  `interface_version` varchar(10) DEFAULT NULL COMMENT '接口版本',
  `sign_type` varchar(10) DEFAULT NULL COMMENT '签名方式',
  `sign` varchar(255) DEFAULT NULL COMMENT '智付返回签名数据',
  PRIMARY KEY (`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tpdinpaynotify
-- ----------------------------
INSERT INTO `ims_tpdinpaynotify` VALUES ('20170707161254', '2017-07-07 16:12:54', '0.01', '100443', null, '2017-07-07 16:13:19', 'SUCCESS', '102562007826201707075103847052', '2110002055', 'offline_notify', '24353d268aad478d83b9848c1b6580ba', 'V3.0', 'RSA-S', 'bank_seq_no=102562007826201707075103847052&extra_return_param=100443&interface_version=V3.0&merchant_code=2110002055&notify_id=24353d268aad478d83b9848c1b6580ba&notify_type=offline_notify&order_amount=0.01&order_no=20170707161254&order_time=2017-07-07 16:1');
INSERT INTO `ims_tpdinpaynotify` VALUES ('20170707175505', '2017-07-07 17:55:05', '0.01', '100443', null, '2017-07-07 17:55:15', 'SUCCESS', '102562007826201707073248112609', '2110002055', 'offline_notify', '02822d5caea741b380a0c86a4446c407', 'V3.0', 'RSA-S', 'bank_seq_no=102562007826201707073248112609&extra_return_param=100443&interface_version=V3.0&merchant_code=2110002055&notify_id=02822d5caea741b380a0c86a4446c407&notify_type=offline_notify&order_amount=0.01&order_no=20170707175505&order_time=2017-07-07 17:5');
INSERT INTO `ims_tpdinpaynotify` VALUES ('20170707181110', '2017-07-07 18:11:10', '0.01', '100443', null, '2017-07-07 18:11:21', 'SUCCESS', '102562007826201707075104186796', '2110002055', 'offline_notify', 'da36cd361d184602912e5cd3321a3af5', 'V3.0', 'RSA-S', 'bank_seq_no=102562007826201707075104186796&extra_return_param=100443&interface_version=V3.0&merchant_code=2110002055&notify_id=da36cd361d184602912e5cd3321a3af5&notify_type=offline_notify&order_amount=0.01&order_no=20170707181110&order_time=2017-07-07 18:1');
INSERT INTO `ims_tpdinpaynotify` VALUES ('20170707181452', '2017-07-07 18:14:52', '0.01', '100443', null, '2017-07-07 18:14:59', 'SUCCESS', '102562007826201707075204199659', '2110002055', 'offline_notify', 'cb0fa239d60f41dd912de637d914a0e5', 'V3.0', 'RSA-S', 'bank_seq_no=102562007826201707075204199659&extra_return_param=100443&interface_version=V3.0&merchant_code=2110002055&notify_id=cb0fa239d60f41dd912de637d914a0e5&notify_type=offline_notify&order_amount=0.01&order_no=20170707181452&order_time=2017-07-07 18:1');
INSERT INTO `ims_tpdinpaynotify` VALUES ('20170707183448', '2017-07-07 18:34:48', '1.00', '100443', null, '2017-07-07 18:35:07', 'SUCCESS', '201707071835077049568', '2110002055', 'offline_notify', '7d02628ed8c941cda1cce42fc1c3bfdf', 'V3.0', 'RSA-S', 'bank_seq_no=201707071835077049568&extra_return_param=100443&interface_version=V3.0&merchant_code=2110002055&notify_id=7d02628ed8c941cda1cce42fc1c3bfdf&notify_type=offline_notify&order_amount=1&order_no=20170707183448&order_time=2017-07-07 18:34:48&trade_n');
INSERT INTO `ims_tpdinpaynotify` VALUES ('20170708180107', '2017-07-08 18:01:07', '0.01', '100443', null, '2017-07-08 18:01:20', 'SUCCESS', '102562007826201707085207228746', '2110002055', 'offline_notify', '99bfdeac7dda442296d6d208fd2d8c9a', 'V3.0', 'RSA-S', 'bank_seq_no=102562007826201707085207228746&extra_return_param=100443&interface_version=V3.0&merchant_code=2110002055&notify_id=99bfdeac7dda442296d6d208fd2d8c9a&notify_type=offline_notify&order_amount=0.01&order_no=20170708180107&order_time=2017-07-08 18:0');
INSERT INTO `ims_tpdinpaynotify` VALUES ('20170710142125', '2017-07-10 14:21:25', '0.01', '100443', null, '2017-07-10 14:21:35', 'SUCCESS', '102562007826201707107237837026', '2110002055', 'offline_notify', '16944ca1a0d54652b90116345e492712', 'V3.0', 'RSA-S', 'bank_seq_no=102562007826201707107237837026&extra_return_param=100443&interface_version=V3.0&merchant_code=2110002055&notify_id=16944ca1a0d54652b90116345e492712&notify_type=offline_notify&order_amount=0.01&order_no=20170710142125&order_time=2017-07-10 14:2');
INSERT INTO `ims_tpdinpaynotify` VALUES ('20170711135859', '2017-07-11 13:58:59', '0.01', '100443', null, '2017-07-11 14:01:26', 'SUCCESS', '102562007826201707117140791095', '2110002055', 'offline_notify', 'a72b8ef3e4ce48b6b2f82efdfdc107f5', 'V3.0', 'RSA-S', 'bank_seq_no=102562007826201707117140791095&extra_return_param=100443&interface_version=V3.0&merchant_code=2110002055&notify_id=a72b8ef3e4ce48b6b2f82efdfdc107f5&notify_type=offline_notify&order_amount=0.01&order_no=20170711135859&order_time=2017-07-11 13:5');
INSERT INTO `ims_tpdinpaynotify` VALUES ('20170711162056', '2017-07-11 16:20:56', '0.01', '100443', null, '2017-07-11 16:21:34', 'SUCCESS', '102562007826201707114269401731', '2110002055', 'offline_notify', 'befcbc5016af42268f7e5fe70e114375', 'V3.0', 'RSA-S', 'bank_seq_no=102562007826201707114269401731&extra_return_param=100443&interface_version=V3.0&merchant_code=2110002055&notify_id=befcbc5016af42268f7e5fe70e114375&notify_type=offline_notify&order_amount=0.01&order_no=20170711162056&order_time=2017-07-11 16:2');
INSERT INTO `ims_tpdinpaynotify` VALUES ('20170711170901', '2017-07-11 17:09:01', '3.00', '100317', null, '2017-07-11 17:17:58', 'SUCCESS', '102562007826201707112289776628', '2110002055', 'offline_notify', '63e5b99bf1ec4cb783370caaef10e437', 'V3.0', 'RSA-S', 'bank_seq_no=102562007826201707112289776628&extra_return_param=100317&interface_version=V3.0&merchant_code=2110002055&notify_id=63e5b99bf1ec4cb783370caaef10e437&notify_type=offline_notify&order_amount=3&order_no=20170711170901&order_time=2017-07-11 17:09:0');
INSERT INTO `ims_tpdinpaynotify` VALUES ('20170711173112', '2017-07-11 17:31:12', '0.01', '100317', null, '2017-07-11 17:32:38', 'SUCCESS', '102562007826201707111168939225', '2110002055', 'offline_notify', '27d424847e564b738f79def9c7b873d5', 'V3.0', 'RSA-S', 'bank_seq_no=102562007826201707111168939225&extra_return_param=100317&interface_version=V3.0&merchant_code=2110002055&notify_id=27d424847e564b738f79def9c7b873d5&notify_type=offline_notify&order_amount=0.01&order_no=20170711173112&order_time=2017-07-11 17:3');
