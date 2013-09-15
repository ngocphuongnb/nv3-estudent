<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

define( 'CLASS_FUNCTION', true );

$msg = array();
$form_action = '';

$search = array(
					'is_search' => false,
					'q' => '',
					'faculty_id' => 0,
					'term_id' => 0,
					'per_page' => 10,
					'page' => 0
					);
					
if( $nv_Request->get_string( 'search', 'get', '' ) == 1 )
{
	$search['is_search'] = true;
	$search['q'] = $nv_Request->get_string( 'q', 'get', '' );
	$search['faculty_id'] = $nv_Request->get_int( 'faculty_id', 'get', 0 );
	$search['term_id'] = $nv_Request->get_int( 'term_id', 'get', 0 );
	$search['per_page'] = $nv_Request->get_int( 'per_page', 'get', 10 );
	$search['page'] = $nv_Request->get_int( 'page', 'get', 0 );
}

$classid = $nv_Request->get_int( 'classid', 'post,get', 0 );
$termid = $nv_Request->get_int( 'termid', 'post,get', 0 );

$class = array(
				'term_id' => $termid,
				'class_id' => $classid,
				'subject_id' => 0,
				'faculty_id' => 0,
				'teacher_id' => 0,
				'class_name' => '',
				'class_code' => '',
				'class_week' => '',
				'class_time' => '',
				'class_room' => '',
				'class_type_id' => 1,
				'test_type_id' => 1,
				'enter_mark' => 0,
				'registered_student' => 0,
				'number_student' => 0,
				'student_data' => '',
				'year' => date("Y"),
				'status' => 1
			);

