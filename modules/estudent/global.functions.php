<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

function createStudentTable($add_config)
{
	global $module_file, $module_data, $module_name, $db;
	
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
		
		if( !$db->sql_query($_sql_string) ) return false;
		else
		{
			file_put_contents(NV_ROOTDIR . '/modules/' . $module_file . '/data/student_table_data.txt', $table . PHP_EOL, FILE_APPEND | LOCK_EX);
			nv_del_moduleCache( $module_name );
			return true;
		}
	}
	else return true;
}

function VnpGetStudent($studentid)
{
	global $db,$module_data, $admin_info;
	
	if( intval($studentid) > 0 )
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_student` WHERE `student_id`=" . intval( $studentid );
		$result = $db->sql_query( $sql );
	
		if( $db->sql_numrows( $result ) == 1 )
		{
			return $db->sql_fetchrow( $result );
		}
		else return false;
	}
	else return false;
}

function VnpUpdateStudent( $student, $table = '' )
{
	global $db,$module_data, $admin_info;
	
	$sql = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "_student" . $table . "` SET
			`userid` = " . intval( $student['userid'] ) . ",
			`faculty_id` = " . intval( $student['faculty_id'] ) . ",
			`level_id` = " . intval( $student['level_id'] ) . ",
			`base_class_id` =" . $db->dbescape( $student['base_class_id'] ) . ",
            `student_name` =" . $db->dbescape( $student['student_name'] ) . ",
			`student_code` =" . $db->dbescape( $student['student_code'] ) . ",
			`course_id` =" . $db->dbescape( $student['course_id'] ) . ",
			`year` = " . intval( $student['year'] ) . ",
			`birthday` = " . intval( $student['birthday'] ) . ",
			`hometown` =" . $db->dbescape( $student['hometown'] ) . ",
			`address` =" . $db->dbescape( $student['address'] ) . ",
			`email` =" . $db->dbescape( $student['email'] ) . ",
			`phone` =" . $db->dbescape( $student['phone'] ) . ",
            `student_desc`= " .  $db->dbescape( $student['student_desc'] ) . ",
			`edit_time`=" . NV_CURRENTTIME . " 
	WHERE `student_id` =" . $student['student_id'];
	$db->sql_query( $sql );
	
	if( $db->sql_affectedrows() > 0 )
	{
		return true;
	}
	else return false;
}

function VnpAddStudent( $student, $table = '' )
{
	global $db,$module_data, $admin_info;
	
	if( ! empty( $student['birthday'] ) and preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $student['birthday'], $m ) )
	{
		$student['birthday'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	
	( isset($student['student_id']) && $student['student_id'] > 0 ) ? $_stdID = $student['student_id'] : $_stdID = 'NULL';
	
	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_student" . $table . "` VALUES (
            " . $_stdID . ",
			" . intval( $student['userid'] ) . ",
			" . intval( $student['faculty_id'] ) . ",
			" . intval( $student['level_id'] ) . ",
			" . $db->dbescape( $student['base_class_id'] ) . ",
			" . $db->dbescape( $student['student_name'] ) . ",
			" . $db->dbescape( $student['student_code'] ) . ",
			" . $db->dbescape( $student['course_id'] ) . ",
			" . intval( $student['year'] ) . ",
			'',
			'',
			" . intval( $student['birthday'] ) . ",
			" . $db->dbescape( $student['hometown'] ) . ",
			" . $db->dbescape( $student['address'] ) . ",
			" . $db->dbescape( $student['email'] ) . ",
			" . $db->dbescape( $student['phone'] ) . ",
			'',
			'',
			'',
			'',
			'',
			1,
			" . $db->dbescape( $student['student_desc'] ) . ",
			0,
			" . $admin_info['admin_id'] . ",
			" . NV_CURRENTTIME . ",
			" . NV_CURRENTTIME . ", 1);";
	if( $_id = $db->sql_query_insert_id( $sql ) ) return $_id;
	else return 0;
}

function VnpDeleteStudent( $stdID, $table = '' )
{
	global $db, $module_data;
	
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_student" . $table . "` WHERE `student_id`=" . intval($stdID);
	if( $db->sql_query($sql) ) return true;
	else return false;
}

?>