<?php


if( !defined('STUDENT_FUNCTION') ) die('Stop!');

$table_log = file( NV_ROOTDIR . '/modules/' . $module_file . '/data/student_table_data.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );

$table = $add_config['faculty_id'] . '_' . $add_config['year'];

if( !in_array( $table , $table_log ) )
{
	$_sql_string = "CREATE TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_student_" . $table . "` (
	  `student_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  
	  `userid` mediumint(8) NOT NULL DEFAULT '0',
	  `faculty_id` mediumint(8) NOT NULL DEFAULT '0',
	  `level_id` mediumint(8) NOT NULL DEFAULT '0',
	  `base_class_id` varchar(255) NOT NULL,
	  `student_name` varchar(255) NOT NULL,
	  `student_code` varchar(255) NOT NULL,
	  `course_id` varchar(255) NOT NULL,
  	  `year` int(11) NOT NULL DEFAULT '0',
	  
	  `family_name` varchar(255),
	  `last_name` varchar(255),
	  `birthday` int(11) NOT NULL DEFAULT '0',
	  `hometown` varchar(255) NOT NULL,
	  `address` varchar(255) NOT NULL,
	  `email` varchar(255) NOT NULL,
	  `phone` varchar(255) NOT NULL,
	  
	  `study_result` longtext NOT NULL,
	  `subject_registered` longtext NOT NULL,
	  `class_registered` longtext NOT NULL,
	  `subject_log` longtext NOT NULL,
	  `off_class_count` longtext NOT NULL,
	  
	  `study_status` int(11) NOT NULL DEFAULT '0',
	  
	  `student_desc` mediumtext NOT NULL,
	  `weight` smallint(4) NOT NULL DEFAULT '0',
	  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	  `add_time` int(11) NOT NULL DEFAULT '0',
	  `edit_time` int(11) NOT NULL DEFAULT '0',
	  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
	  PRIMARY KEY (`student_id`),
	  UNIQUE KEY `userid` (`userid`),
	  UNIQUE KEY `student_code` (`student_code`)
	) ENGINE=MyISAM";
	
	if( !$db->sql_query($_sql_string) ) die($_sql_string);
	else
	{
		file_put_contents(NV_ROOTDIR . '/modules/' . $module_file . '/data/student_table_data.txt', $table . PHP_EOL, FILE_APPEND | LOCK_EX);
		nv_del_moduleCache( $module_name );
	}
}

?>