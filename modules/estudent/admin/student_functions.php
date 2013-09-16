<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) || !defined( 'STUDENT_FUNCTION' ) ) die( 'Stop!!!' );

$_student = $nv_Request->get_typed_array( 'student', 'post', 'string', array() );
$student = array_merge($_student, $student);
$studentid = $nv_Request->get_int( 'studentid', 'post,get', 0 );
//p($student);

$base_class_id = 0;
$student['base_class_id'] = explode(',', $student['base_class_id']);
foreach( $student['base_class_id'] as $_base_class_id )
{
	if( !empty( $_base_class_id ) ) $base_class_id = $_base_class_id;
}
$student['base_class_id'] = $_base_class_id;

$logMsg = array();

if( $studentid )
{
	$student['student_id'] = $studentid;
	$_backUpStudent = VnpGetStudent($studentid);
	$new_table = '_' . $student['faculty_id'] . '_' . $student['year'];
	$old_table = '_' . $_backUpStudent['faculty_id'] . '_' . $_backUpStudent['year'];
	$_error = false;
	if( $new_table != $old_table )
	{
		$tableData = array( 'faculty_id' => $student['faculty_id'], 'year' => $student['year'] );
		if( createStudentTable($tableData) )
		{
			if( !VnpAddStudent( $_backUpStudent, $new_table ) )
			{
				$_error = true;
				$msg = array( 'content' => $lang_module['error_student_table_move_student'] . ' - Student ID: ' . $student['student_id'] . ' - Table: ' . $old_table . ' ---> ' . $new_table, 'type' => 'error' );
				$logMsg = array( 'Error move student ', $lang_module['error_student_table_move_student'] . ' - Student ID: ' . $student['student_id'] . ' - Table: ' . $old_table . ' ---> ' . $new_table );
			}
		}
		else
		{
			$_error = true;
			$msg = array( 'content' => $lang_module['error_create_student_table'] . ' - ' . $new_table, 'type' => 'error' );
			$logMsg = array( 'Error create student table', $lang_module['error_create_student_table'] . ' - '  . $new_table );
		}
	}
	if( !$_error )
	{
		if( VnpUpdateStudent( $student ) )
		{
			if( VnpUpdateStudent( $student, $new_table ) )
			{
				$msg = array( 'content' => $lang_module['success_update_student'] . ' - ' . $student['student_id'] . ' - ' . $student['student_name'], 'type' => 'success' );
				$logMsg = array( 'Success update student', $lang_module['success_update_student'] . ' - '  . $student['student_id'] . ' - ' . $student['student_name'] );
			}
			else
			{
				VnpUpdateStudent( $_backUpStudent );
				
				$msg = array( 'content' => $lang_module['error_update_student'] . ' - '  . $student['student_id'] . ' - ' . $student['student_name'] . ' - ' . $_backUpStudent['faculty_id'] . '_' . $_backUpStudent['year'], 'type' => 'error' );
				$logMsg = array( 'Error update student', $lang_module['error_update_student'] . ' - '  . $student['student_id'] . ' - ' . $student['student_name'] . ' - ' . $_backUpStudent['faculty_id'] . '_' . $_backUpStudent['year'] );
			}
		}
		else
		{
			$msg = array( 'content' => $lang_module['error_update_student'] . ' - '  . $student['student_id'] . ' - ' . $student['student_name'] . ' - student', 'type' => 'error' );
				$logMsg = array( 'Error update student', $lang_module['error_update_student'] . ' - '  . $student['student_id'] . ' - ' . $student['student_name'] . ' - student' );
		}
	}
}
else
{
	$stdID = VnpAddStudent( $student );
	if( $stdID > 0 )
	{
		$student['student_id'] = $stdID;
		$_stdTable = '_' . $add_config['faculty_id'] . '_' . $add_config['year'];
		if( $stdID != VnpAddStudent( $student, $_stdTable ) )
		{
			if( VnpDeleteStudent( $stdID ) )
			{
				$msg = array( 'content' => $lang_module['cannot_add_student_faculty_table'] . $_stdTable . ' - ' . $globalTax['faculty'][$add_config['faculty_id']]['faculty_name'] . ' - ' . $globalTax['year'][$add_config['year']]['year'], 'type' => 'error' );
				$logMsg = array( 'Add student failed', $lang_module['cannot_add_student_faculty_table'] . $_stdTable . ' - ' . $globalTax['faculty'][$add_config['faculty_id']]['faculty_name'] . ' - ' . $globalTax['year'][$add_config['year']]['year'] );
			}
		}
		else
		{
			$msg = array( 'content' => $lang_module['add_student_success'] . ' - ' . $stdID . ' - ' . $student['student_name'], 'type' => 'success' );
			
			$logMsg = array( $lang_module['add_student_success'], $stdID . ' - ' . $student['student_name'] );
		}
	}
	else
	{
		$msg = array( 'content' => $lang_module['cannot_add_student_faculty_table'] . ' - student', 'type' => 'error' );
		$logMsg = array( 'Add student failed', $lang_module['cannot_add_student_faculty_table'] . ' - student' );
	}
}

nv_insert_logs( NV_LANG_DATA, $module_name, $logMsg[0], $logMsg[1], $admin_info['userid'] );

?>