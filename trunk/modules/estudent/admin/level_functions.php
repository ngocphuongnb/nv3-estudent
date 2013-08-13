<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) || !defined( 'LEVEL_FUNCTION' ) ) die( 'Stop!!!' );

$level = $nv_Request->get_typed_array( 'level', 'post', 'string', array() );

if( $levelid )
{
	$sql = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "_level` SET
            `level_name` =" . $db->dbescape( $level['level_name'] ) . ",
			`level_alias` =  " . $db->dbescape( $level['level_alias'] ) . ",
            `level_desc`= " .  $db->dbescape( $level['level_desc'] ) . ",
			`edit_time`=" . NV_CURRENTTIME . " 
	WHERE `level_id` =" . $levelid;
	$db->sql_query( $sql );
}
else
{
	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_level` VALUES (
            NULL,
			" . $db->dbescape( $level['level_name'] ) . ",
			" . $db->dbescape( $level['level_alias'] ) . ",
			" . $db->dbescape( $level['level_desc'] ) . ",
			0,
			" . $admin_info['admin_id'] . ",
			" . NV_CURRENTTIME . ",
			" . NV_CURRENTTIME . ", 1);";
	$_id = $db->sql_query_insert_id( $sql );
}

nv_del_moduleCache( $module_name );

if( $db->sql_affectedrows() > 0 )
{
	if( $levelid )
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit level', "Level ID:  " . $levelid, $admin_info['userid'] );
	}
	else
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Add level', "Level ID:  " . $_id, $admin_info['userid'] );
	}
}
else
{
	$msg = array( 'content' => $lang_module['action_not'], 'type' => 'error' );
}

?>