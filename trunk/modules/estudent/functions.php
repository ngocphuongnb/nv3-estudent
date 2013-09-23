<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_ESTUDENT', true );
include NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

$userData = checkUserPosition($user_info['userid']);
$userInfo = array();
if( $op == 'manage' )
{
	//p(checkUserPosition(3));
}

$userInfo['type'] = $userData['type'];
if( $userData['type'] == 'teacher' )
{
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_class` WHERE ( `teacher_id`='" . $userData['teacher_id'] . "' OR `teacher_id` REGEXP '^" . $userData['teacher_id'] . "\\\,' OR `teacher_id` REGEXP '\\\," . $userData['teacher_id'] . "\\\,' OR `teacher_id` REGEXP '\\\," . $userData['teacher_id'] . "\$') AND `faculty_id`=" . $userData['faculty_id'] . "  AND `enter_mark`=1";
	$result = $db->sql_query( $sql );
	$required_mark = $db->sql_numrows( $result );
	
	
	$userInfo['info'] = 'Khoa: ' . $globalTax['faculty'][$userData['faculty_id']]['faculty_name'];

	$userInfo['type'] = $lang_module['teacher'];
	$userInfo['name'] = $userData['teacher_name'];
	$userInfo['mark']['required_mark'] = 0;
	if( $required_mark > 0 )
	{
		$userInfo['mark']['required_mark'] = $required_mark;
		$userInfo['mark']['required_mark_link'] = NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . '=manage/mark/';
	}
}
elseif( $userData['type'] == 'student' )
{
	$base_class = getBaseClass($userData['base_class_id']);
	$base_class = $base_class[$userData['base_class_id']];
	$userInfo['info'] = 'Lớp: ' . $base_class['base_class_name'] . '<br /> Khoa: ' . $globalTax['faculty'][$userData['faculty_id']]['faculty_name'];
	$userInfo['type'] = $lang_module['student'];
	$userInfo['name'] = $userData['student_name'];
}
else
{
	$userInfo['info'] = 'Vui lòng đăng nhập để sử dụng chức năng này!';
	$userInfo['type'] = 'Khách';
	$userInfo['name'] = 'Khách';
}



?>