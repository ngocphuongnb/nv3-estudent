<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 05/07/2010 09:47
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	"name" => "Estudent", //
	"modfuncs" => "main,manage,ajax_get_item,get_class,get_class_info,update_mark", //
	"is_sysmod" => 0, //
	"virtual" => 1, //
	"version" => "1.0.00", //
	"date" => "Thur, 13 Sep 2013 00:00:00 GMT", //
	"author" => "Nguyen Ngoc Phuong (nguyenngocphuongnb@gmail.com)", //
	"note" => "", //
	"uploads_dir" => array( $module_name )
);

?>