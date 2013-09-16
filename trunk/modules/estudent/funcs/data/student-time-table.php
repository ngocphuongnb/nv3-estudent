<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_MOD_ESTUDENT' ) && !defined('IS_STUDENT') ) die( 'Stop!!!' );

if( $userData['type'] == 'student' )
{
	$base_class = array();
	$term_id = $nv_Request->get_int( 'term_id', 'post,get', 0 );
	$main_time_table = array();
	
	if( !empty($userData['base_class_id']) && $term_id > 0 )
	{
		$base_clid = 0;
		$userData['base_class_id'] = explode(',', $userData['base_class_id']);
		foreach( $userData['base_class_id'] as $base_clid )
		{
			if( !empty( $base_clid ) ) $base_classid = $base_clid;
		}
		
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_base_class` WHERE `base_class_id`=" . $base_classid;
		$result = $db->sql_query( $sql );
	
		$base_class = $db->sql_fetchrow( $result );
		//p($base_class);
		
		if( $base_class['time_table'] == '' )
		{
			$base_class['_class'] = '';
			$base_class['vnp_class'] = '';
		}
		else
		{
			$base_class['_class'] = unserialize($base_class['time_table']);
			$base_class['_class'] = isset($base_class['_class'][$term_id]) ? $base_class['_class'][$term_id] : '';
		}
		$base_class['class_time'] = '';
		if( !empty($base_class['_class']) )
		{
			$base_class['_class'] = explode(',', $base_class['_class']);
			$classids = array();
			foreach( $base_class['_class'] as $_classid)
			{
				if(!empty($_classid) ) $classids[] = intval($_classid);
			}
			$base_class['vnp_class'] = $classids = implode(',', $classids);
			$sql = "SELECT `class_id`, `class_name`, `class_time`, `class_week`, `subject_id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_class` WHERE `class_id` IN (" . $classids . ")";
			$result = $db->sql_query( $sql );
			
			$main_time_table = '';
			
			if( $db->sql_numrows( $result ) > 0 )
			{
				while( $_class = $db->sql_fetchrow( $result ) )
				{
					$base_class['class_time'][] = $_class['class_time'];
					
					$subject_ids = explode(',', $_class['subject_id']);
					foreach( $subject_ids as $subjectid )
					{
						if( !empty($subjectid) ) $class['subject_id'] = $subjectid;
					}
					$subject = $globalTax['subject'][$class['subject_id']];
					
					$__class_times = explode(',', $_class['class_time'] );
					foreach( $__class_times as $__cl_time )
					{
						if( !empty( $__cl_time ) )
						{
							$__cl_time = explode('_', $__cl_time);
							$main_time_table[$__cl_time[0]][$_class['class_name']][] = $__cl_time[1];
						}
					}
					
					$time_table['reged_class'][] = '<li id="vnpclass-' . $_class['class_id'] . '" class="vnp-u">' .$_class['class_name'] . ' Tuần: ' .$_class['class_week'] . '</li>';
					$time_table['reged_subject'][] = '<li id="vnpsubject-' . $subject['subject_id'] . '" class="vnp-u">' . $subject['subject_name'] . '  ' . $_class['class_time'] . '</li>';
				}
			}
			//p($main_time_table);
			$base_class['class_time'] = implode(',',$base_class['class_time']);
		}
		$week = array();
		foreach( $main_time_table as $__day => $___class )
		{
			$week[] = '<h3>' . $globalConfig['week_data'][$__day] . '</h3>';
			$week[] = '<ol class="vnp-registered">';
			
			foreach( $___class as $__class_name => $__class_value )
			{
				$week[] = '<li class="vnp-u">' . $__class_name . ' tiết ' . implode(',', $__class_value) . '</li>';
			}
			$week[] = '</ol>';
		}
		
		$base_class['main_time'] = implode(PHP_EOL, $week);
		
		$class_time = explode(',', $base_class['class_time']);
		$week = '';
		foreach( $globalConfig['week_data'] as $key => $day )
		{
			$week .= '<div id="day-' . $key . '" class="class-time"><div class="day">' . $day . '</div>';
			for( $i = 1; $i <= $globalConfig['day_period']; $i++ )
			{
				$_compareKey = $key . '_' . $i;
				$cked = in_array($_compareKey, $class_time) ? 'checked="checked"' : '';
				$week .= '<label><input disabled="disabled" type="checkbox" ' . $cked . ' name="class[class_time][' . $key . '][]" value="' . $i . '" />' . $lang_module['class_period'] . $i . '</label>';
			}
			$week .= '</div>';
		}
		$base_class['class_time'] = $week;
		$base_class['term'] = $globalTax['term'][$term_id]['term_name'];
	}
			
	$msg = '';
	$xtpl = new XTemplate( "student_time_table.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	
	$globalTax['term'][0] = array('term_id' => 0, 'term_name' => $lang_module['select_term']);
	$_link = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/time-table&amp;term_id=";
	$onchange = 'onchange="top.location.href=\'' . $_link . '\'+this.options[this.selectedIndex].value;return;"';
	$xtpl->assign( 'TERM_SLB', getTaxSelectBox( $globalTax['term'], 'base_class[term_id]', $term_id, NULL, 'term_id', 'term_name', $onchange ) );
	$xtpl->parse( 'main.select_term' );

	if( $term_id == 0 )
	{
		
	}
	else
	{	
		if( !empty($time_table['reged_class']) )
		{
			foreach( $time_table['reged_class'] as $_reged_class )
			{
				$xtpl->assign( 'CLASS', $_reged_class  );
				$xtpl->parse( 'main.add.reged_class' );
			}
		}
		if( !empty($time_table['reged_subject']) )
		{
			foreach( $time_table['reged_subject'] as $_reged_subject )
			{
				$xtpl->assign( 'SUBJECT', $_reged_subject  );
				$xtpl->parse( 'main.add.reged_subject' );
			}
		}
		$xtpl->assign( 'GET_CLASS', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=get_class" );
		$xtpl->assign( 'BASE_CLASS', $base_class );
		$form_action = '';
		$xtpl->assign( 'FORM_ACTION', $form_action );
		$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
		$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
		$xtpl->assign( 'MODULE_NAME', $module_name );
		$xtpl->assign( 'FORM_ACTION', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/time-table&amp;base_classid=" . $base_classid . "&amp;term_id=" . $term_id );
		
		$vnp_content = vnp_msg($msg);
		$xtpl->parse( 'main.add' );
	}
	$xtpl->parse( 'main' );
	$vnp_content .= $xtpl->text( 'main' );
}

?>