<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

//p($userData['type']);
if( ! defined( 'NV_IS_MOD_ESTUDENT' ) || $userData['type'] != 'teacher'  || !in_array($userData['teacher_type'], array(1,2,3)) ) die( 'Stop!!!' );

$std_id = $nv_Request->get_int( 'stdid', 'post,get', 0 );
$class_id = $nv_Request->get_int( 'class_id', 'post,get', 0 );
$value = $nv_Request->get_int( 'value', 'post,get', 0 );
$time = $nv_Request->get_title( 'time', 'post,get', '' );

if( !empty( $time ) and preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $time, $m ) )
{
	$time = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
}
else
{
	$time = NV_CURRENTTIME;
}


$sql = "SELECT `student_id`, `faculty_id`, `year`, `off_class_count` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_student` WHERE ( `class_ids`='" . $class_id . "' OR `class_ids` REGEXP '^" . $class_id . "\\\,' OR `class_ids` REGEXP '\\\," . $class_id . "\\\,' OR `class_ids` REGEXP '\\\," . $class_id . "\$') AND `student_id`=" . $std_id;

$result = $db->sql_query( $sql );

$stt = 'not';
	
if( $db->sql_numrows( $result ) > 0 )
{
	$student = $db->sql_fetchrow( $result );
	$roll_call = $student['off_class_count'];
	if( empty($roll_call)) $roll_call = array();
	else $roll_call = unserialize($roll_call);
	$roll_call[$class_id][] = $time . '-' . $value;
	$roll_call = serialize($roll_call);
	
	$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_student` SET
	`off_class_count`=" . $db->dbescape($roll_call) . "
	WHERE `student_id`=" . $student['student_id'];
	//die();
	
	if($db->sql_query( $sql ))
	{
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_student_" . $student['faculty_id'] . "_" . $student['year'] . "` SET
	`off_class_count`=" . $db->dbescape($roll_call) . "
	WHERE `student_id`=" . $student['student_id'];
		$result = $db->sql_query( $sql );
		$stt = 'ok_' . $student['student_id'];
	}
	else $stt = $sql;
}

echo $stt;
exit();

?>