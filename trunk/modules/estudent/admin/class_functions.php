<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) || !defined( 'CLASS_FUNCTION' ) ) die( 'Stop!!!' );

$_class = $nv_Request->get_array( 'class', 'post', 'string', array() );
$class = array_merge( $class, $_class );

if( !empty( $class['class_week'] ) ) $class['class_week'] = implode(',', $class['class_week'] );

if( !empty( $class['class_time'] ) && is_array( $class['class_time'] ) )
{
	$classTime = array();
	foreach( $class['class_time'] as $day => $_class_times )
	{
		if( !empty( $_class_times ) && is_array( $_class_times ) )
		{
			foreach( $_class_times as $_time )
			{
				$classTime[] = $day . '_' . $_time;
			}
		}
	}
	$class['class_time'] = implode(',', $classTime);
}

if( $classid )
{
	$sql = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "_class` SET
			`term_id` =  " . intval( $class['term_id'] ) . ",
			`subject_id` =  " . $db->dbescape( $class['subject_id'] ) . ",
			`faculty_id` =  " . intval( $class['faculty_id'] ) . ",
			`teacher_id` =  " . $db->dbescape( $class['teacher_id'] ) . ",
			`class_name` = " . $db->dbescape( $class['class_name'] ) . ",
			`class_code` = " . $db->dbescape( $class['class_code'] ) . ",
			`class_week` = " . $db->dbescape( $class['class_week'] ) . ",
			`class_time` = " . $db->dbescape( $class['class_time'] ) . ",
			`class_room` = " . $db->dbescape( $class['class_room'] ) . ",
			`class_type_id` = " . intval( $class['class_type'] ) . ",
			`test_type_id` = " . intval( $class['test_type'] ) . ",
			`number_student` = " . intval( $class['number_student'] ) . ",
			`edit_time`=" . NV_CURRENTTIME . " 
	WHERE `class_id` =" . $classid;
	$db->sql_query( $sql );
}
else
{
	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_class` VALUES (
            NULL,
			" . intval( $class['term_id'] ) . ",
			" . $db->dbescape( $class['subject_id'] ) . ",
			" . $db->dbescape( $class['teacher_id'] ) . ",
			" . intval( $class['faculty_id'] ) . ",
			" . $db->dbescape( $class['class_name'] ) . ",
			" . $db->dbescape( $class['class_code'] ) . ",
			" . $db->dbescape( $class['class_week'] ) . ",
			" . $db->dbescape( $class['class_time'] ) . ",
			" . $db->dbescape( $class['class_room'] ) . ",
			" . intval( $class['class_type'] ) . ",
			" . intval( $class['test_type'] ) . ",
			0,
			0,
			" . intval( $class['number_student'] ) . ",
			'',
			" . $admin_info['admin_id'] . ",
			" . NV_CURRENTTIME . ",
			" . NV_CURRENTTIME . ", 1);";
	$_id = $db->sql_query_insert_id( $sql );
}

nv_del_moduleCache( $module_name );

if( $db->sql_affectedrows() > 0 )
{
	if( $classid )
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit class', "Term ID:  " . $classid, $admin_info['userid'] );
	}
	else
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Add class', "Term ID: ", $admin_info['userid'] );
	}
}
else
{
	$msg = array( 'content' => $lang_module['action_not'], 'type' => 'error' );
}

?>