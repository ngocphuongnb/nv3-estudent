<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) || !defined( 'TEACHER_FUNCTION' ) ) die( 'Stop!!!' );

$_teacher = $nv_Request->get_typed_array( 'teacher', 'post', 'string', array() );
$teacher = array_merge($_teacher, $teacher);

if( $teacherid )
{
	$sql = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "_teacher` SET
			`userid` = " . intval( $teacher['userid'] ) . ",
            `teacher_name` =" . $db->dbescape( $teacher['teacher_name'] ) . ",
			`faculty_id` = " . intval( $teacher['faculty_id'] ) . ",
			`teacher_alias` =  " . $db->dbescape( $teacher['teacher_alias'] ) . ",
            `teacher_desc`= " .  $db->dbescape( $teacher['teacher_desc'] ) . ",
			`edit_time`=" . NV_CURRENTTIME . " 
	WHERE `teacher_id` =" . $teacherid;
	$db->sql_query( $sql );
}
else
{
	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_teacher` VALUES (
            NULL,
			" . intval( $teacher['userid'] ) . ",
			" . intval( $teacher['faculty_id'] ) . ",
			" . $db->dbescape( $teacher['teacher_name'] ) . ",
			" . $db->dbescape( $teacher['teacher_alias'] ) . ",
			" . $db->dbescape( $teacher['teacher_desc'] ) . ",
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
	if( $teacherid )
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit teacher', "Teacher ID:  " . $teacherid, $admin_info['userid'] );
	}
	else
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Add teacher', "Teacher ID:  " . $_id, $admin_info['userid'] );
	}
}
else
{
	$msg = array( 'content' => $lang_module['action_not'], 'type' => 'error' );
}

?>