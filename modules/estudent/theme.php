<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES ., JSC. All rights reserved
 * @Createdate Jul 11, 2010  8:43:46 PM
 */

if( ! defined( 'NV_IS_MOD_ESTUDENT' ) ) die( 'Stop!!!' );

function vnp_page_content($menu, $vnp_content)
{
	global $module_file, $lang_module, $module_info;

	$xtpl = new XTemplate( "manage.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	
	if( !empty( $menu ) )
	{
		foreach( $menu as $menuKey => $menuValue )
		{
			$xtpl->assign( 'MENU', $menuValue );
			$xtpl->parse( 'main.menu.loop' );
		}
		$xtpl->parse( 'main.menu' );
	}
	
	$xtpl->assign( 'CONTENT', $vnp_content );
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}



?>