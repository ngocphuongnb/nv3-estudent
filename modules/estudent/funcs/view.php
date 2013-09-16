<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_MOD_ESTUDENT' ) ) die( 'Stop!!!' );

$contents = '';

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];

$menu = array();
$template_file = '';
$vnp_content = '';

if($userData['type'] == 'student')
{
	$action = ( isset($array_op[1]) && !empty($array_op[1]) ) ? $array_op[1] : '';
	if( !empty($action) && in_array( $action, array('time-table', 'mark', 'roll-call') ) )
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
		$menu[$action]['active'] = 'active';
		include( NV_ROOTDIR . '/modules/' . $module_file . '/funcs/data/student-' . $action . '.php');
	}
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