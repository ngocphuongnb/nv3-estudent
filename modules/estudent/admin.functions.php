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

$emailNo = array();
$named = array();

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


include NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';


function getBaseClassByFacultyID($faculty_id = NULL)
{
	global $db, $module_data;
	
	$_base_class = array();
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_base_class` WHERE `faculty_id`=" . intval($faculty_id);
	$result = nv_db_cache( $sql );

	foreach( $result as $row )
	{
		$_base_class[$row['base_class_id']] = $row;
	}
	return $_base_class;
}


function user_generator()
{
	global $module_file, $emailNo, $named;

	$firstNames = file(NV_ROOTDIR . '/modules/' . $module_file . '/admin/firstname.txt', FILE_IGNORE_NEW_LINES);
	$lastNames = file(NV_ROOTDIR . '/modules/' . $module_file . '/admin/lastname.txt', FILE_IGNORE_NEW_LINES);
	$regLogs= file_get_contents(NV_ROOTDIR . '/modules/' . $module_file . '/admin/reglog.txt');
	//$regLogs = 'a:2:{s:4:"name";a:0:{}s:5:"email";a:0:{}}';
	$regLogs = unserialize( $regLogs );
	//p($regLogs);
	/*$regLogs['name'] = array();
	$regLogs['email'] = array();
	p(serialize($regLogs));*/
	$_name = $firstNames[rand(0,51)] . ' ' . str_replace('&nbsp;', ' ', $lastNames[rand(0,3866)]);
	while( in_array( $_name, $regLogs['name'] ) )
	{
		$_name = $firstNames[rand(0,51)] . ' ' . str_replace('&nbsp;', ' ', $lastNames[rand(0,3866)]);
	}
	$regLogs['name'][] = $_name;
	$regLogs['name'] = array_unique($regLogs['name']);
	
	$bd = '10/03/1992';
	$alias = strtolower(change_alias($_name));
	$username = str_replace('-', '', $alias);
	$email = $username . '@gmail.com';
	$regLogs['email'][] = $email;
	$regLogs['email'] = array_unique($regLogs['email']);
	//p(serialize($regLogs));
	file_put_contents(NV_ROOTDIR . '/modules/' . $module_file . '/admin/reglog.txt', serialize($regLogs));
	
	$user_data = array(
		'username' => $username,
		'password' => '123456',
		'email' => $email,
		'full_name' => $_name,
		'gender' => '1',
		'sig' => '',
		'question' => '123',
		'answer' => '123',
		'view_mail' => 1,
		'birthday' => $bd,
		'alias' => $alias,
		'in_groups' => array()	
	);
	$rt = create_user($user_data);
	return $rt;
}

function create_user($user_data)
{
	global $db_config, $db, $global_config, $crypt;
	
	$_user = $user_data;
	
	if( preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $_user['birthday'], $m ) )
	{
		$_user['birthday'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$_user['birthday'] = 0;
	}
	
	$password = $crypt->hash( $_user['password'] );
	
	$sql = "INSERT INTO `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` (
		`userid`, `username`, `md5username`, `password`, `email`, `full_name`, `gender`, `birthday`, `sig`, `regdate`,
		`question`, `answer`, `passlostkey`, `view_mail`,
		`remember`, `in_groups`, `active`, `checknum`, `last_login`, `last_ip`, `last_agent`, `last_openid`, `idsite`)
		VALUES (
		NULL,
		" . $db->dbescape( $_user['username'] ) . ",
		" . $db->dbescape( nv_md5safe( $_user['username'] ) ) . ",
		" . $db->dbescape( $password ) . ",
		" . $db->dbescape( $_user['email'] ) . ",
		" . $db->dbescape( $_user['full_name'] ) . ",
		" . $db->dbescape( $_user['gender'] ) . ",
		" . $_user['birthday'] . ",
		" . $db->dbescape( $_user['sig'] ) . ",
		" . NV_CURRENTTIME . ",
		" . $db->dbescape( $_user['question'] ) . ",
		" . $db->dbescape( $_user['answer'] ) . ",
		'',
		 " . $_user['view_mail'] . ",
		 1,
		 '" . implode( ',', $_user['in_groups'] ) . "', 1, '', 0, '', '', '', " . $global_config['idsite'] . ")";

	$userid = $db->sql_query_insert_id( $sql );
	return array( 'userid' => $userid, 'fullname' => $_user['full_name'], 'alias' => $_user['alias'], 'email' => $_user['email'] );
}

function base_class_generator()
{
	global $db, $module_data, $globalTax, $nv_Request, $admin_info;
	
	$index = $nv_Request->get_int( 'index', 'get', 0 );
	
	$faculty_id = $index;
			
	$faculty = $globalTax['faculty'][$faculty_id]['faculty_name'];
	$rt = array();
	
	for( $i = 1; $i <= 5; $i++ )
	{
		$_rt['fullname'] = $faculty . ' ' . $i;
		$_rt['faculty'] = $faculty;
		$_teacher_id = $globalTax['teacher'][array_rand($globalTax['teacher'])]['teacher_id'];
		
		$rt[] = $_rt;
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_base_class` VALUES (
				NULL,
				" . intval( $faculty_id ) . ",
				" . intval( 1 ) . ",
				" . $db->dbescape( 'K4' ) . ",
				" . $db->dbescape( $_rt['fullname'] ) . ",
				" . $db->dbescape( change_alias($_rt['fullname']) ) . ",
				" . $db->dbescape( '' ) . ",
				" . $db->dbescape( $_teacher_id ) . ",
				" . intval( 45 ) . ",
				0,
				" . $admin_info['admin_id'] . ",
				" . NV_CURRENTTIME . ",
				" . NV_CURRENTTIME . ", 1);";
		$_id = $db->sql_query_insert_id( $sql );
	}
	return $rt;	
}

