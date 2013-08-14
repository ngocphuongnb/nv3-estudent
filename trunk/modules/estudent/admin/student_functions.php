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

if( $studentid )
{
	$sql = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "_student` SET
            `student_name` =" . $db->dbescape( $student['student_name'] ) . ",
			`faculty_id` = " . intval( $student['faculty_id'] ) . ",
			`student_alias` =  " . $db->dbescape( $student['student_alias'] ) . ",
            `student_desc`= " .  $db->dbescape( $student['student_desc'] ) . ",
			`edit_time`=" . NV_CURRENTTIME . " 
	WHERE `student_id` =" . $studentid;
	$db->sql_query( $sql );
}
else
{
	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_student` VALUES (
            NULL,
			" . intval( $student['faculty_id'] ) . ",
			" . $db->dbescape( $student['student_name'] ) . ",
			" . $db->dbescape( $student['student_alias'] ) . ",
			" . $db->dbescape( $student['student_desc'] ) . ",
			'',
			0,
			" . $admin_info['admin_id'] . ",
			" . NV_CURRENTTIME . ",
			" . NV_CURRENTTIME . ", 1);";
	$_id = $db->sql_query_insert_id( $sql );
}

nv_del_moduleCache( $module_name );

if( $db->sql_affectedrows() > 0 )
{
	if( $studentid )
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit student', "Teacher ID:  " . $studentid, $admin_info['userid'] );
	}
	else
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Add student', "Teacher ID:  " . $_id, $admin_info['userid'] );
	}
}
else
{
	$msg = array( 'content' => $lang_module['action_not'], 'type' => 'error' );
}

?>