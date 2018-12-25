/*
Navicat MySQL Data Transfer

Source Server         : 119.23.48.183_we7
Source Server Version : 50554
Source Host           : 119.23.48.183:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50554
File Encoding         : 65001

Date: 2017-07-11 18:12:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_tpnode`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tpnode`;
CREATE TABLE `ims_tpnode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_name` varchar(155) NOT NULL DEFAULT '' COMMENT '节点名称',
  `module_name` varchar(155) NOT NULL DEFAULT '' COMMENT '模块名',
  `control_name` varchar(155) NOT NULL DEFAULT '' COMMENT '控制器名',
  `action_name` varchar(155) NOT NULL COMMENT '方法名',
  `is_menu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是菜单项 1不是 2是',
  `typeid` int(11) NOT NULL COMMENT '父级节点id',
  `style` varchar(155) DEFAULT '' COMMENT '菜单样式',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30003 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tpnode
-- ----------------------------
INSERT INTO `ims_tpnode` VALUES ('1', '用户管理', '#', '#', '#', '2', '0', 'fa fa-users');
INSERT INTO `ims_tpnode` VALUES ('2', '用户列表', 'admin', 'user', 'index', '2', '1', '');
INSERT INTO `ims_tpnode` VALUES ('3', '添加用户', 'admin', 'user', 'useradd', '1', '2', '');
INSERT INTO `ims_tpnode` VALUES ('4', '编辑用户', 'admin', 'user', 'useredit', '1', '2', '');
INSERT INTO `ims_tpnode` VALUES ('5', '删除用户', 'admin', 'user', 'userdel', '1', '2', '');
INSERT INTO `ims_tpnode` VALUES ('6', '角色列表', 'admin', 'role', 'index', '2', '1', '');
INSERT INTO `ims_tpnode` VALUES ('7', '添加角色', 'admin', 'role', 'roleadd', '1', '6', '');
INSERT INTO `ims_tpnode` VALUES ('8', '编辑角色', 'admin', 'role', 'roleedit', '1', '6', '');
INSERT INTO `ims_tpnode` VALUES ('9', '删除角色', 'admin', 'role', 'roledel', '1', '6', '');
INSERT INTO `ims_tpnode` VALUES ('10', '分配权限', 'admin', 'role', 'giveaccess', '1', '6', '');
INSERT INTO `ims_tpnode` VALUES ('11', '系统管理', '#', '#', '#', '1', '0', 'fa fa-desktop');
INSERT INTO `ims_tpnode` VALUES ('12', '数据备份/还原', 'admin', 'data', 'index', '1', '11', '');
INSERT INTO `ims_tpnode` VALUES ('13', '备份数据', 'admin', 'data', 'importdata', '1', '12', '');
INSERT INTO `ims_tpnode` VALUES ('14', '还原数据', 'admin', 'data', 'backdata', '1', '12', '');
INSERT INTO `ims_tpnode` VALUES ('50', '房卡管理', '#', '#', '#', '2', '0', 'fa fa-desktop');
INSERT INTO `ims_tpnode` VALUES ('52', '房卡充值', 'cardpay', 'cardpay', 'index', '2', '50', '');
INSERT INTO `ims_tpnode` VALUES ('54', '添加充值', 'cardpay', 'cardpay', 'cardpayadd', '1', '50', '');
INSERT INTO `ims_tpnode` VALUES ('100', '公告管理', '#', '#', '#', '2', '0', 'fa fa-desktop');
INSERT INTO `ims_tpnode` VALUES ('102', '公告列表', 'notice', 'notice', 'index', '2', '100', '');
INSERT INTO `ims_tpnode` VALUES ('104', '添加公告', 'notice', 'notice', 'noticeadd', '1', '100', '');
INSERT INTO `ims_tpnode` VALUES ('106', '编辑公告', 'notice', 'notice', 'noticeedit', '1', '100', '');
INSERT INTO `ims_tpnode` VALUES ('108', '删除公告', 'notice', 'notice', 'noticedel', '1', '100', '');
INSERT INTO `ims_tpnode` VALUES ('200', '会员管理', '#', '#', '#', '2', '0', 'fa fa-desktop');
INSERT INTO `ims_tpnode` VALUES ('300', '文章管理', '#', '#', '#', '2', '0', 'fa fa-desktop');
INSERT INTO `ims_tpnode` VALUES ('302', '文章列表', 'article', 'article', 'index', '2', '300', '');
INSERT INTO `ims_tpnode` VALUES ('304', '添加文章', 'article', 'article', 'articleadd', '1', '300', '');
INSERT INTO `ims_tpnode` VALUES ('306', '编辑文章', 'article', 'article', 'articleedit', '1', '300', '');
INSERT INTO `ims_tpnode` VALUES ('308', '删除文章', 'article', 'article', 'articledel', '1', '300', '');
INSERT INTO `ims_tpnode` VALUES ('310', '预览文章', 'articles', 'articles', 'index', '1', '300', '');
INSERT INTO `ims_tpnode` VALUES ('202', '我的会员', 'member', 'member', 'index', '2', '200', '');
INSERT INTO `ims_tpnode` VALUES ('400', '商品管理', '#', '#', '#', '2', '0', 'fa fa-desktop');
INSERT INTO `ims_tpnode` VALUES ('402', '商品列表', 'goods', 'goods', 'index', '2', '400', '');
INSERT INTO `ims_tpnode` VALUES ('404', '添加商品', 'goods', 'goods', 'goodsadd', '1', '400', '');
INSERT INTO `ims_tpnode` VALUES ('406', '编辑商品', 'goods', 'goods', 'goodsedit', '1', '400', '');
INSERT INTO `ims_tpnode` VALUES ('408', '删除商品', 'goods', 'goods', 'goodsdel', '1', '400', '');
INSERT INTO `ims_tpnode` VALUES ('500', '订单管理', '#', '#', '#', '2', '0', 'fa fa-desktop');
INSERT INTO `ims_tpnode` VALUES ('502', '订单列表', 'orders', 'orders', 'index', '2', '500', '');
INSERT INTO `ims_tpnode` VALUES ('504', '添加订单', 'orders', 'orders', 'ordersadd', '1', '500', '');
INSERT INTO `ims_tpnode` VALUES ('506', '编辑订单', 'orders', 'orders', 'ordersedit', '1', '500', '');
INSERT INTO `ims_tpnode` VALUES ('508', '删除订单', 'orders', 'orders', 'ordersdel', '1', '500', '');
INSERT INTO `ims_tpnode` VALUES ('10000', '系统管理', '#', '#', '#', '2', '0', 'fa fa-desktop');
INSERT INTO `ims_tpnode` VALUES ('10002', '分佣折扣设置', 'settings', 'commissions', 'index', '2', '10000', '');
INSERT INTO `ims_tpnode` VALUES ('10004', '分佣编辑', 'settings', 'commissions', 'commissionsedit', '1', '10000', '');
INSERT INTO `ims_tpnode` VALUES ('204', '我的返利', 'member', 'member', 'rebate', '2', '200', '');
INSERT INTO `ims_tpnode` VALUES ('600', '营销管理', '#', '#', '#', '2', '0', 'fa fa-desktop');
INSERT INTO `ims_tpnode` VALUES ('602', '推广工具', 'marketings', 'marketings', 'index', '2', '600', '');
INSERT INTO `ims_tpnode` VALUES ('700', '代理管理', '#', '#', '#', '2', '0', 'fa fa-desktop');
INSERT INTO `ims_tpnode` VALUES ('702', '我的代理', 'agent', 'agent', 'index', '2', '700', '');
INSERT INTO `ims_tpnode` VALUES ('704', '派发房卡', 'agent', 'agent', 'agentallot', '1', '700', '');
INSERT INTO `ims_tpnode` VALUES ('706', '我的返利', 'agent', 'agent', 'rebate', '2', '700', '');
INSERT INTO `ims_tpnode` VALUES ('10006', '同步用户', 'settings', 'syncusers', 'index', '1', '10000', '');
INSERT INTO `ims_tpnode` VALUES ('10008', '同步用户提交', 'settings', 'syncusers', 'syncuserssubmit', '1', '10000', '');
INSERT INTO `ims_tpnode` VALUES ('20002', '前端用户中心', 'mobile', 'usercenter', 'index', '1', '20000', '');
INSERT INTO `ims_tpnode` VALUES ('20000', '手机管理', '#', '#', '#', '1', '0', '');
INSERT INTO `ims_tpnode` VALUES ('708', '房卡派发（高级）', 'agent', 'agent', 'cardpay', '2', '700', '');
INSERT INTO `ims_tpnode` VALUES ('710', '房卡派发（高级）提交', 'agent', 'agent', 'cardpayadd', '1', '700', '');
INSERT INTO `ims_tpnode` VALUES ('701', '添加代理', 'agent', 'agent', 'agentadd', '2', '700', '');
INSERT INTO `ims_tpnode` VALUES ('714', '房卡派发记录', 'agent', 'agent', 'recordlist', '2', '700', '');
INSERT INTO `ims_tpnode` VALUES ('56', '代理申请列表', 'admin', 'user', 'agentapplicationlist', '2', '1', '');
INSERT INTO `ims_tpnode` VALUES ('58', '审核代理操作', 'admin', 'user', 'agentadopt', '1', '1', '');
INSERT INTO `ims_tpnode` VALUES ('30000', '充值管理', '#', '#', '', '2', '0', 'fa fa-desktop');
INSERT INTO `ims_tpnode` VALUES ('30002', '在线充值', 'api', 'dinpay', 'b2c', '2', '30000', '');
