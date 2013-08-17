<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['teacher'] = $lang_module['teacher_management'];
$submenu['level'] = $lang_module['level_management'];
$submenu['subject'] = $lang_module['subject_management'];
$submenu['faculty'] = $lang_module['faculty_management'];
$submenu['term'] = $lang_module['term_management'];
$submenu['base_class'] = $lang_module['base_class_management'];
$submenu['class'] = $lang_module['class_management'];
$submenu['student'] = $lang_module['student_management'];
$submenu['sp_data'] = 'Cài dữ liệu mẫu';

//$my_head .= '<link rel="Stylesheet" href="' . NV_BASE_SITEURL . 'modules/' . $module_file . '/data/bootstrap/css/bootstrap.css" type="text/css" />';

$allow_func = array( 'main', 'ajax_get_item', 'sp_data',
							'teacher', 'teacher_ajax_action', 
							'level', 'level_ajax_action',
							'subject', 'subject_ajax_action',
							'faculty', 'faculty_ajax_action',
							'term', 'term_ajax_action',
							'class', 'class_ajax_action',
							'base_class', 'base_class_ajax_action',
							'student', 'student_ajax_action' );

define( 'NV_IS_FILE_ADMIN', true );

$globalTax = $globalConfig = array();

// Taxonomy data
$globalTax['faculty'] = getFaculty();
$globalTax['teacher'] = getTeacher();
$globalTax['subject'] = getSubject();
$globalTax['test_type'] = getTestType();
$globalTax['class_type'] = getClassType();
$globalTax['level'] = getLevel();
$globalTax['year'] = getYear();
$globalTax['term'] = getTerm();
$globalTax['course'] = getCourse();
$globalTax['study_status'] = array(
									0 => $lang_module['study_suspended'],
									1 => $lang_module['study_normal'],
									2 => $lang_module['study_warned_1'],
									3 => $lang_module['study_warned_2'],
									4 => $lang_module['study_warned_3'],
									5 => $lang_module['study_reserved_1'],
									6 => $lang_module['study_reserved_2'],
									7 => $lang_module['study_reserved_3'],
									8 => $lang_module['study_graduated']
								);
								
$globalTax['class_reg_status'] = array(
									0 => $lang_module['class_canceled'],
									1 => $lang_module['class_normal'],
									2 => $lang_module['class_edit_reg'],
									3 => $lang_module['class_reg_closed'],
									4 => $lang_module['class_based_class']
								);

// Global config data
$globalConfig['day_period'] = 12;
$globalConfig['week_data'] = array(
									2 => $lang_module['monday'],
									3 => $lang_module['tuesday'],
									4 => $lang_module['wednesday'],
									5 => $lang_module['thursday'],
									6 => $lang_module['friday'],
									7 => $lang_module['saturday'],
									8 => $lang_module['sunday']
								);
include NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

function vnp_msg($msg)
{
	if( !empty( $msg ) )
	return '<div class="' . $msg['type'] . '">' . $msg['content'] . '</div>';
}

function getFaculty($faculty_id = NULL)
{
	global $db, $module_data;
	
	$_faculty = array();
	if( $faculty_id > 0 )
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_faculty` WHERE `faculty_id`=" . intval($faculty_id);
	}
	else
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_faculty`";
	}
	$result = nv_db_cache( $sql );

	foreach( $result as $row )
	{
		$_faculty[$row['faculty_id']] = $row;
	}
	return $_faculty;
}

function getTeacher($teacher_id = NULL)
{
	global $db, $module_data;
	
	$_teacher = array();
	if( $teacher_id > 0 )
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_teacher` WHERE `teacher_id`=" . intval($teacher_id);
	}
	else
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_teacher`";
	}
	$result = nv_db_cache( $sql );

	foreach( $result as $row )
	{
		$_teacher[$row['teacher_id']] = $row;
	}
	return $_teacher;
}

function getSubject($subject_id = NULL)
{
	global $db, $module_data;
	
	$_subject = array();
	if( $subject_id > 0 )
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_subject` WHERE `subject_id`=" . intval($subject_id);
	}
	else
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_subject`";
	}
	$result = nv_db_cache( $sql );

	foreach( $result as $row )
	{
		$_subject[$row['subject_id']] = $row;
	}
	return $_subject;
}

function getTerm($term_id = NULL)
{
	global $db, $module_data;
	
	$_term = array();
	if( $term_id > 0 )
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_term` WHERE `term_id`=" . intval($term_id);
	}
	else
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_term`";
	}
	$result = nv_db_cache( $sql );

	foreach( $result as $row )
	{
		$_term[$row['term_id']] = $row;
	}
	return $_term;
}

function getLevel($level_id = NULL)
{
	global $db, $module_data, $lang_module;
	
	$_level = array();
	if( $level_id > 0 )
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_level` WHERE `level_id`=" . intval($level_id);
	}
	else
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_level`";
	}
	$result = nv_db_cache( $sql );
	$_level[0] = array( 'level_id' => 0, 'level_name' => $lang_module['select_level'] );
	foreach( $result as $row )
	{
		$_level[$row['level_id']] = $row;
	}
	return $_level;
}

