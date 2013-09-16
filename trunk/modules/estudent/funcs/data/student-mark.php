<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_MOD_ESTUDENT' ) && !defined('IS_STUDENT') ) die( 'Stop!!!' );

$xtpl = new XTemplate( "student_mark.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_student_" . $userData['faculty_id'] . "_" . $userData['year'] . "` WHERE `student_id`=" . $userData['student_id'];
$result = $db->sql_query( $sql );

$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $result_all );
	
if( $db->sql_numrows( $result ) ==1 )
{
	$classData = array();
	$classIDs = array();
	$student = $db->sql_fetchrow( $result );
	if( !empty( $student['study_result'] ) )
	{
		$student['study_result'] = unserialize($student['study_result']);
		foreach( $student['study_result'] as $classID => $mark )
		{
			$classIDs[] = $classID;
			$classData[$classID]['mark'] = $mark;
		}
		$classIDs = implode(',', $classIDs);
		
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_class` WHERE `class_id` IN(" . $classIDs . ") ORDER BY `class_id` DESC";
		$result = $db->sql_query( $sql );
		
		if( $db->sql_numrows( $result ) > 0 )
		{
			while($class = $db->sql_fetchrow( $result ) )
			{
				$sbIDs = explode(',', $class['subject_id']);
				foreach( $sbIDs as $sbID )
				{
					if( !empty($sbID) ) $class['subject_id'] = $sbID;
				}
				$class['subject_name'] = $globalTax['subject'][$class['subject_id']]['subject_name'];
				$mark = $classData[$class['class_id']]['mark'];
				$class['time_mark'] = $mark['time'];
				$class['end_mark'] = $mark['end'];
				$xtpl->assign( 'ROW', $class );
				$xtpl->parse( 'main.loop' );
			}
		}
	}
}



$vnp_content = '';
$xtpl->parse( 'main' );
$vnp_content .= $xtpl->text( 'main' );


?>