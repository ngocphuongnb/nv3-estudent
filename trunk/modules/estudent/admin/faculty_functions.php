<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) || !defined( 'FACULTY_FUNCTION' ) ) die( 'Stop!!!' );

$faculty = $nv_Request->get_typed_array( 'faculty', 'post', 'string', array() );

if( $facultyid )
{
	$sql = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "_faculty` SET
            `faculty_name` =" . $db->dbescape( $faculty['faculty_name'] ) . ",
			`faculty_alias` =  " . $db->dbescape( $faculty['faculty_alias'] ) . ",
            `faculty_desc`= " .  $db->dbescape( $faculty['faculty_desc'] ) . ",
			`edit_time`=" . NV_CURRENTTIME . " 
	WHERE `faculty_id` =" . $facultyid;
	$db->sql_query( $sql );
}
else
{
	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_faculty` VALUES (
            NULL,
			" . $db->dbescape( $faculty['faculty_name'] ) . ",
			" . $db->dbescape( $faculty['faculty_alias'] ) . ",
			" . $db->dbescape( $faculty['faculty_desc'] ) . ",
			0,
			" . $admin_info['admin_id'] . ",
			" . NV_CURRENTTIME . ",
			" . NV_CURRENTTIME . ", 1);";
	$_id = $db->sql_query_insert_id( $sql );
}

nv_del_moduleCache( $module_name );

if( $db->sql_affectedrows() > 0 )
{
	if( $facultyid )
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit faculty', "Level ID:  " . $facultyid, $admin_info['userid'] );
	}
	else
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Add faculty', "Level ID:  " . $_id, $admin_info['userid'] );
	}
}
else
{
	$msg = array( 'content' => $lang_module['action_not'], 'type' => 'error' );
}

?>