function base_sample_data()
{
	global $db, $module_name;
		$sample_data[] = "INSERT INTO `nv3_vi_estudent_faculty` (`faculty_id`, `faculty_name`, `faculty_alias`, `faculty_desc`, `weight`, `admin_id`, `add_time`, `edit_time`, `status`) VALUES
	(1, 'Công nghệ thông tin', 'cong-nghe-thong-tin', '<br  />', 0, 1, 1376379383, 1376379383, 1),
	(2, 'Điện', 'dien', '<br  />', 0, 1, 1376379388, 1376379388, 1),
	(3, 'Điện tử viễn thông', 'dien-tu-vien-thong', '<br  />', 0, 1, 1376379400, 1376379400, 1),
	(4, 'Toán tin ứng dụng', 'toan-tin-ung-dung', '<br  />', 0, 1, 1376379414, 1376379414, 1),
	(5, 'Kỹ thuật hóa học', 'ky-thuat-hoa-hoc', '<br  />', 0, 1, 1376379427, 1376379427, 1),
	(6, 'Cơ khí', 'co-khi', '<br  />', 0, 1, 1376379439, 1376379439, 1),
	(7, 'Cơ điện tử', 'co-dien-tu', '<br  />', 0, 1, 1376379447, 1376379447, 1);";
	
	
	$sample_data[] = "INSERT INTO `nv3_vi_estudent_level` (`level_id`, `level_name`, `level_alias`, `level_desc`, `weight`, `admin_id`, `add_time`, `edit_time`, `status`) VALUES
	(1, 'Đại học', 'dai-hoc', '', 0, 1, 1376379277, 1376379277, 1),
	(2, 'Cao Đẳng', 'cao-dang', '', 0, 1, 1376379293, 1376379293, 1),
	(3, 'Tại chức', 'tai-chuc', '', 0, 1, 1376379309, 1376379309, 1),
	(4, 'Liên thông', 'lien-thong', '', 0, 1, 1376379319, 1376379319, 1),
	(5, 'Chất lượng cao', 'chat-luong-cao', '', 0, 1, 1376379329, 1376379329, 1);";
	
	
	$sample_data[] = "INSERT INTO `nv3_vi_estudent_term` (`term_id`, `year`, `weeks`, `term_name`, `admin_id`, `add_time`, `edit_time`, `status`) VALUES
	(1, 2013, '1-16', '20131', 1, 1376379893, 1376379893, 1),
	(2, 2013, '18-36', '20132', 1, 1376379906, 1376379906, 1),
	(3, 2013, '40-48', '20133', 1, 1376379940, 1376379940, 1),
	(4, 2012, '40-48', '20123', 1, 1376379955, 1376379955, 1),
	(5, 2012, '17-36', '20122', 1, 1376379968, 1376379968, 1),
	(6, 2012, '1-16', '20121', 1, 1376379978, 1376379978, 1);";
	
	
	$sample_data[] = "INSERT INTO `nv3_vi_estudent_subject` (`subject_id`, `faculty_id`, `subject_name`, `subject_code`, `subject_alias`, `subject_desc`, `weight`, `admin_id`, `add_time`, `edit_time`, `practice_require`, `clpart`, `status`) VALUES
	(1, 1, 'Kỹ thuật lập trinh', 'KTLT-D', 'ky-thuat-lap-trinh', '', 2, 0, 1376379819, 1376379819, 0, 40, 1),
	(2, 1, 'Java', 'JAVA-CNTT', 'java-cntt', '', 1, 0, 1376379853, 1376379853, 1, 40, 1),
	(3, 1, 'Vẽ kỹ thuật', 'VG-DTVT', 'vg-dtvt', '', 1, 0, 1376379879, 1376379879, 1, 40, 1);";
	
	foreach( $sample_data as $_spdt )
	{
		$db->sql_query($_spdt);
	}
	nv_del_moduleCache( $module_name );
}

?>