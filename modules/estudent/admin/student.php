<?php

/**
 * @Project NUKEVIET 3.x
 * @Author Nguyen Ngoc Phuong (nguyenngocphuongnb@gmail.com)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

define( 'STUDENT_FUNCTION', true );

$action = $nv_Request->get_string( 'action', 'get', '' );
$add_config['year'] = $nv_Request->get_int( 'year', 'get,post', 0 );
$add_config['faculty_id'] = $nv_Request->get_int( 'faculty_id', 'get,post', 0 );

if( $action == 'add' )
{
	if( $add_config['faculty_id'] > 0 && $add_config['year'] > 0 )
	{
		if( !createStudentTable($add_config) )
		die('Cannot create student table ' . $add_config['faculty_id'] . '_' . $add_config['year'] . ' - ' . $globalTax['faculty'][$add_config['faculty_id']]['faculty_name'] . ' - ' . $add_config['year']);
	}
	else
	{
		$xtpl = new XTemplate( "add_student.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'GLANG', $lang_global );
		$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
		$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
		$xtpl->assign( 'MODULE_NAME', $module_name );
		$xtpl->assign( 'OP', $op );
		
		$xtpl->assign( 'YEAR_SLB', getTaxSelectBox( $globalTax['year'], 'year', $add_config['year'], NULL, 'year', 'year' ) );
		$xtpl->assign( 'FACULTY_SLB', getTaxSelectBox( 'faculty', 'faculty_id', $add_config['faculty_id'] ) );
		
		$xtpl->parse( 'select_year' );
		$contents = $xtpl->text( 'select_year' );
		
		include ( NV_ROOTDIR . '/includes/header.php' );
		echo nv_admin_theme( $contents );
		include ( NV_ROOTDIR . '/includes/footer.php' );
		exit();
	}
}

$msg = array();
$form_action = '';

$search = array(
					'is_search' => false,
					'q' => '',
					'faculty_id' => 0,
					'year' => date('Y'),
					'course_id' => '',
					'per_page' => 10,
					'page' => 0
					);
					
if( $nv_Request->get_string( 'search', 'get', 0 ) == 1 )
{
	$search['is_search'] = true;
	$search['q'] = $nv_Request->get_string( 'q', 'get', '' );
	$search['faculty_id'] = $nv_Request->get_int( 'faculty_id', 'get', 0 );
	$search['course_id'] = $nv_Request->get_string( 'course_id', 'get', '' );
	$search['year'] = $nv_Request->get_int( 'year', 'get', 0 );
	$search['per_page'] = $nv_Request->get_int( 'per_page', 'get', 10 );
	$search['page'] = $nv_Request->get_int( 'page', 'get', 0 );
}

$xtpl = new XTemplate( "add_student.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$studentid = $nv_Request->get_int( 'studentid', 'post,get', 0 );

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$student = $nv_Request->get_typed_array( 'student', 'post', 'string', array() );
	require( 'student_functions.php' );
}
else
{
	if( $action == '' || $action == 'list' )
	{
		$xtpl = new XTemplate( "student.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
				$_s[] = "`student_name` LIKE '%" . $db->dblikeescape( $search['q'] ) . "%'";
			}
			//if( $search['faculty_id'] > 0 || !empty($search['q']) || !empty($search['course_id']) )
			if( !empty($search) )
			{
				$_s = "WHERE " . implode(' AND ', $_s );
			}
		}
		$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;search=1&amp;per_page=" . $search['per_page'] . "&amp;faculty_id=" . $search['faculty_id'] . "&amp;q=" . $search['q'];
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_student` " . $_s . " LIMIT " . $search['page'] . "," . $search['per_page'];
		$result = $db->sql_query( $sql );
		
		$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result_all );
	
		if( $db->sql_numrows( $result ) > 0 )
		{
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$array_status = array( $lang_module['deactive'], $lang_module['active'] );
				$row['class'] = ( ++$i % 2 ) ? " class=\"second\"" : "";
				$row['url_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add&amp;faculty_id=" . $row['faculty_id'] . "&amp;year=" . $row['year'] ."&amp;studentid=" . $row['student_id'];
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
		$studentid = $nv_Request->get_int( 'studentid', 'get', 0 );		
		if( $studentid == 0 )
		{
			$student = array(
				'studentid' => 0,
				'student_name' => '',
				'student_alias' => '',
				'student_desc' => '',
				'faculty_id' => $add_config['faculty_id'], 
				'level_id' => 0,
				'year' => $add_config['year'],
				'course_id' => 0
			);
			$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add&amp;year=" . $add_config['year'] . "&amp;faculty_id=" . $add_config['faculty_id'];
		}
		else
		{
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_student` WHERE `student_id`=" . $studentid;
			$result = $db->sql_query( $sql );
		
			if( $db->sql_numrows( $result ) != 1 )
			{
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=add" );
				die();
			}
		
			$student = $db->sql_fetchrow( $result );
					
			$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add&amp;year=" . $add_config['year'] . "&amp;faculty_id=" . $add_config['faculty_id'] . "&amp;studentid=" . $student['student_id'];
		}		
	}
}

if( $action == 'add' )
{
	if( !empty( $student['base_class_id'] ) )
	{
		$_base_class_title = array();
		$base_class_ids = explode(',', $student['base_class_id']);
		foreach( $base_class_ids as $_baseClassID )
		{
			if( !empty($_baseClassID) )
			{
				$_base_class_data = getBaseClass($_baseClassID);
				if( !empty($_base_class_data) )
				{
					$_base_class_title[] = '<li>' . $_baseClassID . ' - ' . $_base_class_data[$_baseClassID]['base_class_name'] . '</li>';
				}
			}
		}
		$student['base_class'] = implode(PHP_EOL, $_base_class_title);
	}
	
	if( ! empty( $student['student_desc'] ) ) $student['student_desc'] = nv_htmlspecialchars( $student['student_desc'] );
		
	if( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
	
	if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
	{
		$student['student_desc'] = nv_aleditor( "student[student_desc]", '100%', '300px', $student['student_desc'] );
	}
	else
	{
		$student['student_desc'] = "<textarea style=\"width:100%;height:300px\" name=\"student[student_desc]\">" . $student['student_desc'] . "</textarea>";
	}
	$xtpl->assign( 'FACULTY_SLB', getTaxSelectBox( 'faculty', 'student[faculty_id]', $student['faculty_id'] ) );
	$xtpl->assign( 'LEVEL_SLB', getTaxSelectBox( 'level', 'student[level_id]', $student['level_id'] ) );
	$xtpl->assign( 'COURSE_SLB', getTaxSelectBox( $globalTax['course'], 'student[course_id]', $student['course_id'], NULL, 'course_id', 'course_name' ) );
	$xtpl->assign( 'YEAR_SLB', getTaxSelectBox( $globalTax['year'], 'student[year]', $student['year'], NULL, 'year', 'year' ) );
	$xtpl->assign( 'STUDENT', $student );
	
	$xtpl->assign( 'ADD_STUDENT_HEADER', sprintf( $lang_module['add_student_header'], $globalTax['faculty'][$add_config['faculty_id']]['faculty_name'], $globalTax['year'][$add_config['year']]['year']) );
	
	$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.css\" rel=\"stylesheet\" />\n";
	$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.theme.css\" rel=\"stylesheet\" />\n";
	$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.datepicker.css\" rel=\"stylesheet\" />\n";

	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.min.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.datepicker.min.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.ui.datepicker-" . NV_LANG_INTERFACE . ".js\"></script>\n";
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

$filtersql = " `userid` NOT IN (SELECT `admin_id` FROM `" . NV_AUTHORS_GLOBALTABLE . "`)";
$xtpl->assign( 'FILTERSQL', nv_base64_encode( $crypt->aes_encrypt( $filtersql, md5( $global_config['sitekey'] . $client_info['session_id'] ) ) ) );

$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
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