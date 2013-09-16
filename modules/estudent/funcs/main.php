<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_MOD_ESTUDENT' ) ) die( 'Stop!!!' );

$contents = '';

$menu = array();
$template_file = '';
$vnp_content = '';

if($userData['type'] == 'teacher')
{
	$menu = array();
	if( $userData['teacher_type'] == 3 || $userData['teacher_type'] == 2 )
	{
		define('IS_DEAN', true);
		/*$menu['time-table'] = array(	'title' => $lang_module['set_time_table'],
												'link' => NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . '=manage/time-table/',
												'active' => ''
											);*/
		$menu['term-class'] = array(	'title' => $lang_module['class_management'],
												'link' => NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . '=manage/term-class/',
												'active' => ''
											);
		$menu['base-class'] = array(	'title' => $lang_module['base_class_management'],
												'link' => NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . '=manage/base-class/',
												'active' => ''
											);
		$menu['subject'] = array(	'title' => $lang_module['subject_management'],
													'link' => NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . '=manage/subject/',
													'active' => ''
												);
	}
	if( $userData['teacher_type'] == 3 || $userData['teacher_type'] == 2 || $userData['teacher_type'] == 1 )
	{
		define('IS_TEACHER', true);
		$menu['mark'] = array(	'title' => $lang_module['class_mark'],
												'link' => NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . '=manage/mark/',
												'active' => ''
											);
		$menu['roll-call'] = array(	'title' => $lang_module['roll_call'],
													'link' => NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . '=manage/roll-call/',
													'active' => ''
												);
	}
}
elseif($userData['type'] == 'student')
{
	$menu = array();
	define('IS_STUDENT', true);
	$menu['time-table'] = array(	'title' => 'Xem thời khóa biểu',
											'link' => NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . '=view/time-table/',
											'active' => ''
										);
	$menu['mark'] = array(	'title' => 'Tra điểm',
											'link' => NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . '=view/mark/',
											'active' => ''
										);
	$menu['roll-call'] = array(	'title' => 'Thông tin điểm danh',
											'link' => NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . '=view/roll-call/',
											'active' => ''
										);
}
else
{
	$contents = vnp_error($lang_module['permission_denined']);
}

$contents = vnp_page_content($menu, $vnp_content);

include ( NV_ROOTDIR . '/includes/header.php' );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . '/includes/footer.php' );

?>