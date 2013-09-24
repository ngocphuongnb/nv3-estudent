<?php

//user_generator();


base_sample_data();
//user_generator();

/*$regLogs= file_get_contents(NV_ROOTDIR . '/modules/' . $module_file . '/admin/reglog.txt');
	//$regLogs = 'a:2:{s:4:"name";a:0:{}s:5:"email";a:0:{}}';
	$regLogs = unserialize( $regLogs );
	p($regLogs);*/

if( $nv_Request->get_string( 'action', 'get', '' ) == 'ajax' )
{
	$mod = $nv_Request->get_string( 'mod', 'get', '' );
	$rt = array();
	for( $i = 1; $i <= 1; $i++ )
	{
		if( $mod == 'teacher' )
		{
			$_rt = user_generator();
			
			$faculty_id = rand(1,7);
			
			$_rt['faculty'] = $globalTax['faculty'][$faculty_id]['faculty_name'];
			$rt[] = $_rt;
			
			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_teacher` VALUES (
					NULL,
					" . intval( $_rt['userid'] ) . ",
					" . $faculty_id . ",
					" . $db->dbescape( $_rt['fullname'] ) . ",
					" . $db->dbescape( $_rt['alias'] ) . ",
					" . $db->dbescape( '' ) . ",
					'',
					0,
					" . $admin_info['admin_id'] . ",
					" . NV_CURRENTTIME . ",
					" . NV_CURRENTTIME . ", 1);";
			$_id = $db->sql_query_insert_id( $sql );
		}
		if( $mod == 'student' )
		{
			exit();
			$faculty_id = 7;
			$tableData = array( 'faculty_id' => $faculty_id, 'year' => 2013 );
			createStudentTable($tableData);
			
			$_rt = user_generator();
			
			$_rt['faculty_id'] = $faculty_id;
			$_rt['level_id'] = 1;
			$baseCLassByFaculty = getBaseClassByFacultyID($faculty_id);
			$_rt['base_class_id'] = $baseCLassByFaculty[array_rand($baseCLassByFaculty)]['base_class_id'];
			$_rt['student_name'] = $_rt['fullname'];
			if( $_rt['userid'] > 9999 ) $_stdCode = str_pad(($_rt['userid']%1000), 4, '0', STR_PAD_LEFT);
			else $_stdCode = str_pad(($_rt['userid']), 4, '0', STR_PAD_LEFT);
			$_rt['student_code'] = $tableData['year'] .  $_stdCode;
			$_rt['course_id'] = 'K4';
			$_rt['year'] = $tableData['year'];
			$_rt['birthday'] = 0;
			$_rt['hometown'] = '';
			$_rt['address'] = '';
			$_rt['phone'] = 0;
			$_rt['student_desc'] = '';
			$_rt['faculty'] = $globalTax['faculty'][$faculty_id]['faculty_name'];
			//p($_rt);
			$rt[] = $_rt;
			
			$stdID = VnpAddStudent( $_rt );
			//echo $stdID; die();
			if( $stdID > 0 )
			{
				$student_id = $stdID;
				$_rt['student_id'] = $student_id;
				$_stdTable = '_' . $tableData['faculty_id'] . '_' . $tableData['year'];
				if( $stdID != VnpAddStudent( $_rt, $_stdTable ) )
				{
					VnpDeleteStudent( $stdID );
				}
			}
		}
		elseif( $mod == 'base_class' )
		{
			$rt = base_class_generator();
		}
	}
	//sleep(1);
	echo json_encode($rt);
	exit();
}
else
{
	$xtpl = new XTemplate( "sp_data.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'module_name', $module_name );
	$xtpl->assign( 'AJAX_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=ajax' );
	
	
	$xtpl->parse( 'main' );
	$contents .= $xtpl->text( 'main' );
	
	
	include ( NV_ROOTDIR . '/includes/header.php' );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . '/includes/footer.php' );
}

?>