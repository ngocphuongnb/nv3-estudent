<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_MOD_ESTUDENT' ) && $userData['type'] != 'teacher'  && !in_array($userData['teacher_type'], array(2,3)) ) die( 'Stop!!!' );

$listid = $nv_Request->get_string( 'listid', 'post', '' );
$action = $nv_Request->get_string( 'action', 'post', '' );

if( !empty($listid) )
{
	if( $action == 'delete' )
	{		
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_base_class` WHERE `faculty_id`= " . $userData['faculty_id'] . " AND `base_class_id` IN (" . $listid . ")";
		if($db->sql_query( $sql ))
		{
			$contents = "OK_" . $lang['action_ok'];
		}
		else $contents = "NOT_" . $lang['action_not'];
	}
	elseif( $action == 'status' )
	{
		$sql = "SELECT `status` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_base_class` WHERE `faculty_id`= " . $userData['faculty_id'] . " AND `base_class_id`=" . intval($listid);
		$result = $db->sql_query( $sql );
		$numrows = $db->sql_numrows( $result );
		if( $numrows != 1 ) die( 'NO_' . $listid );
		
		$new_status = $nv_Request->get_bool( 'new_status', 'post' );
		$new_status = ( int )$new_status;
		
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_base_class` SET `status`=" . $new_status . " WHERE `faculty_id`= " . $userData['faculty_id'] . " AND `base_class_id`=" . intval($listid);
		$db->sql_query( $sql );
		if($db->sql_query( $sql ))
		{
			$contents = "OK_" . $lang['action_ok'];
			nv_del_moduleCache( $module_name );
		}
		else $contents = "NOT_" . $lang['action_not'];
	}
}
else $contents = "NOT_" . $lang['action_not'];

include ( NV_ROOTDIR . '/includes/header.php' );
echo $contents;
include ( NV_ROOTDIR . '/includes/footer.php' );

?>