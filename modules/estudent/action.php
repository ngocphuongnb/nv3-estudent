<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_teacher`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_level`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_subject`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_faculty`;";

$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_term`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_class`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_class_type`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_test_type`;";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_teacher` (
  `teacher_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `faculty_id` mediumint(8),
  `teacher_name` varchar(255) NOT NULL,
  `teacher_alias` varchar(255) NOT NULL,
  `teacher_desc` mediumtext NOT NULL,
  `teacher_task` mediumtext NOT NULL,
  `weight` smallint(4) NOT NULL DEFAULT '0',
  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `edit_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`teacher_id`),
  UNIQUE KEY `teacher_alias` (`teacher_alias`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_level` (
  `level_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `level_name` varchar(255) NOT NULL,
  `level_alias` varchar(255) NOT NULL,
  `level_desc` mediumtext NOT NULL,
  `weight` smallint(4) NOT NULL DEFAULT '0',
  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `edit_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`level_id`),
  UNIQUE KEY `level_alias` (`level_alias`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_subject` (
  `subject_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `faculty_id` mediumint(8),
  `subject_name` varchar(255) NOT NULL,
  `subject_code` varchar(255) NOT NULL,
  `subject_alias` varchar(255) NOT NULL,
  `subject_desc` mediumtext NOT NULL,
  `weight` smallint(4) NOT NULL DEFAULT '0',
  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `edit_time` int(11) NOT NULL DEFAULT '0',
  `practice_require` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `clpart` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`subject_id`),
  UNIQUE KEY `subject_alias` (`subject_alias`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_faculty` (
  `faculty_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `faculty_name` varchar(255) NOT NULL,
  `faculty_alias` varchar(255) NOT NULL,
  `faculty_desc` mediumtext NOT NULL,
  `weight` smallint(4) NOT NULL DEFAULT '0',
  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `edit_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`faculty_id`),
  UNIQUE KEY `faculty_alias` (`faculty_alias`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_term` (
  `term_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `year` int(11) NOT NULL DEFAULT '0',
  `weeks` varchar(255) NOT NULL,
  `term_name` varchar(255) NOT NULL,
  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `edit_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  UNIQUE KEY `term_name` (`term_name`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_class_type` (
  `class_type_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `class_type_name` varchar(255) NOT NULL,
  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `edit_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`class_type_id`),
  UNIQUE KEY `class_type_name` (`class_type_name`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_test_type` (
  `test_type_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `test_type_name` varchar(255) NOT NULL,
  `require_mark` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `edit_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`test_type_id`),
  UNIQUE KEY `test_type_name` (`test_type_name`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_class` (
  `class_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `subject_id` mediumint(8),
  `teacher_id` mediumint(8),
  `faculty_id` mediumint(8),
  `teacher_id` mediumint(8),
  `class_name` varchar(255),
  `class_code` varchar(255) NOT NULL,
  `class_week` varchar(255),
  `class_time` varchar(255),
  `class_room` int(11) NOT NULL DEFAULT '0',
  `class_type_id` int(11) NOT NULL DEFAULT '0',
  `test_type_id` int(11) NOT NULL DEFAULT '0',
  `enter_mark` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `registered_student` int(11) NOT NULL DEFAULT '0',
  `number_student` int(11) NOT NULL DEFAULT '0',
  `student_data` longtext,
  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `edit_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`class_id`),
  UNIQUE KEY `class_name` (`class_name`)
) ENGINE=MyISAM";

?>