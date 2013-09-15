<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

//p($userData['type']);
if( ! defined( 'NV_IS_MOD_ESTUDENT' ) && $userData['type'] != 'teacher'  && !in_array($userData['teacher_type'], array(2,3)) ) die( 'Stop!!!' );

// subject, teacher, class room, student, class
$container = $nv_Request->get_string( 'container', 'post,get', '' );

$search = array(
						'is_search' => false,
						'q' => '',
						'faculty_id' => 0,
						'term_id' => 0,
						'number_student' => '',
						'enter_mark' => 'all',
						'status' => 'all',
						'per_page' => 10,
						'page' => 0,
						);


$_s = $_string_query = $_q = '';
					
if( $nv_Request->get_string( 'search', 'get', '' ) == 1 )
{
	$search['is_search'] = true;
	$search['q'] = $nv_Request->get_string( 'q', 'get', '' );
	$search['faculty_id'] = $userData['faculty_id'];
	$search['term_id'] = $nv_Request->get_int( 'term_id', 'get', 0 );
	$search['number_student'] = $nv_Request->get_string( 'number_student', 'get', 'all' );
	$search['enter_mark'] = $nv_Request->get_string( 'enter_mark', 'get', 'all' );
	$search['status'] = $nv_Request->get_string( 'status', 'get', 'all' );
	$search['per_page'] = $nv_Request->get_int( 'per_page', 'get', 10 );
	$search['page'] = $nv_Request->get_int( 'page', 'get', 0 );
}
	
$_s = ' WHERE `faculty_id`=' . intval($userData['faculty_id']);
			
if( $search['is_search'] )
{
	$_s = array();
	$_s[] = "`faculty_id`=" . intval($userData['faculty_id']);
	if( $search['term_id'] > 0 )
	{
		$_s[] = "`term_id`=" . intval($search['term_id']);
	}
	if( $search['number_student'] != 'all' && $search['number_student'] != '' )
	{
		$_s[] = "`number_student`=" . intval($search['number_student']);
	}
	if( $search['enter_mark'] != 'all' )
	{
		$_s[] = "`enter_mark`=" . intval($search['enter_mark']);
	}
	if( $search['status'] != 'all' )
	{
		$_s[] = "`status`=" . intval($search['status']);
	}
	if( $search['q'] )
	{
		$_s[] = "`class_name` LIKE '%" . $db->dblikeescape( $search['q'] ) . "%'";
	}
	//if( $search['faculty_id'] > 0 || !empty($search['q']) )
	if( !empty($_s) )
	{
		$_s = "WHERE " . implode(' AND ', $_s );
	}
	else $_s = '';
}
$base_url = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;search=1&amp;per_page=" . $search['per_page'] . "&amp;term_id=" . $search['term_id'] . "&amp;number_student=" . $search['number_student'] . "&amp;enter_mark=" . $search['enter_mark'] . "&amp;status=" . $search['status'] . "&amp;q=" . $search['q'];

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_class`" . $_s . " LIMIT " . $search['page'] . "," . $search['per_page'];

$result = $db->sql_query( $sql );

$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $result_all );
//die('dcdcdc');

$xtpl = new XTemplate( "get_class.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$my_head .= '<script src="' . NV_BASE_SITEURL . 'modules/' . $module_file . '/data/input-toggle.js" /></script>';

if( $db->sql_numrows( $result ) > 0 )
{
	$i = 1;
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$array_status = array( $lang_module['deactive'], $lang_module['active'] );
		$row['class'] = ( ++$i % 2 ) ? " class=\"second\"" : "";
		$row['url_edit'] = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/term-class&amp;action=add&amp;classid=" . $row['class_id'];
		
		$row['term'] = $globalTax['term'][$row['term_id']]['term_name'];
		$_subjectID = explode( ',', $row['subject_id'] );
		$row['subject'] = $globalTax['subject'][$_subjectID[1]]['subject_name'];

		$row['class_status'] = $globalTax['class_reg_status'][$row['status']];
		$row['class_mark'] = getTaxSelectBox( $globalTax['class_mark'], 'class_mark_' . $row['class_id'], $row['enter_mark'], 'change_class_enter_mark_' . $row['class_id'], '', '', 'onchange="vnp_update_class(\'' . $row['class_id'] . '\', \'enter_mark\', this.value);"' );
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.row' );
		$i++;
	}
}
$generate_page = nv_generate_page( $base_url, $all_page, $search['per_page'], $search['page'] );
$xtpl->assign( 'SEARCH_TERM', getTaxSelectBox( 'term', 'term_id', $search['term_id'] ) );
$showNumber = array();
$i = 1;
while( $i <= 20 )
{
	$showNumber[$i] = array( 'value' => $i );
	$i++;
}
$globalTax['class_reg_status']['all'] = $lang_module['all'];
$globalTax['class_mark']['all'] = $lang_module['all'];

$xtpl->assign( 'SHOW_NUMBER', getTaxSelectBox( $showNumber, 'per_page', $search['per_page'], NULL, 'value', 'value' ) );
$xtpl->assign( 'STATUS', getTaxSelectBox( $globalTax['class_reg_status'], 'status', $search['status'] ) );
$xtpl->assign( 'ENTER_MARK', getTaxSelectBox( $globalTax['class_mark'], 'enter_mark', $search['enter_mark']) );

$xtpl->assign( 'SEARCH', $search );
$xtpl->assign( 'PAGE_GEN', $generate_page );

$xtpl->assign( 'FORM_ACTION', $form_action );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'OP', $op . '/term-class' );
$xtpl->assign( 'GLOBAL_CONFIG', $global_config );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'CLASS_INFO_URL', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=get_class_info" );

$contents = vnp_msg($msg);
$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );	

include ( NV_ROOTDIR . '/includes/header.php' );
echo $contents;
include ( NV_ROOTDIR . '/includes/footer.php' );
exit();

?>