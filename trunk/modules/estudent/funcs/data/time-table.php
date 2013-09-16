<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_MOD_ESTUDENT' ) && !defined('IS_DEAN') ) die( 'Stop!!!' );

if( $userData['type'] == 'teacher' )
{
	$base_classid = $nv_Request->get_int( 'base_classid', 'post,get', 0 );
	$term_id = $nv_Request->get_int( 'term_id', 'post,get', 0 );
	$base_class = array();
	
	if( $nv_Request->get_int( 'save', 'post', 0 ) == 1 )
	{
		$sql = "SELECT `time_table`, `faculty_id`, `year` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_base_class` WHERE `base_class_id`=" . $base_classid;
		$result = $db->sql_query( $sql );
			
		if( $db->sql_numrows( $result ) == 1 )
		{
			$base_class = $db->sql_fetchrow( $result );
			if( $base_class['time_table'] == '' )
			{
				$base_class['time_table'] = array();
			}
			else
			{
				$base_class['time_table'] = unserialize($base_class['time_table']);
			}
			$term_class_reged = $nv_Request->get_string( 'term_class_reged', 'post', '' );
			$base_class['time_table'][$term_id] = $term_class_reged;
			$base_class['time_table'] = serialize($base_class['time_table']);
			
			$sql = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "_base_class` SET
					`time_table` =" . $db->dbescape( $base_class['time_table'] ) . "
			WHERE `base_class_id` =" . $base_classid;
			
			if( $db->sql_query( $sql ) )
			{
				$vnp_content = $lang_module['save_ok'];
				updateStudentRegistedClass($base_classid, $base_class['faculty_id'], $base_class['year'], $term_class_reged);
				/*$sql = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "_student` SET
					`class_registered` =" . $db->dbescape( $base_class['time_table'] ) . "
			WHERE `base_class_id` =" . $base_classid;*/
			}
			else $vnp_content = $lang_module['save_error'];
		}
	}
	else
	{	
		if( $base_classid > 0 && $term_id > 0 )
		{
			$time_table = array( 'reged_class' => '', 'reged_subject' => '' );
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_base_class` WHERE `base_class_id`=" . $base_classid;
			$result = $db->sql_query( $sql );
				
			if( $db->sql_numrows( $result ) != 1 )
			{
				Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "/base-class&action=add" );
				die();
			}
		
			$base_class = $db->sql_fetchrow( $result );
			$base_class['url_edit'] = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/base-class&amp;action=add&amp;base_classid=" . $base_class['base_class_id'];
			
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
						
						$subject_link = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=manage/subject&amp;action=add&amp;subjectid=" . $subject['subject_id'];
	
						$class_link = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=manage/term-class&amp;action=add&amp;classid=" . $_class['class_id'];
						
						$time_table['reged_class'][] = '<li id="vnpclass-' . $_class['class_id'] . '" class="vnp-u"><a href="' . $class_link . '" title="' .$_class['class_name'] . '">' .$_class['class_name'] . ' </a> Tuần: ' .$_class['class_week'] . '<span class="remove" onclick="remove_class(' . $_class['class_id'] . ',' . $subject['subject_id'] . ',\'' . $_class['class_time'] . '\')"></span></li>';
						$time_table['reged_subject'][] = '<li id="vnpsubject-' . $subject['subject_id'] . '" class="vnp-u"><a href="' . $subject_link . '" title="' .$_class['class_name'] . '">' . $subject['subject_name'] . ' </a> Tuần: ' .$_class['class_time'] . '</li>';
					}
				}
				$base_class['class_time'] = implode(',',$base_class['class_time']);
			}
			
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
		$xtpl = new XTemplate( "base_class_time_table.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'GLANG', $lang_global );

		if( $term_id == 0 )
		{
			$globalTax['term'][0] = array('term_id' => 0, 'term_name' => $lang_module['select_term']);
			$_link = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/time-table&amp;base_classid=" . $base_classid . "&amp;term_id=";
			$onchange = 'onchange="top.location.href=\'' . $_link . '\'+this.options[this.selectedIndex].value;return;"';
			$xtpl->assign( 'TERM_SLB', getTaxSelectBox( $globalTax['term'], 'base_class[term_id]', $term_id, NULL, 'term_id', 'term_name', $onchange ) );
			$xtpl->parse( 'main.select_term' );
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
}

?>