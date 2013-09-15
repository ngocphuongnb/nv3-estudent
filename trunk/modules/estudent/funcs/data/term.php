<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_MOD_ESTUDENT' ) && !defined('IS_DEAN') ) die( 'Stop!!!' );

define( 'TERM_FUNCTION', true );

$msg = array();
$form_action = '';

$term = array(
				'term_id' => 0,
				'term_name' => '',
				'year' => date("Y")
			);

$xtpl = new XTemplate( "add_term.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$termid = $nv_Request->get_int( 'termid', 'post,get', 0 );
$action = $nv_Request->get_string( 'action', 'get', '' );

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$term = $nv_Request->get_typed_array( 'term', 'post', 'string', array() );
	require( 'term_functions.php' );
}
else
{
	if( $action == '' || $action == 'list' )
	{
		$xtpl = new XTemplate( "term.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'GLANG', $lang_global );
		$xtpl->assign( 'ADD_LINK', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/term&amp;action=add" );
		
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_term`";
		$result = $db->sql_query( $sql );
	
		if( $db->sql_numrows( $result ) > 0 )
		{
			$i = 1;
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$array_status = array( $lang_module['deactive'], $lang_module['active'] );
				$row['class'] = ( ++$i % 2 ) ? " class=\"second\"" : "";
				$row['url_edit'] = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/term&amp;action=add&amp;termid=" . $row['term_id'];
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
				$i++;
			}
		}
	}
	elseif( $action == 'add' )
	{
		$termid = $nv_Request->get_int( 'termid', 'get', 0 );		
		if( $termid == 0 )
		{
			$form_action = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/term&amp;action=add";
		}
		else
		{
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_term` WHERE `term_id`=" . $termid;
			$result = $db->sql_query( $sql );
		
			if( $db->sql_numrows( $result ) != 1 )
			{
				Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "/term&action=add" );
				die();
			}
		
			$term = $db->sql_fetchrow( $result );			
			$form_action = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/term&amp;action=add&amp;termid=" . $termid;			
		}		
	}
}

if( $action == 'add' )
{
	$xtpl->assign( 'TERM_SLB', getTaxSelectBox( $globalTax['year'], 'term[year]', $term['year'], NULL, 'year', 'year' ) );
	$xtpl->assign( 'TERM', $term );
}

$xtpl->assign( 'FORM_ACTION', $form_action );

$vnp_content = vnp_msg($msg);
$xtpl->parse( 'main' );
$vnp_content .= $xtpl->text( 'main' );

?>