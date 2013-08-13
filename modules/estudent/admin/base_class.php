<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

define( 'BASE_CLASS_FUNCTION', true );
$msg = array();
$form_action = '';

$search = array(
					'is_search' => false,
					'q' => '',
					'faculty_id' => 0,
					'course_id' => 0,
					'per_page' => 10,
					'page' => 0
					);
					
if( $nv_Request->get_string( 'search', 'get', '' ) == 1 )
{
	$search['is_search'] = true;
	$search['q'] = $nv_Request->get_string( 'q', 'get', '' );
	$search['faculty_id'] = $nv_Request->get_int( 'faculty_id', 'get', 0 );
	$search['course_id'] = $nv_Request->get_string( 'course_id', 'get', '' );
	$search['per_page'] = $nv_Request->get_int( 'per_page', 'get', 10 );
	$search['page'] = $nv_Request->get_int( 'page', 'get', 0 );
}

$xtpl = new XTemplate( "add_base_class.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$base_classid = $nv_Request->get_int( 'base_classid', 'post,get', 0 );
$action = $nv_Request->get_string( 'action', 'get', '' );

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$base_class = $nv_Request->get_typed_array( 'base_class', 'post', 'string', array() );
	require( 'base_class_functions.php' );
}
else
{
	if( $action == '' || $action == 'list' )
	{
		$xtpl = new XTemplate( "base_class.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
			if( $search['course_id'] )
			{
				$_s[] = "`course_id`=" . $db->dbescape($search['course_id']);
			}
			if( $search['q'] )
			{
				$_s[] = "`base_class_name` LIKE '%" . $db->dblikeescape( $search['q'] ) . "%'";
			}
			if( !empty( $_s ) )
			{
				$_s = "WHERE " . implode(' AND ', $_s );
			}
			else $_s = '';
		}
		$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;search=1&amp;per_page=" . $search['per_page'] . "&amp;faculty_id=" . $search['faculty_id'] . "&amp;q=" . $search['q'];
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_base_class` " . $_s . " LIMIT " . $search['page'] . "," . $search['per_page'];
		$result = $db->sql_query( $sql );
		
		$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result_all );
	
		if( $db->sql_numrows( $result ) > 0 )
		{
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$array_status = array( $lang_module['deactive'], $lang_module['active'] );
				$row['class'] = ( ++$i % 2 ) ? " class=\"second\"" : "";
				$row['url_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add&amp;base_classid=" . $row['base_class_id'];
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
				$row['course'] = $globalTax['course'][$row['course_id']]['course_name'];
				$xtpl->assign( 'ROW', $row );
				$xtpl->parse( 'main.row' );
			}
		}
	}
	elseif( $action == 'add' )
	{
		$base_classid = $nv_Request->get_int( 'base_classid', 'get', 0 );		
		if( $base_classid == 0 )
		{
			$base_class = array(
				'base_classid' => 0,
				'base_class_name' => '',
				'base_class_alias' => '', 
				'base_class_desc' => '',
				'faculty_id' => 0,
				'level_id' => 0,
				'number_student' => 0,
				'course_id' => '',
			);
			$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add";
		}
		else
		{
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_base_class` WHERE `base_class_id`=" . $base_classid;
			$result = $db->sql_query( $sql );
		
			if( $db->sql_numrows( $result ) != 1 )
			{
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=add" );
				die();
			}
		
			$base_class = $db->sql_fetchrow( $result );	
			
			if( !empty( $base_class['teacher_id'] ) )
			{
				$_teacher_title = array();
				$base_class_teacher_id = explode(',', $base_class['teacher_id']);
				foreach( $base_class_teacher_id as $_tcid )
				{
					if( !empty($_tcid) )
					$_teacher_title[] = '<li>' . $_tcid . ' - ' . $globalTax['teacher'][$_tcid]['teacher_name'] . '</li>';
				}
				$base_class['teacher_title'] = implode(PHP_EOL, $_teacher_title);
			}
					
			$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add&amp;base_classid=" . $base_classid;			
		}		
	}
}

if( $action == 'add' )
{
	if( ! empty( $base_class['base_class_desc'] ) ) $base_class['base_class_desc'] = nv_htmlspecialchars( $base_class['base_class_desc'] );
		
	if( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
	
	if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
	{
		$base_class['base_class_desc'] = nv_aleditor( "base_class[base_class_desc]", '100%', '300px', $base_class['base_class_desc'] );
	}
	else
	{
		$base_class['base_class_desc'] = "<textarea style=\"width:100%;height:300px\" name=\"base_class[base_class_desc]\">" . $base_class['base_class_desc'] . "</textarea>";
	}
	$xtpl->assign( 'FACULTY_SLB', getTaxSelectBox( 'faculty', 'base_class[faculty_id]', $base_class['faculty_id'] ) );
	$xtpl->assign( 'LEVEL_SLB', getTaxSelectBox( 'level', 'base_class[level_id]', $base_class['level_id'] ) );
	$xtpl->assign( 'COURSE_SLB', getTaxSelectBox( $globalTax['course'], 'base_class[course_id]', $base_class['course_id'], NULL, 'course_id', 'course_name' ) );
	$xtpl->assign( 'BASE_CLASS', $base_class );
}
else
{
	$generate_page = nv_generate_page( $base_url, $all_page, $search['per_page'], $search['page'] );
	$xtpl->assign( 'SEARCH_FACULTY', getTaxSelectBox( 'faculty', 'faculty_id', $search['faculty_id'] ) );
	$xtpl->assign( 'SEARCH_COURSE', getTaxSelectBox( $globalTax['course'], 'course_id', $search['course_id'], NULL, 'course_id', 'course_name' ) );
	$showNumber = array();
	$i = 1;
	while( $i <= 20 )
	{
		$showNumber[$i] = array( 'value' => $i );
		$i++;
	}
	$xtpl->assign( 'SHOW_NUMBER', getTaxSelectBox( $showNumber, 'per_page', $search['per_page'], NULL, 'value', 'value' ) );
	
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