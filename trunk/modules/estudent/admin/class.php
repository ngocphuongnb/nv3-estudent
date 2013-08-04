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

$class = array(
				'class_id' => 0,
				'subject_id' => 0,
				'faculty_id' => 0,
				'teacher_id' => 0,
				'class_name' => '',
				'class_code' => '',
				'class_week' => 0,
				'class_time' => '',
				'class_room' => '',
				'class_type_id' => 1,
				'test_type_id' => 1,
				'enter_mask' => 0,
				'registered_student' => 0,
				'number_student' => 0,
				'student_data' => '',
				'year' => date("Y")
			);

$xtpl = new XTemplate( "add_class.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$classid = $nv_Request->get_int( 'classid', 'post,get', 0 );
$termid = $nv_Request->get_int( 'termid', 'post,get', 0 );
$action = $nv_Request->get_string( 'action', 'get', '' );

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$class = $nv_Request->get_typed_array( 'class', 'post', 'string', array() );
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
		
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_class`";
		$result = $db->sql_query( $sql );
	
		if( $db->sql_numrows( $result ) > 0 )
		{
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$array_status = array( $lang_module['deactive'], $lang_module['active'] );
				$row['class'] = ( ++$i % 2 ) ? " class=\"second\"" : "";
				$row['url_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add&amp;classid=" . $row['class_id'];
				foreach( $array_status as $key => $val )
				{
					$xtpl->assign( 'STATUS', array(
						'key' => $key, //
						'val' => $val, //
						'selected' => ( $key == $row['status'] ) ? " selected=\"selected\"" : "" //
					) );
			
					$xtpl->parse( 'main.row.status' );
				}
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
			$term_data = $globalTax['term'][$class['term_id']];		
			$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=add&amp;classid=" . $classid;	
		}		
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
	$xtpl->assign( 'CLASS_SLB', getTaxSelectBox( $globalTax['year'], 'class[year]', $class['year'], NULL, 'year', 'year' ) );
	$xtpl->assign( 'CLASS', $class );
	$xtpl->assign( 'FACULTY_SLB', getTaxSelectBox( 'faculty', 'class[faculty_id]', $class['faculty_id'] ) );
	$xtpl->assign( 'TEACHER_SLB', getTaxSelectBox( 'teacher', 'class[teacher_id]', $class['teacher_id'] ) );
	$xtpl->assign( 'CLASS_TYPE_SLB', getTaxSelectBox( $globalTax['class_type'], 'class[class_type]', $class['class_type_id'], NULL, 'class_type_id', 'class_type_name' ) );
	$xtpl->assign( 'TEST_TYPE_SLB', getTaxSelectBox( $globalTax['test_type'], 'class[test_type]', $class['test_type_id'], NULL, 'test_type_id', 'test_type_name' ) );
	$xtpl->assign( 'WEEK_CB', getTaxCheckBox( $weeks_data, 'class[class_week]', $class['class_week'], NULL, 'value', 'label' ) );
	
	$week = '';
	foreach( $globalConfig['week_data'] as $key => $day )
	{
		$week .= '<div class="class-time"><div class="day">' . $day . '</div>';
		for( $i = 1; $i <= $globalConfig['day_period']; $i++ )
		{
			$week .= '<label><input type="checkbox" name="class[class_time][' . $key . '][]" value="' . $i . '" />' . $lang_module['class_period'] . $i . '</label>';
		}
		$week .= '</div>';
	}
	$xtpl->assign( 'WEEK_DATA', $week );
	$xtpl->parse( 'main.add' );
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

$xtpl->assign( 'FORM_ACTION', $form_action );

$contents = vnp_msg($msg);
$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . '/includes/header.php' );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . '/includes/footer.php' );

?>