$xtpl = new XTemplate( "add_class.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$action = $nv_Request->get_string( 'action', 'get', '' );

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	//$class = $nv_Request->get_typed_array( 'class', 'post', 'string', array() );
	require( 'class_functions.php' );
}
else
{
	if( $action == '' || $action == 'list' )
	{
		$xtpl = new XTemplate( "class.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'GLANG', $lang_global );
		$xtpl->assign( 'ADD_LINK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=select_year" );
		
		$_s = '';
		
		if( $search['is_search'] )
		{
			$_s = array();
			if( $search['faculty_id'] > 0 )
			{
				$_s[] = "`faculty_id`=" . intval($search['faculty_id']);
			}
			if( $search['term_id'] > 0 )
			{
				$_s[] = "`term_id`=" . intval($search['term_id']);
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
		$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;search=1&amp;per_page=" . $search['per_page'] . "&amp;faculty_id=" . $search['faculty_id'] . "&amp;term_id=" . $search['term_id'] . "&amp;q=" . $search['q'];
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_class`" . $_s . " LIMIT " . $search['page'] . "," . $search['per_page'];
		
		$result = $db->sql_query( $sql );
		
		$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result_all );
	
		if( $db->sql_numrows( $result ) > 0 )
		{
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$array_status = array( $lang_module['deactive'], $lang_module['active'] );
				$row['class'] = ( ++$i % 2 ) ? " class=\"second\"" : "";
				$row['url_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add&amp;classid=" . $row['class_id'];
				
				$row['term'] = $globalTax['term'][$row['term_id']]['term_name'];
				$_subjectID = explode( ',', $row['subject_id'] );
				$row['subject'] = $globalTax['subject'][$_subjectID[1]]['subject_name'];

				$row['class_status'] = getTaxSelectBox( $globalTax['class_reg_status'], 'class_status_' . $row['class_id'], $row['status'], 'change_status_' . $row['class_id'], '', '', 'onchange="nv_chang_status(\'' . $row['class_id'] . '\', \'class\');"' );
				$row['class_mark'] = getTaxSelectBox( $globalTax['class_mark'], 'class_mark_' . $row['class_id'], $row['enter_mark'], 'change_class_enter_mark_' . $row['class_id'], '', '', 'onchange="vnp_update_class(\'' . $row['class_id'] . '\', \'enter_mark\', this.value);"' );
				$xtpl->assign( 'ROW', $row );
				$xtpl->parse( 'main.row' );
			}
		}
	}
	elseif( $action == 'add' )
	{
		$classid = $nv_Request->get_int( 'classid', 'get', 0 );	
		if( $termid == 0 && $classid == 0 )
		{
			//Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
			//die();
		}
		
		if( $classid == 0 )
		{
			$term_data = $globalTax['term'][$termid];
			$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add&amp;termid=" . $termid;
		}
		else
		{
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_class` WHERE `class_id`=" . $classid;
			$result = $db->sql_query( $sql );
		
			if( $db->sql_numrows( $result ) != 1 )
			{
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=add" );
				die();
			}
		
			$class = $db->sql_fetchrow( $result );
			
			if( !empty( $class['subject_id'] ) )
			{
				$_subject_title = array();
				$class_subject_id = explode(',', $class['subject_id']);
				foreach( $class_subject_id as $_sbid )
				{
					if( !empty($_sbid) )
					$_subject_title[] = '<li>' . $_sbid . ' - ' . $globalTax['subject'][$_sbid]['subject_name'] . '</li>';
				}
				$class['subject_title'] = implode(PHP_EOL, $_subject_title);
			}
			
			if( !empty( $class['teacher_id'] ) )
			{
				$_teacher_title = array();
				$class_teacher_id = explode(',', $class['teacher_id']);
				foreach( $class_teacher_id as $_tcid )
				{
					if( !empty($_tcid) )
					$_teacher_title[] = '<li>' . $_tcid . ' - ' . $globalTax['teacher'][$_tcid]['teacher_name'] . '</li>';
				}
				$class['teacher_title'] = implode(PHP_EOL, $_teacher_title);
			}
			
			$term_data = $globalTax['term'][$class['term_id']];		
			$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add&amp;classid=" . $classid;	
		}

		$class_time = explode(',', $class['class_time']);
		$week = '';
		foreach( $globalConfig['week_data'] as $key => $day )
		{
			$week .= '<div class="class-time"><div class="day">' . $day . '</div>';
			for( $i = 1; $i <= $globalConfig['day_period']; $i++ )
			{
				$_compareKey = $key . '_' . $i;
				$cked = in_array($_compareKey, $class_time) ? 'checked="checked"' : '';
				$week .= '<label><input type="checkbox" ' . $cked . ' name="class[class_time][' . $key . '][]" value="' . $i . '" />' . $lang_module['class_period'] . $i . '</label>';
			}
			$week .= '</div>';
		}
		$class['class_time'] = $week;
		
				
	}
	elseif( $action == 'select_year' )
	{
		/*$year = $nv_Request->get_int( 'year', 'post,get', 0 );
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_term` WHERE `year`=" . $year;
		$result = $db->sql_query( $sql );
		
		if( $db->sql_numrows( $result ) != 1 )
		{
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=select_year" );
			die();
		}
		
		while( $term = $db->sql_fetchrow( $result ) )	
		$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add&amp;termid=" . $termid;*/
	}
}

if( $action == 'add' )
{
	if( !empty( $term_data ) )
	{
		$weeks_data = array();
		$term_data['weeks'] = explode('-', $term_data['weeks']);
		for( $i = intval( $term_data['weeks'][0] ); $i <= intval($term_data['weeks'][1]); $i++ )
		{
			$weeks_data[] = array( 'label' => $lang_module['week'] . ' ' . $i, 'value' => $i );
		}
	}
	if( $nv_Request->get_int( 'save', 'post' ) != '1' )
	{
		if( !isset( $class['year'] ) && isset($class['term_id']) && $class['term_id'] > 0 )
		{
			$class['year'] = $globalTax['term'][$class['term_id']];
		}
		$xtpl->assign( 'CLASS_SLB', getTaxSelectBox( $globalTax['year'], 'class[year]', $class['year'], NULL, 'year', 'year' ) );
		$xtpl->assign( 'CLASS', $class );
		$xtpl->assign( 'FACULTY_SLB', getTaxSelectBox( 'faculty', 'class[faculty_id]', $class['faculty_id'] ) );
		$xtpl->assign( 'TEACHER_SLB', getTaxSelectBox( 'teacher', 'class[teacher_id]', $class['teacher_id'] ) );
		$xtpl->assign( 'TERM_SLB', getTaxSelectBox( $globalTax['term'], 'class[term_id]', $class['term_id'], NULL, 'term_id', 'term_name' ) );
		$xtpl->assign( 'CLASS_TYPE_SLB', getTaxSelectBox( $globalTax['class_type'], 'class[class_type]', $class['class_type_id'], NULL, 'class_type_id', 'class_type_name' ) );
		$xtpl->assign( 'TEST_TYPE_SLB', getTaxSelectBox( $globalTax['test_type'], 'class[test_type]', $class['test_type_id'], NULL, 'test_type_id', 'test_type_name' ) );
		$xtpl->assign( 'WEEK_CB', getTaxCheckBox( $weeks_data, 'class[class_week]', $class['class_week'], NULL, 'value', 'label' ) );
		$xtpl->assign( 'CLASS_STATUS', getTaxSelectBox( $globalTax['class_reg_status'], 'class[class_status]', $class['status'], NULL, '', '') );
		$xtpl->assign( 'CLASS_MARK', getTaxSelectBox( $globalTax['class_mark'], 'class[enter_mark]', $class['enter_mark'], NULL, '', '') );
		$xtpl->parse( 'main.add' );
	}
}
elseif( $action == 'select_year' )
{
	$year = $nv_Request->get_int( 'year', 'post,get', 0 );
	if( !empty( $term_data ) )
	{
		$weeks_data = array();
		$term_data['weeks'] = explode('-', $term_data['weeks']);
		for( $i = intval( $term_data['weeks'][0] ); $i <= intval($term_data['weeks'][1]); $i++ )
		{
			$weeks_data[] = array( 'label' => $lang_module['week'] . ' ' . $i, 'value' => $i );
		}
	}
	$_link = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=select_year&amp;year=";
	$onchange = 'onchange="top.location.href=\'' . $_link . '\'+this.options[this.selectedIndex].value;return;"';
	$xtpl->assign( 'YEAR_SLB', getTaxSelectBox( $globalTax['year'], 'term[year]', $year, NULL, 'year', 'year', $onchange ) );
	
	if( $year > 0 )
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_term` WHERE `year`=" . intval($year) . " ORDER BY `term_id`";
		$result = $db->sql_query( $sql );
	
		if( $db->sql_numrows( $result ) > 0 )
		{
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$row['link'] =NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add&amp;termid=" . $row['term_id'];
				$xtpl->assign( 'TERM', $row );
				$xtpl->parse( 'main.select_year.term.loop' );
			}
			$xtpl->parse( 'main.select_year.term' );
		}
	}
	$xtpl->parse( 'main.select_year' );
}
else
{
	$generate_page = nv_generate_page( $base_url, $all_page, $search['per_page'], $search['page'] );
	$xtpl->assign( 'SEARCH_FACULTY', getTaxSelectBox( 'faculty', 'faculty_id', $search['faculty_id'] ) );
	$xtpl->assign( 'SEARCH_TERM', getTaxSelectBox( 'term', 'term_id', $search['term_id'] ) );
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