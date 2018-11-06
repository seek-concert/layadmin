/*
Navicat MySQL Data Transfer

Source Server         : layadmin
Source Server Version : 50557
Source Host           : 123.207.83.249:3306
Source Database       : layadmin

Target Server Type    : MYSQL
Target Server Version : 50557
File Encoding         : 65001

Date: 2018-11-06 09:26:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lay_admin
-- ----------------------------
DROP TABLE IF EXISTS `lay_admin`;
CREATE TABLE `lay_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `password` varchar(100) NOT NULL COMMENT '用户密码',
  `secret` varchar(100) NOT NULL COMMENT '密钥',
  `status` tinyint(10) NOT NULL DEFAULT '1' COMMENT '状态 0为禁用  1为启用',
  `role_id` tinyint(10) NOT NULL COMMENT '角色ID',
  `last_login_ip` char(15) DEFAULT NULL COMMENT '最近一次登陆ip',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='后台用户表';

-- ----------------------------
-- Records of lay_admin
-- ----------------------------
INSERT INTO `lay_admin` VALUES ('1', '用户', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '01FC504B-E3D2-94D0-EAE3-3EB10385A395', '1', '2', '125.84.195.15', null, '1540523320', null);
INSERT INTO `lay_admin` VALUES ('2', '小张', '123456', 'e10adc3949ba59abbe56e057f20f883e', '309D8163-741D-EB7D-EA07-61FB480FE0E9', '1', '2', null, null, null, null);

-- ----------------------------
-- Table structure for lay_config
-- ----------------------------
DROP TABLE IF EXISTS `lay_config`;
CREATE TABLE `lay_config` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `webname` varchar(255) NOT NULL DEFAULT '' COMMENT '网站名称',
  `webnum` varchar(100) DEFAULT NULL COMMENT '网站备案号',
  `seokey` varchar(255) DEFAULT NULL COMMENT 'SEO关键字',
  `wx_code` varchar(255) DEFAULT NULL COMMENT '微信二维码',
  `qq_num` int(20) DEFAULT NULL COMMENT 'QQ',
  `e_mail` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `announcement` text COMMENT '公告',
  `content` text COMMENT '关于我们',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='网站配置表';

-- ----------------------------
-- Records of lay_config
-- ----------------------------
INSERT INTO `lay_config` VALUES ('1', '>代码坞-code坞', '渝ICP备18011678号', null, 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQGh8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyM2daSHN1LTlmZDMxMDAwMDAwN18AAgQB2_VZAwQAAAAA', '1105420247', null, '本站致力于PHP,JAVA共同学习交流,共同成长,交流群:6968963', '代码分享交流群：6968963', null, null, null);

-- ----------------------------
-- Table structure for lay_menu
-- ----------------------------
DROP TABLE IF EXISTS `lay_menu`;
CREATE TABLE `lay_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID  默认为0',
  `name` varchar(255) NOT NULL COMMENT '菜单名称',
  `icon` varchar(255) DEFAULT NULL COMMENT '菜单图标',
  `url` varchar(255) NOT NULL COMMENT '菜单地址',
  `method` varchar(255) DEFAULT NULL COMMENT ' 限制请求方法，【get，post，put，delete】',
  `display` tinyint(255) NOT NULL DEFAULT '0' COMMENT '显示，【0隐藏，1显示】',
  `sort` int(255) NOT NULL DEFAULT '0' COMMENT '排序',
  `info` text COMMENT '描述',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='菜单节点表';

-- ----------------------------
-- Records of lay_menu
-- ----------------------------
INSERT INTO `lay_menu` VALUES ('1', '0', '系统管理', 'fa fa-desktop', '#', null, '1', '0', null, null, null, null);
INSERT INTO `lay_menu` VALUES ('2', '1', '菜单节点管理', '', '/admin/menu/index', null, '1', '0', null, null, null, null);
INSERT INTO `lay_menu` VALUES ('3', '1', '用户角色管理', '', '/admin/role/index', null, '1', '0', null, null, null, null);
INSERT INTO `lay_menu` VALUES ('4', '1', '后台用户管理', '', '/admin/admin/index', null, '1', '0', null, null, null, null);
INSERT INTO `lay_menu` VALUES ('5', '1', '网站配置管理', '', '/admin/config/index', null, '1', '0', null, null, null, null);
INSERT INTO `lay_menu` VALUES ('6', '0', '文章管理', 'fa fa-book', '#', null, '1', '0', null, null, null, null);
INSERT INTO `lay_menu` VALUES ('7', '2', '添加菜单节点', '', '/admin/menu/add', null, '0', '0', null, null, null, null);
INSERT INTO `lay_menu` VALUES ('8', '2', '修改菜单节点', '', '/admin/menu/edit', null, '0', '0', null, null, null, null);
INSERT INTO `lay_menu` VALUES ('9', '2', '删除菜单节点', '', '/admin/menu/del', null, '0', '0', null, null, null, null);
INSERT INTO `lay_menu` VALUES ('10', '3', '添加用户角色', '', '/admin/admin/add', null, '0', '0', null, null, null, null);

-- ----------------------------
-- Table structure for lay_msg
-- ----------------------------
DROP TABLE IF EXISTS `lay_msg`;
CREATE TABLE `lay_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `send_id` int(11) NOT NULL COMMENT '发送ID',
  `receive_id` int(11) NOT NULL COMMENT '接收ID',
  `content` longtext COMMENT '内容',
  `needsend` tinyint(4) NOT NULL DEFAULT '1' COMMENT '消息状态  0已推送 1未推送',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='聊天消息表';

-- ----------------------------
-- Records of lay_msg
-- ----------------------------
INSERT INTO `lay_msg` VALUES ('1', '2', '1', '21111', '1', null, null, null);
INSERT INTO `lay_msg` VALUES ('2', '1', '2', '123456', '0', '1541313185', '1541313185', null);
INSERT INTO `lay_msg` VALUES ('3', '1', '2', '32146566', '0', '1541399585', '1541399585', null);

-- ----------------------------
-- Table structure for lay_role
-- ----------------------------
DROP TABLE IF EXISTS `lay_role`;
CREATE TABLE `lay_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(155) NOT NULL COMMENT '角色名称',
  `type` tinyint(255) NOT NULL DEFAULT '1' COMMENT '类型  1为受约束角色  0为超级管理员',
  `menu_ids` varchar(255) DEFAULT '' COMMENT '权限节点数据',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 0禁用 1启用',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='角色权限表';

-- ----------------------------
-- Records of lay_role
-- ----------------------------
INSERT INTO `lay_role` VALUES ('1', '超级管理员', '0', '*', '1', null, null, null);
INSERT INTO `lay_role` VALUES ('2', '系统维护员', '1', '1,2,,3,4', '1', null, null, null);
INSERT INTO `lay_role` VALUES ('3', '管理员', '1', '6', '1', null, null, null);

-- ----------------------------
-- Table structure for lay_user
-- ----------------------------
DROP TABLE IF EXISTS `lay_user`;
CREATE TABLE `lay_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL COMMENT '用户名',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '类型  1为用户  2为客服',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态   0为离线  1为在线',
  `sign` varchar(255) DEFAULT NULL COMMENT '签名',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of lay_user
-- ----------------------------
INSERT INTO `lay_user` VALUES ('1', '客服小张', '2', '0', '时间是诠释人生的最好方式', '/static/index/img/a1_off.png', null, null, null);
INSERT INTO `lay_user` VALUES ('2', '游客9527', '1', '1', '时间是诠释人生的最好方式', '/static/index/img/timg.jpg', null, null, null);
