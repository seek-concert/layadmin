/*
Navicat MySQL Data Transfer

Source Server         : phpwamp
Source Server Version : 50554
Source Host           : 127.0.0.1:3306
Source Database       : layadmin

Target Server Type    : MYSQL
Target Server Version : 50554
File Encoding         : 65001

Date: 2018-10-09 19:05:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lay_admin
-- ----------------------------
DROP TABLE IF EXISTS `lay_admin`;
CREATE TABLE `lay_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID 默认为0',
  `name` enum('') NOT NULL COMMENT '名称',
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `password` varchar(100) NOT NULL COMMENT '用户密码',
  `secret` varchar(100) NOT NULL COMMENT '密钥',
  `type` tinyint(10) NOT NULL DEFAULT '1' COMMENT '用户类型 (默认 为1)   1为受约束角色 0为超级管理员',
  `status` tinyint(10) NOT NULL DEFAULT '1' COMMENT '状态 0为禁用  1为启用',
  `lastloginip` char(15) DEFAULT NULL COMMENT '最近一次登陆ip',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='后台用户表';

-- ----------------------------
-- Records of lay_admin
-- ----------------------------
INSERT INTO `lay_admin` VALUES ('1', '0', '', 'admin', 'd0f1cfce4eaf34b6127e5bbd43cf401b', 'abcdef', '1', '1', '27.14.253.213', null, null, null);

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
  `icon` varchar(255) NOT NULL COMMENT '菜单图标',
  `url` varchar(255) NOT NULL COMMENT '菜单地址',
  `method` varchar(255) DEFAULT NULL COMMENT ' 限制请求方法，【get，post，put，delete】',
  `display` tinyint(255) NOT NULL DEFAULT '0' COMMENT '显示，【0隐藏，1显示】',
  `sort` int(255) NOT NULL DEFAULT '0' COMMENT '排序',
  `info` text COMMENT '描述',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='菜单节点表';

-- ----------------------------
-- Records of lay_menu
-- ----------------------------