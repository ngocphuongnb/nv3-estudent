<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) || !defined( 'STUDENT_FUNCTION' ) ) die( 'Stop!!!' );

$_student = $nv_Request->get_typed_array( 'student', 'post', 'string', array() );
$student = array_merge($_student, $student);
$studentid = $nv_Request->get_int( 'studentid', 'post,get', 0 );
//p($student);

$logMsg = array();

if( $studentid )
{
	$student['student_id'] = $studentid;
	$_backUpStudent = VnpGetStudent($studentid);
	if( VnpUpdateStudent( $student ) )
	{
		$table = '_' . $student['faculty_id'] . '_' . $student['year'];
		if( VnpUpdateStudent( $student, $table ) )
		{
			$msg = array( 'content' => $lang_module['success_update_student'] . ' - ' . $student['student_id'] . ' - ' . $student['student_name'], 'type' => 'success' );
			$logMsg = array( 'Success update student', $lang_module['success_update_student'] . ' - '  . $student['student_id'] . ' - ' . $student['student_name'] );
		}
		else
		{
			VnpUpdateStudent( $_backUpStudent );
			
			$msg = array( 'content' => $lang_module['error_update_student'] . ' - '  . $student['student_id'] . ' - ' . $student['student_name'] . ' - ' . $_backUpStudent['faculty_id'] . '_' . $_backUpStudent['year'], 'type' => 'error' );
			$logMsg = array( 'Error update student', $lang_module['error_update_student'] . ' - '  . $student['student_id'] . ' - ' . $student['student_name'] . ' - ' . $_backUpStudent['faculty_id'] . '_' . $_backUpStudent['year'] );
		}
	}
	else
	{
		$msg = array( 'content' => $lang_module['error_update_student'] . ' - '  . $student['student_id'] . ' - ' . $student['student_name'] . ' - student', 'type' => 'error' );
			$logMsg = array( 'Error update student', $lang_module['error_update_student'] . ' - '  . $student['student_id'] . ' - ' . $student['student_name'] . ' - student' );
	}
}
else
{
	$stdID = VnpAddStudent( $student );
	if( $stdID > 0 )
	{
		$student['student_id'] = $stdID;
		$_stdTable = '_' . $add_config['faculty_id'] . '_' . $add_config['year'];
		if( $stdID != VnpAddStudent( $student, $_stdTable ) )
		{
			if( VnpDeleteStudent( $stdID ) )
			{
				$msg = array( 'content' => $lang_module['cannot_add_student_faculty_table'] . $_stdTable . ' - ' . $globalTax['faculty'][$add_config['faculty_id']]['faculty_name'] . ' - ' . $globalTax['year'][$add_config['year']]['year'], 'type' => 'error' );
				$logMsg = array( 'Add student failed', $lang_module['cannot_add_student_faculty_table'] . $_stdTable . ' - ' . $globalTax['faculty'][$add_config['faculty_id']]['faculty_name'] . ' - ' . $globalTax['year'][$add_config['year']]['year'] );
			}
		}
		else
		{
			$msg = array( 'content' => $lang_module['add_student_success'] . ' - ' . $stdID . ' - ' . $student['student_name'], 'type' => 'success' );
			
			$logMsg = array( $lang_module['add_student_success'], $stdID . ' - ' . $student['student_name'] );
		}
	}
	else
	{
		$msg = array( 'content' => $lang_module['cannot_add_student_faculty_table'] . ' - student', 'type' => 'error' );
		$logMsg = array( 'Add student failed', $lang_module['cannot_add_student_faculty_table'] . ' - student' );
	}
}

nv_insert_logs( NV_LANG_DATA, $module_name, $logMsg[0], $logMsg[1], $admin_info['userid'] );

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