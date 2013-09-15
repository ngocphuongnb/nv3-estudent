<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_MOD_ESTUDENT' ) && !defined('IS_DEAN') ) die( 'Stop!!!' );

$_base_class = $nv_Request->get_typed_array( 'base_class', 'post', 'string', array() );
$base_class = array_merge($_base_class, $base_class);

if( $base_classid )
{
	$sql = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "_base_class` SET
            `base_class_name` =" . $db->dbescape( $base_class['base_class_name'] ) . ",
			`faculty_id` = " . intval( $userData['faculty_id'] ) . ",
			`level_id` = " . intval( $base_class['level_id'] ) . ",
			`base_class_alias` =  " . $db->dbescape( $base_class['base_class_alias'] ) . ",
            `base_class_desc`= " .  $db->dbescape( $base_class['base_class_desc'] ) . ",
			`course_id`= " .  $db->dbescape( $base_class['course_id'] ) . ",
			`teacher_id`= " .  $db->dbescape( $base_class['teacher_id'] ) . ",
			`number_student` = " . intval( $base_class['number_student'] ) . ",
			`edit_time`=" . NV_CURRENTTIME . " 
	WHERE `base_class_id` =" . $base_classid;
	$db->sql_query( $sql );
}
else
{
	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_base_class` VALUES (
            NULL,
			" . intval( $userData['faculty_id'] ) . ",
			" . intval( $base_class['level_id'] ) . ",
			" . $db->dbescape( $base_class['course_id'] ) . ",
			" . $db->dbescape( $base_class['base_class_name'] ) . ",
			" . $db->dbescape( $base_class['base_class_alias'] ) . ",
			" . $db->dbescape( $base_class['base_class_desc'] ) . ",
			" . $db->dbescape( $base_class['teacher_id'] ) . ",
			" . intval( $base_class['number_student'] ) . ",
			0,
			" . $admin_info['admin_id'] . ",
			" . NV_CURRENTTIME . ",
			" . NV_CURRENTTIME . ", 1);";
	$_id = $db->sql_query_insert_id( $sql );
}

nv_del_moduleCache( $module_name );

if( $db->sql_affectedrows() > 0 )
{
	if( $base_classid )
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit base_class', "Base_class ID:  " . $base_classid, $admin_info['userid'] );
	}
	else
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Add base_class', "Base_class ID:  " . $_id, $admin_info['userid'] );
	}
}
else
{
	$msg = array( 'content' => $lang_module['action_not'], 'type' => 'error' );
}

?>