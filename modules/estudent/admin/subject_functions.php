<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) || !defined( 'SUBJECT_FUNCTION' ) ) die( 'Stop!!!' );

$_subject = $nv_Request->get_typed_array( 'subject', 'post', 'string', array() );
$subject = array_merge($subject,$_subject);
$_msg = '';
$_id = 0;

if( $subjectid )
{
	if( !isset($subject['practice_require']) ) $subject['practice_require'] = 0;
	$sql = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "_subject` SET
			`subject_name` =" . $db->dbescape( $subject['subject_name'] ) . ",
			`subject_code` =" . $db->dbescape( $subject['subject_code'] ) . ",
			`subject_alias` =  " . $db->dbescape( $subject['subject_alias'] ) . ",
			`subject_desc`= " .  $db->dbescape( $subject['subject_desc'] ) . ",
			`faculty_id` = " . intval( $subject['faculty_id'] ) . ",
			`practice_require` = " . intval( $subject['practice_require'] ) . ",
			`clpart` = " . intval( $subject['clpart'] ) . ",
			`edit_time`=" . NV_CURRENTTIME . " 
	WHERE `subject_id` =" . $subjectid;
	$db->sql_query( $sql );
}
else
{
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_subject` WHERE `subject_code`=" . $db->dbescape( $subject['subject_code'] );
	$result = $db->sql_query( $sql );
	if( $db->sql_numrows( $result ) == 0 )
	{
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_subject` VALUES (
				NULL,
				" . $admin_info['admin_id'] . ",
				" . $db->dbescape( $subject['subject_name'] ) . ",
				" . $db->dbescape( $subject['subject_code'] ) . ",
				" . $db->dbescape( $subject['subject_alias'] ) . ",
				" . $db->dbescape( $subject['subject_desc'] ) . ",
				" . intval( $subject['faculty_id'] ) . ",
				0,
				" . NV_CURRENTTIME . ",
				" . NV_CURRENTTIME . ",
				" . $subject['practice_require'] . ",
				" . intval( $subject['clpart'] ) . ",
				1);";
		$_id = $db->sql_query_insert_id( $sql );
	}
	else
	{
		$_msg = ': ' . $lang_module['subject_code_existed'];
	}
}

nv_del_moduleCache( $module_name );

if( $db->sql_affectedrows() > 0 )
{
	if( $subjectid )
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit subject', "SUBJECT ID:  " . $subjectid, $admin_info['userid'] );
	}
	else
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Add subject', "SUBJECT ID:  " . $_id, $admin_info['userid'] );
	}
	//Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&op=subject&action=add" );
	//die();
}
else
{
	$msg = array( 'content' => $lang_module['action_not'] . $_msg, 'type' => 'error' );
}

?>