function getTestType($test_type_id = NULL)
{
	$test_type = array(
					array(
						'test_type_id' => 0,
						'test_type_name' => 'Không thi', 
						'require_mark' => 0
					),
											array(
						'test_type_id' => 1,
						'test_type_name' => 'Tự luận', 
						'require_mark' => 1
					),
											array(
						'test_type_id' => 2,
						'test_type_name' => 'Trắc nghiệm', 
						'require_mark' => 1
					),
											array(
						'test_type_id' => 3,
						'test_type_name' => 'Vấn đáp', 
						'require_mark' => 1
					)
				);
	if( $test_type_id != NULL && isset( $test_type[$test_type_id] ) )
	{
		return $test_type[$test_type_id];
	}
	else return $test_type;
}

function getClassType($class_type_id = NULL)
{
	$class_type = array(
					array(
						'class_type_id' => 0,
						'class_type_name' => 'None'
					),
					array(
						'class_type_id' => 1,
						'class_type_name' => 'Lý thuyết'
					),
					array(
						'class_type_id' => 2,
						'class_type_name' => 'Thực hành'
					),
					array(
						'class_type_id' => 3,
						'class_type_name' => 'Thực tập'
					),
					array(
						'class_type_id' => 4,
						'class_type_name' => 'Tham quan thực tế'
					)
				);
	if( $class_type_id != NULL && isset( $class_type[$class_type_id] ) )
	{
		return $class_type[$class_type_id];
	}
	else return $class_type;
}

function getYear($year_id = NULL)
{
	$year = array();
	$year[date('Y')] = array( 'year' => date('Y') );
	
	for( $i = 2010; $i <= 2020; $i++ )
	{
		$year[$i] = array( 'year' => $i );
	}
	if( $year_id != NULL && isset( $year[$year_id] ) )
	{
		return $year[$year_id];
	}
	else return $year;
}

function getCourse($course_id = NULL)
{
	global $lang_module;
	
	$course = array();
	$course[0] = array( 'course_id' => 0, 'course_name' => $lang_module['select_course']);
	$j = 1;
	for( $i = 2010; $i <= 2020; $i++ )
	{
		$course['K' . $j] = array( 'course_id' => 'K' . $j, 'course_name' => 'K' . $j . ' - ' . $i );
		$j++;
	}
	if( $course_id != NULL && isset( $course[$course_id] ) )
	{
		return $course[$course_id];
	}
	else return $course;
}

function getTaxSelectBox( $termData, $name = NULL, $defaultValue = NULL, $selectBoxID = NULL, $valueKey = NULL, $titleKey = NULL, $otherAttr = NULL )
{
	global $globalTax, $lang_module;
	
	$selectBox = array();
	if( !empty( $termData ) )
	{
		//$xtpl = new XTemplate( "tax_select_box.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		//$xtpl->assign( 'LANG', $lang_module );
		//$xtpl->assign( 'GLANG', $lang_global );
		
		if( in_array($termData, array('faculty', 'subject', 'teacher', 'level', 'term')) )
		{
			$_t = $globalTax[$termData];
			$selectBox[] = '<option value="">' . $lang_module['select'] . '</option>';
			foreach( $_t as $taxData )
			{
				( $taxData[$termData . '_id'] == $defaultValue ) ? $slt = 'selected="selected"' : $slt = '';
				$selectBox[] = '<option ' . $slt . ' value="' . $taxData[$termData . '_id'] . '">' . $taxData[$termData . '_name'] . '</option>';
			}
		}
		elseif( is_array($termData) )
		{
			foreach( $termData as $taxData )
			{
				( $taxData[$valueKey] == $defaultValue ) ? $slt = 'selected="selected"' : $slt = '';
				$selectBox[] = '<option ' . $slt . ' value="' . $taxData[$valueKey] . '">' . $taxData[$titleKey] . '</option>';
			}
		}
	}
	return '<select name="' . $name . '" ' . $otherAttr . '>' . implode( PHP_EOL, $selectBox ) . '</select>';
}

function getTaxCheckBox( $termData, $name = NULL, $defaultValue = '', $selectBoxID = NULL, $valueKey = NULL, $titleKey = NULL )
{
	global $globalTax, $lang_module;
	
	if( !is_array($defaultValue) ) $defaultValue = explode(',', $defaultValue );
	
	$selectBox = array();
	if( !empty( $termData ) )
	{		
		if( in_array($termData, array('faculty', 'subject', 'teacher', 'level', 'term')) )
		{
			$_t = $globalTax[$termData];
			foreach( $_t as $taxData )
			{
				in_array( $taxData[$termData . '_id'], $defaultValue ) ? $slt = 'checked="checked"' : $slt = '';
				$selectBox[] = $taxData[$termData . '_name'] . ': <input type="checkbox" ' . $slt . ' name="' . $name . '[]" value="' . $taxData[$termData . '_id'] . '"/>';
			}
		}
		elseif( is_array($termData) )
		{
			foreach( $termData as $taxData )
			{
				in_array( $taxData[$valueKey], $defaultValue ) ? $slt = 'checked="checked"' : $slt = '';
				$selectBox[] = '<label><input type="checkbox" name="' . $name . '[]" ' . $slt . ' value="' . $taxData[$valueKey] . '"/>' . $taxData[$titleKey] . '</label>';
			}
		}
	}
	return implode( PHP_EOL, $selectBox );
}

function getBaseClass($base_class_id = NULL)
{
	global $db, $module_data;
	
	$_base_class = array();
	if( $base_class_id > 0 )
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_base_class` WHERE `base_class_id`=" . intval($base_class_id);
	}
	else
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_base_class`";
	}
	$result = nv_db_cache( $sql );

	foreach( $result as $row )
	{
		$_base_class[$row['base_class_id']] = $row;
	}
	return $_base_class;
}

function p($data = array())
{
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	die();
}

?>