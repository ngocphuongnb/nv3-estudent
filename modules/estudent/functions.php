<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_ESTUDENT', true );
include NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

$userData = checkUserPosition($user_info['userid']);
if( $op == 'manage' )
{
	//p(checkUserPosition(3));
}



?>