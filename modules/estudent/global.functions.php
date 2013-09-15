<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 12/31/2009 2:29
 */

if( !defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

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
									0 => $lang_module['class_reg_closed'],
									1 => $lang_module['class_normal'],
									2 => $lang_module['class_edit_reg'],
									3 => $lang_module['class_suspended'],
									4 => $lang_module['class_only_based_class']
								);
$globalTax['class_mark'] = array(
									0 => $lang_module['mark_not_entered'],
									1 => $lang_module['mark_required'],
									2 => $lang_module['mark_sent'],
									3 => $lang_module['mark_completed']
								);
$globalTax['teacher_type'] = array(
									0 => $lang_module['all'],
									1 => $lang_module['normal_teacher'],
									2 => $lang_module['second_dean'],
									3 => $lang_module['dean'],
									4 => $lang_module['practice_teacher'],
									5 => $lang_module['assistant_teacher']
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

function createStudentTable($add_config)
{
	global $module_file, $module_data, $module_name, $db;
	
	$table_log = file( NV_ROOTDIR . '/modules/' . $module_file . '/data/student_table_data.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );

	$table = $add_config['faculty_id'] . '_' . $add_config['year'];
	
	if( !in_array( $table , $table_log ) )
	{
		$_sql_string = "CREATE TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_student_" . $table . "` (
		  `student_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	  
		  `userid` mediumint(8) NOT NULL DEFAULT '0',
		  `faculty_id` mediumint(8) NOT NULL DEFAULT '0',
		  `level_id` mediumint(8) NOT NULL DEFAULT '0',
		  `base_class_id` varchar(255) NOT NULL,
		  `student_name` varchar(255) NOT NULL,
		  `student_code` varchar(255) NOT NULL,
		  `course_id` varchar(255) NOT NULL,
		  `year` int(11) NOT NULL DEFAULT '0',
		  
		  `family_name` varchar(255),
		  `last_name` varchar(255),
		  `birthday` int(11) NOT NULL DEFAULT '0',
		  `hometown` varchar(255) NOT NULL,
		  `address` varchar(255) NOT NULL,
		  `email` varchar(255) NOT NULL,
		  `phone` varchar(255) NOT NULL,
		  
		  `study_result` longtext NOT NULL,
		  `subject_registered` longtext NOT NULL,
		  `class_registered` longtext NOT NULL,
		  `class_ids` mediumtext NOT NULL,
		  `subject_log` longtext NOT NULL,
		  `off_class_count` longtext NOT NULL,
		  
		  `study_status` int(11) NOT NULL DEFAULT '0',
		  
		  `student_desc` mediumtext NOT NULL,
		  `weight` smallint(4) NOT NULL DEFAULT '0',
		  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
		  `add_time` int(11) NOT NULL DEFAULT '0',
		  `edit_time` int(11) NOT NULL DEFAULT '0',
		  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
		  PRIMARY KEY (`student_id`),
		  UNIQUE KEY `userid` (`userid`),
		  UNIQUE KEY `student_code` (`student_code`)
		) ENGINE=MyISAM";
		
		if( !$db->sql_query($_sql_string) ) return false;
		else
		{
			file_put_contents(NV_ROOTDIR . '/modules/' . $module_file . '/data/student_table_data.txt', $table . PHP_EOL, FILE_APPEND | LOCK_EX);
			nv_del_moduleCache( $module_name );
			return true;
		}
	}
	else return true;
}

function VnpGetStudent($studentid)
{
	global $db,$module_data, $admin_info;
	
	if( intval($studentid) > 0 )
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_student` WHERE `student_id`=" . intval( $studentid );
		$result = $db->sql_query( $sql );
	
		if( $db->sql_numrows( $result ) == 1 )
		{
			return $db->sql_fetchrow( $result );
		}
		else return false;
	}
	else return false;
}

function VnpUpdateStudent( $student, $table = '' )
{
	global $db,$module_data, $admin_info;
	
	$sql = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "_student" . $table . "` SET
			`userid` = " . intval( $student['userid'] ) . ",
			`faculty_id` = " . intval( $student['faculty_id'] ) . ",
			`level_id` = " . intval( $student['level_id'] ) . ",
			`base_class_id` =" . $db->dbescape( $student['base_class_id'] ) . ",
            `student_name` =" . $db->dbescape( $student['student_name'] ) . ",
			`student_code` =" . $db->dbescape( $student['student_code'] ) . ",
			`course_id` =" . $db->dbescape( $student['course_id'] ) . ",
			`year` = " . intval( $student['year'] ) . ",
			`birthday` = " . intval( $student['birthday'] ) . ",
			`hometown` =" . $db->dbescape( $student['hometown'] ) . ",
			`address` =" . $db->dbescape( $student['address'] ) . ",
			`email` =" . $db->dbescape( $student['email'] ) . ",
			`phone` =" . $db->dbescape( $student['phone'] ) . ",
            `student_desc`= " .  $db->dbescape( $student['student_desc'] ) . ",
			`edit_time`=" . NV_CURRENTTIME . " 
	WHERE `student_id` =" . $student['student_id'];
	$db->sql_query( $sql );
	
	if( $db->sql_affectedrows() > 0 )
	{
		return true;
	}
	else return false;
}

function VnpAddStudent( $student, $table = '' )
{
	global $db,$module_data, $admin_info;
	
	if( ! empty( $student['birthday'] ) and preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $student['birthday'], $m ) )
	{
		$student['birthday'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	
	( isset($student['student_id']) && $student['student_id'] > 0 ) ? $_stdID = $student['student_id'] : $_stdID = 'NULL';
	
	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_student" . $table . "` VALUES (
            " . $_stdID . ",
			" . intval( $student['userid'] ) . ",
			" . intval( $student['faculty_id'] ) . ",
			" . intval( $student['level_id'] ) . ",
			" . $db->dbescape( $student['base_class_id'] ) . ",
			" . $db->dbescape( $student['student_name'] ) . ",
			" . $db->dbescape( $student['student_code'] ) . ",
			" . $db->dbescape( $student['course_id'] ) . ",
			" . intval( $student['year'] ) . ",
			'',
			'',
			" . intval( $student['birthday'] ) . ",
			" . $db->dbescape( $student['hometown'] ) . ",
			" . $db->dbescape( $student['address'] ) . ",
			" . $db->dbescape( $student['email'] ) . ",
			" . $db->dbescape( $student['phone'] ) . ",
			'',
			'',
			'',
			'',
			'',
			'',
			1,
			" . $db->dbescape( $student['student_desc'] ) . ",
			0,
			" . $admin_info['admin_id'] . ",
			" . NV_CURRENTTIME . ",
			" . NV_CURRENTTIME . ", 1);";
	if( $_id = $db->sql_query_insert_id( $sql ) ) return $_id;
	else return 0;
}

function checkUserPosition($userid)
{
	global $db, $module_data;
	
	if( intval($userid) > 0 )
	{
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_teacher` WHERE `userid`=" . intval( $userid );
		$result = $db->sql_query( $sql );
	
		if( $db->sql_numrows( $result ) == 1 )
		{
			$data = $db->sql_fetchrow( $result );
			$data['type'] = 'teacher';
		}
		else
		{
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_student` WHERE `userid`=" . intval( $userid );
			$result = $db->sql_query( $sql );
		
			if( $db->sql_numrows( $result ) == 1 )
			{
				$data = $db->sql_fetchrow( $result );
				$data['type'] = 'student';
			}
		}
		return $data;
	}
	return false;
}

function VnpDeleteStudent( $stdID, $table = '' )
{
	global $db, $module_data;
	
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_student" . $table . "` WHERE `student_id`=" . intval($stdID);
	if( $db->sql_query($sql) ) return true;
	else return false;
}

function p($data = array())
{
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	die();
}

function vnp_error($msg, $type = 'error')
{
	if( $type == 'error' )
	{
		$class = 'label label-important';
	}
	return '<div class="' . $class . '">' . $msg . '</div>';
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
	$year[0] = array( 'year' => ' - - - - - - - ' );
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

function updateStudentRegistedClass($base_class_id, $faculty_id, $year, $class_ids)
{
	global $module_data, $db;
	
	
	$table = 'student_' . $faculty_id . '_' . $year;
	
	$class_ids = explode(',', $class_ids);
	$classids = array();
	foreach( $class_ids as $_classid)
	{
		if(!empty($_classid) ) $classids[] = intval($_classid);
	}
	$classids = implode(',', $classids);

	$sql = "SELECT `class_id`, `term_id`, `class_time`, `class_week`, `subject_id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_class` WHERE `class_id` IN (" . $classids . ")";
	$result = $db->sql_query( $sql );
	
	$classData = array();
	
	if( $db->sql_numrows( $result ) > 0 )
	{
		while( $_class = $db->sql_fetchrow( $result ) )
		{
			$classData[] = $_class;
		}
	}
	
	$sql = "SELECT `class_registered` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $table . "` WHERE `base_class_id`=" . $base_class_id;
	$result = $db->sql_query( $sql );
	
	if( $db->sql_numrows( $result ) > 0 )
	{
		$student = $db->sql_fetchrow( $result );
		$registered_class = $student['class_registered'];
		if( empty($registered_class) ) $registered_class = array();
		else $registered_class = unserialize($registered_class);
		foreach( $classData as $_class)
		{
			$registered_class[$_class['term_id']] = $_class['class_id'];
		}
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $table . "` SET
		`class_registered`=" . $db->dbescape(serialize($registered_class)) . " WHERE `base_class_id`=" . $base_class_id;
		if( $db->sql_query( $sql ) )
		{
			$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_student` SET
		`class_registered`=" . $db->dbescape(serialize($registered_class)) . " WHERE `base_class_id`=" . $base_class_id;
			$db->sql_query( $sql );
		}
	}
}

function vnp_msg($msg)
{
	if( !empty( $msg ) )
	return '<div class="' . $msg['type'] . '">' . $msg['content'] . '</div>';
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
			foreach( $termData as $taxKey => $taxData )
			{
				if( !empty( $valueKey ) && !empty( $titleKey ) )
				{
					( $taxData[$valueKey] == $defaultValue ) ? $slt = 'selected="selected"' : $slt = '';
					$selectBox[] = '<option ' . $slt . ' value="' . $taxData[$valueKey] . '">' . $taxData[$titleKey] . '</option>';
				}
				else
				{
					( $taxKey == $defaultValue ) ? $slt = 'selected="selected"' : $slt = '';
					$selectBox[] = '<option ' . $slt . ' value="' . $taxKey . '">' . $taxData . '</option>';
				}
			}
		}
	}
	return '<select id="' . $selectBoxID . '" name="' . $name . '" ' . $otherAttr . '>' . implode( PHP_EOL, $selectBox ) . '</select>';
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

?>