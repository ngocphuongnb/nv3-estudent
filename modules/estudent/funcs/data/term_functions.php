<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_MOD_ESTUDENT' ) && !defined('IS_DEAN') ) die( 'Stop!!!' );

$term = $nv_Request->get_typed_array( 'term', 'post', 'string', array() );
$error = array();

$term_weeks = explode( '-', $term['weeks'] );
if( sizeof($term_weeks) !== 2 )
{
	$error[] = $lang_module['term_weeks_error'];
}
else
{
	$term['weeks'] = intval($term_weeks[0]) . '-' . intval($term_weeks[1]);
}
if( $term['term_name'] == '' )
{
	$error[] = $lang_module['term_name_error'];
}
if( $term['year'] == '' )
{
	$error[] = $lang_module['term_year_error'];
}

if( !$error )
{
	if( $termid )
	{
		$sql = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "_term` SET
				`term_name` =" . $db->dbescape( $term['term_name'] ) . ",
				`year` =  " . intval( $term['year'] ) . ",
				`edit_time`=" . NV_CURRENTTIME . ",
				`weeks`=" . $db->dbescape( $term['weeks'] ) . "
		WHERE `term_id` =" . $termid;
		$db->sql_query( $sql );
	}
	else
	{
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_term` VALUES (
				NULL,
				" . intval( $term['year'] ) . ",
				" . $db->dbescape( $term['weeks'] ) . ",
				" . $db->dbescape( $term['term_name'] ) . ",
				" . $admin_info['admin_id'] . ",
				" . NV_CURRENTTIME . ",
				" . NV_CURRENTTIME . ", 1);";
		$_id = $db->sql_query_insert_id( $sql );
	}
}

nv_del_moduleCache( $module_name );

if( $db->sql_affectedrows() > 0 )
{
	if( $termid )
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit term', "Term ID:  " . $termid, $admin_info['userid'] );
	}
	else
	{
		$msg = array( 'content' => $lang_module['action_ok'], 'type' => 'success' );
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Add term', "Term ID: ", $admin_info['userid'] );
	}
}
else
{
	$msg = array( 'content' => $lang_module['action_not'] . implode(PHP_EOL, $error), 'type' => 'error' );
}

?>