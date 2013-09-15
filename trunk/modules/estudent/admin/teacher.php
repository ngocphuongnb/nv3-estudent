<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

define( 'TEACHER_FUNCTION', true );
$msg = array();
$form_action = '';

$search = array(
					'is_search' => false,
					'q' => '',
					'teacher_type' => 0,
					'faculty_id' => 0,
					'per_page' => 10,
					'page' => 0
					);
					
if( $nv_Request->get_string( 'search', 'get', '' ) == 1 )
{
	$search['is_search'] = true;
	$search['q'] = $nv_Request->get_string( 'q', 'get', '' );
	$search['faculty_id'] = $nv_Request->get_int( 'faculty_id', 'get', 0 );
	$search['teacher_type'] = $nv_Request->get_int( 'teacher_type', 'get', 0 );
	$search['per_page'] = $nv_Request->get_int( 'per_page', 'get', 10 );
	$search['page'] = $nv_Request->get_int( 'page', 'get', 0 );
}

$xtpl = new XTemplate( "add_teacher.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$teacherid = $nv_Request->get_int( 'teacherid', 'post,get', 0 );
$action = $nv_Request->get_string( 'action', 'get', '' );

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$teacher = $nv_Request->get_typed_array( 'teacher', 'post', 'string', array() );
	require( 'teacher_functions.php' );
}
else
{
	if( $action == '' || $action == 'list' )
	{
		$xtpl = new XTemplate( "teacher.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'GLANG', $lang_global );
		$xtpl->assign( 'ADD_LINK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add" );
		$_s = '';
		
		if( $search['is_search'] )
		{
			$_s = array();
			if( $search['faculty_id'] > 0 )
			{
				$_s[] = "`faculty_id`=" . intval($search['faculty_id']);
			}
			if( $search['teacher_type'] > 0 )
			{
				$_s[] = "`teacher_type`=" . intval($search['teacher_type']);
			}
			if( $search['q'] )
			{
				$_s[] = "`teacher_name` LIKE '%" . $db->dblikeescape( $search['q'] ) . "%'";
			}
			if( $search['faculty_id'] > 0 || !empty($search['q']) || $search['teacher_type'] > 0 )
			{
				$_s = "WHERE " . implode(' AND ', $_s );
			}
			else $_s = '';
		}
		$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;search=1&amp;per_page=" . $search['per_page'] . "&amp;teacher_type=" . $search['teacher_type'] . "&amp;faculty_id=" . $search['faculty_id'] . "&amp;q=" . $search['q'];
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_teacher` " . $_s . " LIMIT " . $search['page'] . "," . $search['per_page'];
		$result = $db->sql_query( $sql );
		
		$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result_all );
	
		if( $db->sql_numrows( $result ) > 0 )
		{
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$array_status = array( $lang_module['deactive'], $lang_module['active'] );
				$row['class'] = ( ++$i % 2 ) ? " class=\"second\"" : "";
				$row['url_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add&amp;teacherid=" . $row['teacher_id'];
				foreach( $array_status as $key => $val )
				{
					$xtpl->assign( 'STATUS', array(
						'key' => $key, //
						'val' => $val, //
						'selected' => ( $key == $row['status'] ) ? " selected=\"selected\"" : "" //
					) );
			
					$xtpl->parse( 'main.row.status' );
				}
				$row['faculty'] = $globalTax['faculty'][$row['faculty_id']]['faculty_name'];
				$xtpl->assign( 'ROW', $row );
				$xtpl->parse( 'main.row' );
			}
		}
	}
	elseif( $action == 'add' )
	{
		$teacherid = $nv_Request->get_int( 'teacherid', 'get', 0 );		
		if( $teacherid == 0 )
		{
			$teacher = array(
				'teacherid' => 0,
				'teacher_name' => '',
				'teacher_alias' => '', 
				'teacher_desc' => '',
				'faculty_id' => 0,
				'teacher_type' => 1
			);
			$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add";
		}
		else
		{
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_teacher` WHERE `teacher_id`=" . $teacherid;
			$result = $db->sql_query( $sql );
		
			if( $db->sql_numrows( $result ) != 1 )
			{
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=add" );
				die();
			}
		
			$teacher = $db->sql_fetchrow( $result );			
			$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add&amp;teacherid=" . $teacherid;			
		}		
	}
}

if( $action == 'add' )
{
	if( ! empty( $teacher['teacher_desc'] ) ) $teacher['teacher_desc'] = nv_htmlspecialchars( $teacher['teacher_desc'] );
		
	if( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
	
	if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
	{
		$teacher['teacher_desc'] = nv_aleditor( "teacher[teacher_desc]", '100%', '300px', $teacher['teacher_desc'] );
	}
	else
	{
		$teacher['teacher_desc'] = "<textarea style=\"width:100%;height:300px\" name=\"teacher[teacher_desc]\">" . $teacher['teacher_desc'] . "</textarea>";
	}
	unset($globalTax['teacher_type'][0]);
	$xtpl->assign( 'FACULTY_SLB', getTaxSelectBox( 'faculty', 'teacher[faculty_id]', $teacher['faculty_id'] ) );
	$xtpl->assign( 'TEACHER_TYPE', getTaxSelectBox( $globalTax['teacher_type'], 'teacher[teacher_type]', $teacher['teacher_type'] ) );
	$xtpl->assign( 'TEACHER', $teacher );
}
else
{
	$generate_page = nv_generate_page( $base_url, $all_page, $search['per_page'], $search['page'] );
	$xtpl->assign( 'SEARCH_FACULTY', getTaxSelectBox( 'faculty', 'faculty_id', $search['faculty_id'] ) );
	$showNumber = array();
	$i = 1;
	while( $i <= 20 )
	{
		$showNumber[$i] = array( 'value' => $i );
		$i++;
	}
	$xtpl->assign( 'SHOW_NUMBER', getTaxSelectBox( $showNumber, 'per_page', $search['per_page'], NULL, 'value', 'value' ) );
	$xtpl->assign( 'TEACHER_TYPE', getTaxSelectBox( $globalTax['teacher_type'], 'teacher_type', $search['teacher_type'] ) );
	$xtpl->assign( 'SEARCH', $search );
	$xtpl->assign( 'PAGE_GEN', $generate_page );
}
$xtpl->assign( 'FORM_ACTION', $form_action );

$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$contents = vnp_msg($msg);
$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . '/includes/header.php' );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . '/includes/footer.php' );

?>