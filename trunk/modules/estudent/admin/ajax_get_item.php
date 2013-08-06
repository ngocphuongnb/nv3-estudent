<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

// subject, teacher, class room, student, class

$mode = $nv_Request->get_string( 'mode', 'post,get', '' );
$container = $nv_Request->get_string( 'container', 'post,get', '' );
$listid = $nv_Request->get_string( 'listid', 'post,get', '' );

if( !empty( $listid ) )
{
	$nv_Request->set_Cookie( 'vnp_selected_items', $listid, NV_LIVE_COOKIE_TIME, false );
}
else 
{
	//$listid = $nv_Request->get_string( 'vnp_selected_items', 'cookie', '' );
	$listid = $_COOKIE[$global_config['cookie_prefix'] . '_vnp_selected_items'];
}

$search = array(
					'mode' => $mode,
					'container' => $container,
					'listid' => $listid,
					'search' => 0,
					'q' => '',
					'per_page' => 10,
					'page' => 0,
					'extra_condition' => array()
				);

$keyData = array(
					'id_key' => '',
					'name_key' => ''
				); 
switch( $search['mode'] )
{
	case 'subject' :
	{
		$keyData['id_key'] = 'subject_id';
		$keyData['name_key'] = 'subject_name';
		$search['query_key'] = 'subject_name';
		$search['table'] = 'subject';
		$_faculty_id = $nv_Request->get_int( 'faculty_id', 'get', 0 );
		$search['extra_condition']['faculty_id'] = array( 'key' => 'faculty_id', 'value' => $_faculty_id, 'datatype' => 'int' );
		break;
	}
	case 'teacher' :
	{
		$keyData['id_key'] = 'teacher_id';
		$keyData['name_key'] = 'teacher_name';
		$search['query_key'] = 'teacher_name';
		$search['table'] = 'teacher';
		$_faculty_id = $nv_Request->get_int( 'faculty_id', 'get', 0 );
		$search['extra_condition']['faculty_id'] = array( 'key' => 'faculty_id', 'value' => $_faculty_id, 'datatype' => 'int' );
		break;
	}
}

$_s = $_string_query = $_q = '';
					
if( $nv_Request->get_string( 'search', 'get', '' ) == 1 )
{
	$search['search'] = true;
	$search['q'] = $nv_Request->get_string( 'q', 'get', '' );
	$search['per_page'] = $nv_Request->get_int( 'per_page', 'get', 10 );
	$search['page'] = $nv_Request->get_int( 'page', 'get', 0 );
	
	if( !empty( $search['extra_condition'] ) )
	{
		$_s = array();
		foreach( $search['extra_condition'] as $_condition )
		{
			if( !empty( $_condition['value'] ) )
			{
				if( $_condition['datatype'] == 'int' )
				{
					$_s[] = '`' . $_condition['key'] . '`=' . intval($_condition['value']);
				}
				else
				{
					$_s[] = '`' . $_condition['key'] . '`=' . $db->dbescape($_condition['value']);
				}
			}
			$search[$_condition['key']] = $_condition['value'];
		}
		if( !empty( $search['q'] ) )
		{
			$_s[] = "`" . $search['query_key'] . "` LIKE '%" . $db->dblikeescape( $search['q'] ) . "%'";
		}
		if( !empty( $_s ) ) $_s = "WHERE " . implode(' AND ', $_s );
		else $_s = '';
		$_string_query = $search;
		unset( $_string_query['extra_condition'] );
		unset( $_string_query['query_key'] );
		unset( $_string_query['table'] );
		unset( $_string_query['page'] );
		
		foreach( $_string_query as $_query_key => $_query_value )
		{
			$_q .= '&amp;' . $_query_key . '=' .$_query_value;
		}
		$_string_query = $_q;
	}
}
	
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . $_q;

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $search['table'] . "` " . $_s . " LIMIT " . $search['page'] . "," . $search['per_page'];

$result = $db->sql_query( $sql );	
$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $result_all );

$xtpl = new XTemplate( "ajax_get_item.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$my_head .= '<script src="' . NV_BASE_SITEURL . 'modules/' . $module_file . '/data/input-toggle.js" /></script>';

if( $db->sql_numrows( $result ) > 0 )
{
	$i = 0;
	$listid = explode(',', $listid);
	while( $row = $db->sql_fetchrow( $result ) )
	{
		( $i%2 == 0 ) ? $_row['class'] = 'second' : $_row['class'] = '';
		$_row['id'] = $row[$keyData['id_key']];
		$_row['name'] = $row[$keyData['name_key']];
		in_array( $_row['id'], $listid ) ? $_row['check'] = 'checked="checked"' : $_row['check'] = '';
		$xtpl->assign( 'ROW', $_row );
		$xtpl->parse( 'main.row' );
		$i++;
	}
	if( sizeof( $listid ) >= $i ) $xtpl->assign( 'CHECK_ALL', 'checked="checked"' );
	else $xtpl->assign( 'CHECK_ALL', '' );
}
$generate_page = nv_generate_page( $base_url, $all_page, $search['per_page'], $search['page'] );
$showNumber = array();
$i = 1;
while( $i <= 20 )
{
	$showNumber[$i] = array( 'value' => $i );
	$i++;
}

$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'GLOBAL_CONFIG', $global_config );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'VNP_COOKIE_PREFIX', $global_config['cookie_prefix'] );

if( in_array( $search['mode'], array( 'teacher', 'subject' ) ) )
{
	$xtpl->assign( 'SEARCH_FACULTY', getTaxSelectBox( 'faculty', 'faculty_id', $search['extra_condition']['faculty_id']['value'] ) );
	$xtpl->parse( 'main.faculty' );
}
$xtpl->assign( 'SHOW_NUMBER', getTaxSelectBox( $showNumber, 'per_page', $search['per_page'], NULL, 'value', 'value' ) );
$xtpl->assign( 'SEARCH', $search );
if( !empty( $generate_page ) )
{
	$xtpl->assign( 'PAGE_GEN', $generate_page );
	$xtpl->parse( 'main.page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . '/includes/header.php' );
echo $contents;
include ( NV_ROOTDIR . '/includes/footer.php' );
exit();

?>