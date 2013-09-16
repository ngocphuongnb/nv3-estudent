<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_MOD_ESTUDENT' ) && !defined('IS_TEACHER') ) die( 'Stop!!!' );

define( 'TEACHER_FUNCTION', true );

$msg = array();
$form_action = '';
$term_id = $nv_Request->get_int( 'term_id', 'post,get', 0 );
$class_id = $nv_Request->get_int( 'class_id', 'post,get', 0 );

$search['per_page'] = $nv_Request->get_int( 'per_page', 'get', 40 );
$search['page'] = $nv_Request->get_int( 'page', 'get', 0 );

if( $class_id > 0 )
{
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_class` WHERE `class_id`=" . $class_id;
	$result = $db->sql_query( $sql );
	
	$my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . 'modules/' . $module_file . '/data/jquery-ui.js"></script>';
	$my_head .= '<link type="text/css" href="' . NV_BASE_SITEURL . 'modules/' . $module_file . '/data/jquery-ui.css" rel="stylesheet" type="text/css" />';
	
	$xtpl = new XTemplate( "enter_roll_call_class.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'TODAY', date('d/m/Y') );
		
	if( $db->sql_numrows( $result ) == 1 )
	{
		$class = $db->sql_fetchrow( $result );
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_student_" . $class['faculty_id'] . "_" . $globalTax['term'][$class['term_id']]['year'] . "` WHERE ( `class_ids`='" . $class_id . "' OR `class_ids` REGEXP '^" . $class_id . "\\\,' OR `class_ids` REGEXP '\\\," . $class_id . "\\\,' OR `class_ids` REGEXP '\\\," . $class_id . "\$') ORDER BY `student_id` LIMIT " . $search['page'] . "," . $search['per_page'];
		$result = $db->sql_query( $sql );
		
		$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result_all );
		
		$class['year'] = $globalTax['term'][$class['term_id']]['year'];
			
		if( $db->sql_numrows( $result ) > 0 )
		{
			while($student = $db->sql_fetchrow( $result ))
			{
				$student['miss_class'] = 0;
				$student['miss_class_desc'] = '';
				$student['test_status'] = 'Được thi';
				if( !empty($student['off_class_count']) )
				{
					$roll_call = unserialize($student['off_class_count']);
					//p($roll_call);
					if( isset($roll_call[$class_id]) )
					{
						$roll_call = $roll_call[$class_id];
						
						$i = 0;
						foreach( $roll_call as $_roll_call )
						{
							$_roll_data = explode('-', $_roll_call);
							if( $_roll_data[1] == 1 )
							{
								$i += 0.5;
								$student['miss_class_desc'][] = date( "d/m/Y", $_roll_data[0] ) . ' muộn học';
							}
							elseif( $_roll_data[1] == 0 )
							{
								$i += 1;
								$student['miss_class_desc'][] = date( "d/m/Y", $_roll_data[0] ) . ' nghỉ học';
							}
						}
						$student['miss_class'] = $i;
						$student['miss_class_desc'] = implode('<br />', $student['miss_class_desc'] );
					}
					
					if( $student['miss_class'] > $max_miss_class ) $student['test_status'] = 'Đình chỉ thi';
				}
				else $roll_call = '';
				$student['class_id'] = $class_id;
				
				$current_roll_call_stt = 2;
				
				$student['vnp_roll_call'] = getTaxSelectBox($globalTax['roll_call'], 'roll_call_' . $student['student_id'], $current_roll_call_stt, 'change_student_roll_call_' . $student['student_id'], '', '' ) . '<input type="button" onclick="vnp_roll_call(\'' . $class_id . '\', \'' . $student['student_id'] . '\');" value="Xác nhận"/>';
				$xtpl->assign( 'ROW', $student );
				$xtpl->parse( 'main.loop' );
			}
		}
	}
	$base_url = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/roll-call&amp;class_id=" . $class_id;
	
	$generate_page = nv_generate_page( $base_url, $all_page, $search['per_page'], $search['page'] );
	//p($generate_page);
	$xtpl->assign( 'PAGE_GEN', $generate_page );
	$xtpl->assign( 'CLASS', $class );
	$vnp_content = '';
	$xtpl->parse( 'main' );
	$vnp_content .= $xtpl->text( 'main' );
}
else
{
	$search = array(
							'is_search' => false,
							'q' => '',
							'faculty_id' => 0,
							'term_id' => $term_id,
							'number_student' => '',
							'enter_mark' => 1,
							'status' => 'all',
							'per_page' => 10,
							'page' => 0,
							);
							
	if( $nv_Request->get_string( 'search', 'get', '' ) == 1 )
	{
		$search['is_search'] = true;
		$search['q'] = $nv_Request->get_string( 'q', 'get', '' );
		$search['faculty_id'] = $userData['faculty_id'];
		$search['term_id'] = $term_id;
		$search['number_student'] = $nv_Request->get_string( 'number_student', 'get', 'all' );
		$search['enter_mark'] = $nv_Request->get_string( 'enter_mark', 'get', 'all' );
		$search['status'] = $nv_Request->get_string( 'status', 'get', 'all' );
		$search['per_page'] = $nv_Request->get_int( 'per_page', 'get', 10 );
		$search['page'] = $nv_Request->get_int( 'page', 'get', 0 );
	}
	
	$xtpl = new XTemplate( "required_roll_call_class.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	
	
	$globalTax['term'][0] = array('term_id' => 0, 'term_name' => $lang_module['select_term']);
	$_link = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/roll-call&amp;term_id=";
	$onchange = 'onchange="top.location.href=\'' . $_link . '\'+this.options[this.selectedIndex].value;return;"';
	$xtpl->assign( 'TERM_SLB', getTaxSelectBox( $globalTax['term'], 'term_id', $term_id, NULL, 'term_id', 'term_name', $onchange ) );
	
	$_s = array();
	$_s[] = "`faculty_id`=" . intval($userData['faculty_id']);
	if($search['term_id'] > 0 )
	$_s[] = "`term_id`=" . intval($search['term_id']);
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
		$_s = ' AND ' . implode(' AND ', $_s );
	}
	else $_s = '';
	
	$base_url = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/roll-call&amp;search=1&amp;per_page=" . $search['per_page'] . "&amp;term_id=" . $search['term_id'] . "&amp;number_student=" . $search['number_student'] . "&amp;enter_mark=" . $search['enter_mark'] . "&amp;status=" . $search['status'] . "&amp;q=" . $search['q'];
	
	$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_class` WHERE ( `teacher_id`='" . $userData['teacher_id'] . "' OR `teacher_id` REGEXP '^" . $userData['teacher_id'] . "\\\,' OR `teacher_id` REGEXP '\\\," . $userData['teacher_id'] . "\\\,' OR `teacher_id` REGEXP '\\\," . $userData['teacher_id'] . "\$')" . $_s . " LIMIT " . $search['page'] . "," . $search['per_page'];
	
	$result = $db->sql_query( $sql );
	
	$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
	list( $all_page ) = $db->sql_fetchrow( $result_all );
	//die('dcdcdc');
	if( $db->sql_numrows( $result ) > 0 )
	{
		$i = 1;
		while( $row = $db->sql_fetchrow( $result ) )
		{
			if( 1 )
			{
				$title = 'Điểm danh';
			}
			else $title = 'Điểm danh';
			$row['label'] = $title;
			$array_status = array( $lang_module['deactive'], $lang_module['active'] );
			$row['class'] = ( ++$i % 2 ) ? " class=\"second\"" : "";
			$row['url_enter_roll_call'] = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/roll-call&amp;class_id=" . $row['class_id'];
			
			$row['term'] = $globalTax['term'][$row['term_id']]['term_name'];
			$_subjectID = explode( ',', $row['subject_id'] );
			$row['subject'] = $globalTax['subject'][$_subjectID[1]]['subject_name'];
			$row['faculty'] = $globalTax['faculty'][$row['faculty_id']]['faculty_name'];	
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.row' );
			$i++;
		}
	}
	
	
	$generate_page = nv_generate_page( $base_url, $all_page, $search['per_page'], $search['page'] );
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
	
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op . '/roll-call' );
	
	
	$vnp_content = '';
	$xtpl->parse( 'main' );
	$vnp_content .= $xtpl->text( 'main' );
}


?>