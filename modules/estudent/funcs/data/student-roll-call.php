<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_MOD_ESTUDENT' ) && !defined('IS_STUDENT') ) die( 'Stop!!!' );

$xtpl = new XTemplate( "student_roll_call.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_student_" . $userData['faculty_id'] . "_" . $userData['year'] . "` WHERE `student_id`=" . $userData['student_id'];
$result = $db->sql_query( $sql );
	
if( $db->sql_numrows( $result ) ==1 )
{
	$student = $db->sql_fetchrow( $result );
	
	if( !empty($student['off_class_count']) )
	{
		$classIDs = $classData = array();
		$roll_call = unserialize($student['off_class_count']);
		foreach( $roll_call as $classID => $roll_data )
		{
			$classIDs[] = $classID;
			//p($roll_data);
		}
		$classIDs = implode(',', $classIDs);
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_class` WHERE `class_id` IN(" . $classIDs . ") ORDER BY `class_id` DESC";
		$result = $db->sql_query( $sql );
		
		if( $db->sql_numrows( $result ) > 0 )
		{
			while($class = $db->sql_fetchrow( $result ) )
			{
				$class['miss_class'] = 0;
				$class['miss_class_desc'] = '';
				$class['test_status'] = 'Được thi';
				$i = 0;
				foreach( $roll_call[$class['class_id']] as $_roll_call )
				{
					$_roll_data = explode('-', $_roll_call);
					if( $_roll_data[1] == 1 )
					{
						$i += 0.5;
						$class['miss_class_desc'][] = date( "d/m/Y", $_roll_data[0] ) . ' muộn học';
					}
					elseif( $_roll_data[1] == 0 )
					{
						$i += 1;
						$class['miss_class_desc'][] = date( "d/m/Y", $_roll_data[0] ) . ' nghỉ học';
					}
				}
				$class['miss_class'] = $i;
				$class['miss_class_desc'] = implode('<br />', $class['miss_class_desc'] );
				if( $class['miss_class'] > $max_miss_class ) $class['test_status'] = 'Đình chỉ thi';
				$sbIDs = explode(',', $class['subject_id']);
				foreach( $sbIDs as $sbID )
				{
					if( !empty($sbID) ) $class['subject_id'] = $sbID;
				}
				$class['subject_name'] = $globalTax['subject'][$class['subject_id']]['subject_name'];
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