<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) || !defined( 'TERM_FUNCTION' ) ) die( 'Stop!!!' );

$term = $nv_Request->get_typed_array( 'term', 'post', 'string', array() );

if( $termid )
{
	$sql = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "_term` SET
            `term_name` =" . $db->dbescape( $term['term_name'] ) . ",
			`term_alias` =  " . $db->dbescape( $term['term_alias'] ) . ",
            `term_desc`= " .  $db->dbescape( $term['term_desc'] ) . ",
			`edit_time`=" . NV_CURRENTTIME . " 
	WHERE `term_id` =" . $termid;
	$db->sql_query( $sql );
}
else
{
	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_term` VALUES (
            NULL,
			" . $db->dbescape( $term['term_name'] ) . ",
			" . $db->dbescape( $term['term_alias'] ) . ",
			" . $db->dbescape( $term['term_desc'] ) . ",
			0,
			" . $admin_info['admin_id'] . ",
			" . NV_CURRENTTIME . ",
			" . NV_CURRENTTIME . ", 1);";
	$_id = $db->sql_query_insert_id( $sql );
}

nv_del_moduleCache( $module_name );

if( $db->sql_affectedrows() > 0 )
{
	if( $termid )
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit term', "Level ID:  " . $termid, $admin_info['userid'] );
	}
	else
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Add term', "Level ID:  " . $_id, $admin_info['userid'] );
	}
}
else
{
	$msg = array( 'content' => $lang_module['action_not'], 'type' => 'error' );
}

?>