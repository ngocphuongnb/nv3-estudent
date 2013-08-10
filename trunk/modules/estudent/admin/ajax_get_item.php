<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

// subject, teacher, class room, student, class

$mode = $nv_Request->get_int( 'mode', 'post', '' );

$search = array(
					'search' => 0,
					'q' => '',
					'per_page' => 10,
					'page' => 0,
					'extra_condition' => array()
					);
switch( $mode )
{
	case 'subject' :
	{
		$search['query_key'] = 'subject_name';
		$search['table'] = 'subject';
		$_faculty_id = $nv_Request->get_int( 'faculty_id', 'get', 0 );
		$search['extra_condition'][] = array( 'key' => 'faculty_id', 'value' => $_faculty_id, 'datatype' => 'int' );
		break;
	}
	case 'teacher' :
	{
		$search['query_key'] = 'teacher_name';
		$search['table'] = 'teacher';
		$_teacher_id = $nv_Request->get_int( 'teacher_id', 'get', 0 );
		$search['extra_condition'][] = array( 'key' => 'teacher_id', 'value' => $_teacher_id, 'datatype' => 'int' );
		break;
	}
}
					
if( $nv_Request->get_string( 'search', 'get', '' ) == 1 )
{
	$search['search'] = true;
	$search['q'] = $nv_Request->get_string( 'q', 'get', '' );
	$search['per_page'] = $nv_Request->get_int( 'per_page', 'get', 10 );
	$search['page'] = $nv_Request->get_int( 'page', 'get', 0 );
	
	$_s = $_string_query = '';
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
		if( $search['q'] )
		{
			$_s[] = "`" . $search['query_key'] . "` LIKE '%" . $db->dblikeescape( $search['q'] ) . "%'";
		}
		
		$_s = "WHERE " . implode(' AND ', $_s );
		unset( $search['extra_condition'] );
		unset( $search['table'] );
		$_string_query = implode( '&amp;', $search );
	}
}
	
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;" . $_string_query;
	
$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $search['table'] . "` " . $_s . " LIMIT " . $search['page'] . "," . $search['per_page'];

$result = $db->sql_query( $sql );	
$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $result_all );

if( $db->sql_numrows( $result ) > 0 )
{
	while( $row = $db->sql_fetchrow( $result ) )
	{
	}
}

include ( NV_ROOTDIR . '/includes/header.php' );
echo 'OK_' . $id;
include ( NV_ROOTDIR . '/includes/footer.php' );

?>