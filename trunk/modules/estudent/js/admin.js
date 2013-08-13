/* *
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */
 

Array.prototype.getUnique = function(){
   var u = {}, a = [];
   for(var i = 0, l = this.length; i < l; ++i){
      if(u.hasOwnProperty(this[i])) {
         continue;
      }
      a.push(this[i]);
      u[this[i]] = 1;
   }
   return a;
}

var checkedInputs = new Array();
var titleData = new Array();

function nv_chang_weight(vid) {
	var nv_timer = nv_settimeout_disable('change_weight_' + vid, 5000);
	var new_weight = document.getElementById( 'change_weight_' + vid ).options[document.getElementById('change_weight_' + vid).selectedIndex].value;
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_weight&id=' + vid + '&new_weight=' + new_weight + '&num=' + nv_randomPassword(8), '', 'nv_ajax_chang_res');
	return;
}

function nv_chang_status(vid, table) {
	var nv_timer = nv_settimeout_disable('change_status_' + vid, 5000);
	var new_status = document.getElementById( 'change_status_' + vid ).options[document.getElementById('change_status_' + vid).selectedIndex].value;
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + table + '_ajax_action&action=status&listid=' + vid + '&new_status=' + new_status + '&num=' + nv_randomPassword(8), '', 'nv_ajax_chang_res');
	return;
}

function nv_ajax_chang_res(res) {
	var r_split = res.split("_");
	if (r_split[0] != 'OK') {
		alert(nv_is_change_act_confirm[2]);
		clearTimeout(nv_timer);
	} else {
		window.location.href = window.location.href;
	}
	return;
}

function nv_module_del(listdid, table) {
	if (confirm(nv_is_del_confirm[0])) {
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + table + '_ajax_action&action=delete&listid=' + listdid, '', 'nv_module_del_result');
	}
	return false;
}

function nv_module_del_result(res) {
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		window.location.href = window.location.href;
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

//---------------------------------------

function get_alias(id) {
	var title = strip_tags(document.getElementById('idtitle').value);
	if (title != '') {
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&title=' + encodeURIComponent(title) + '&id=' + id, '', 'res_get_alias');
	}
	return false;
}

function res_get_alias(res) {
	if (res != "") {
		document.getElementById('idalias').value = res;
	} else {
		document.getElementById('idalias').value = '';
	}
	return false;
}

function getIDs( mode, container, IDs )
{
	if( mode == 'teacher' || mode == 'subject' || mode == 'class' )
	{
		var _currentIDs = document.getElementById(container).value;
		if( !_currentIDs  && _currentIDs != 0 )
		{
			if( typeof IDs == 'undefined' ) IDs = '';
		}
		else IDs = _currentIDs;
		var _url = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax_get_item&mode=' + mode + '&container=' + container + '&listid=' + IDs;
		nv_open_browse_file( _url, 'NVImg', '850', '600', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' )
	}
}

function setCookieSelected(listid, mode)
{
	if( typeof listid == 'undefined' ) listid = '';
	if( nv_setCookie(vnp_cookie_prefix + '_vnp_selected_items_' + mode, listid) );
}

function insertToPost(container, checkedInputs, titleData)
{
	$('#' + container, opener.document).val(checkedInputs);
	var html_title = '';
	var _titleData = new Array();
	_titleData = titleData.split(',');
	for( var i = 0; i < _titleData.length; i++ )
	{
		if( _titleData[i] != '' )
		html_title += '<li>' + _titleData[i] + '</li>';
	}
	$('#' + container + '_title', opener.document).html(html_title);
	window.close();
}