<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

//p($userData['type']);
if( ! defined( 'NV_IS_MOD_ESTUDENT' ) || $userData['type'] != 'teacher'  || !in_array($userData['teacher_type'], array(2,3)) ) die( 'Stop!!!' );

// subject, teacher, class room, student, class

$class_id = $nv_Request->get_int( 'class_id', 'post,get', 0 );

if( $class_id > 0 )
{
	$sql = "SELECT `class_id`, `class_name`, `class_week`, `class_time`, `subject_id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_class` WHERE `faculty_id`= " . $userData['faculty_id'] . " AND `class_id`=" . intval($class_id);
	$result = $db->sql_query( $sql );
	$numrows = $db->sql_numrows( $result );
	$class = $db->sql_fetchrow( $result );
	
	$subject_ids = explode(',', $class['subject_id']);
	foreach( $subject_ids as $subjectid )
	{
		if( !empty($subjectid) ) $class['subject_id'] = $subjectid;
	}
	$subject = $globalTax['subject'][$class['subject_id']];
	
	$subject['subject_link'] = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=manage/subject&amp;action=add&amp;subjectid=" . $subject['subject_id'];
	$class = array_merge($class, $subject);
	
	$class['class_link'] = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=manage/term-class&amp;action=add&amp;classid=" . $class['class_id'];
	
	$return = array( 'status' => 'ok', 'data' => $class );
}
else $return = array( 'status' => 'not', 'data' => '' );
echo json_encode($return);
die();

?>