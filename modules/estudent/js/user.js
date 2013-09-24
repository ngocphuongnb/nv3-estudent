function getIDs( mode, container, IDs )
{
	if( mode == 'teacher' || mode == 'subject' || mode == 'class' || mode == 'base_class' )
	{
		var _currentIDs = document.getElementById(container).value;
		if( !_currentIDs  && _currentIDs != 0 )
		{
			if( typeof IDs == 'undefined' ) IDs = '';
		}
		else IDs = _currentIDs;
		var _url = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax_get_item&mode=' + mode + '&container=' + container + '&listid=' + IDs;
		nv_open_browse_file( _url, 'NVImg', '950', '600', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' )
	}
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

function vnp_update_class(class_id, field, value)
{
	var nv_timer = nv_settimeout_disable('change_class_' + field + '_' + class_id, 5000);
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=class_ajax_action&action=field&field_name=' + field + '&field_value=' + value + '&listid=' + class_id + '&num=' + nv_randomPassword(8), '', 'nv_ajax_chang_res');
	return;
}
function vnp_class_change_res(res)
{
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
function choose_this_class(class_id, class_info_url)
{
	if( typeof class_id !== 'undefined' )
	{
		$.ajax({
			type: 'POST',
			url: class_info_url + '&class_id=' + class_id,
			dataType: 'json',
			data: {class_id: class_id},
			success: function(result)
			{
				if(result.status == 'ok')
				{
					var duplicateTimeTable = new Array();				
					class_time = result.data.class_time;
					var _class_time = new Array();
					_class_time = class_time.split(',');
					var total_class_times = _class_time.length;
					var i = 0;
					var day_data = new Array();
					for( i = 0; i < total_class_times; i++ )
					{
						day_data = _class_time[i].split('_');
						$('#day-' + day_data[0] + ' input[type="checkbox"]', opener.document).each(function(index, element) {
							if( this.value == day_data[1] )
							{
								if( $(this).is(':checked') )
								{
									duplicateTimeTable.push('Trùng thời khóa biểu: Thứ ' + day_data[0] + ' tiết ' + day_data[1]);
								}
								else
								{
									$(this).attr('checked', 'checked');
									$(this).parent().addClass('class-time-active');
								}
							}
						});
					}
					if( duplicateTimeTable.length > 0 )
					{
						alert( duplicateTimeTable.join('\n\r\t'));
					}
					else
					{
						var class_reged = $('#term-class-reged', opener.document).val();
						var _class_reged = class_reged.split(',');
						_class_reged.push(result.data.class_id);
						class_reged = _class_reged.join(',');
						$('#term-class-reged', opener.document).val(class_reged);
						$('#vnpclass-' + result.data.class_id, opener.document).remove();
						$('#vnpsubject-' + result.data.subject_id, opener.document).remove();
						$('#class_ctner', opener.document).append('<li id="vnpclass-' + result.data.class_id + '" class="vnp-u"><a href="' + result.data.class_link + '" title="' + result.data.class_name + '">' + result.data.class_name + ' </a> Tuần: ' + result.data.class_week + '<span class="remove" onclick="remove_class(' + result.data.class_id + ',' + result.data.subject_id + ',\'' + result.data.class_time + '\')"></span></li>');
						$('#subject_ctner', opener.document).append('<li id="vnpsubject-' + result.data.subject_id + '" class="vnp-u"><a href="' + result.data.subject_link + '" title="' + result.data.subject_name + '">' + result.data.subject_name + '</a> (' + result.data.class_time + ')</li>');
					}
				}
				else
				{
					alert('Error, please try again!');
				}
			},
			complete: function()
			{
				window.close();
			}
		})
	}
	//$('#class_ctner', opener.document).html(class_time);
}

function remove_class(class_id, subject_id, class_time)
{
	if(class_id > 0 && subject_id > 0)
	{
		if( confirm('Bạn có chắc chắn xóa lớp này?') )
		{
			var class_reged = $('#term-class-reged').val();
			var _class_reged = class_reged.split(',');
			
			
			var index = _class_reged.indexOf(String(class_id));	
			if(index > -1)
			{
				_class_reged.splice(index, 1);
			}
			
			class_reged = _class_reged.join(',');
			$('#term-class-reged').val(class_reged);
			$('#vnpclass-' + class_id).remove();
			$('#vnpsubject-' + subject_id).remove();
			
			var _class_time = new Array();
			_class_time = class_time.split(',');
			var total_class_times = _class_time.length;
			var i = 0;
			var day_data = new Array();
			for( i = 0; i < total_class_times; i++ )
			{
				day_data = _class_time[i].split('_');
				$('#day-' + day_data[0] + ' input[type="checkbox"]').each(function(index, element) {
					if( this.value == day_data[1] )
					{
						$(this).removeAttr('checked');
						$(this).parent().removeClass('class-time-active');
					}
				});
			}
		}
	}
}
function nv_opener(theURL, winName, w, h, features) {
	LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
	TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
	settings = 'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition;
	if (features != '') {
		settings = settings + ',' + features;
	}
	window.open(theURL, winName, settings);
	window.blur();
	return false;
}
function class_proccess(class_week, class_time)
{
	alert(class_time);
	$('#class_ctner').html(class_time);
}
function vnp_roll_call( class_id, student_id )
{
	if( typeof value === 'undefined' || value == 2 || typeof class_id === 'undefined' || typeof student_id === 'undefined' )
	{
		return;
	}
	else
	{
		value = $('#change_student_roll_call_' + student_id).val();
		var nv_timer = nv_settimeout_disable('change_student_roll_call_' + student_id, 1000);
		
		var time = $('#roll-call-dat').val();
	
		$.ajax({
			type:'POST', 
			url: nv_siteroot + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=roll_call',
			data:{stdid : student_id, class_id: class_id, value: value, time : time},
			success: function(data){
				res = data.split('_');
				if(res[0] == 'ok')
				{
					nv_settimeout_disable('change_student_roll_call_' + student_id,1);
				}
				else
				{
					alert('Có lỗi sảy ra');
				}
			}
		});
	}
}
function updateStdMark(stdID, class_id, type, value)
{
	//alert('std-' + type + '-' + stdID);
	var nv_timer = nv_settimeout_disable('std-' + type + '-' + stdID, 5000);
	
	$.ajax({
		type:'POST', 
		url: nv_siteroot + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=update_mark',
		data:{stdid : stdID, class_id: class_id, type: type, value: value},
		success: function(data){
			//nv_update_mark_res(data)
			res = data.split('_');
			if(res[0] == 'ok')
			{
				nv_settimeout_disable('std-' + type + '-' + res[1],1);
			}
			else
			{
				alert('Có lỗi sảy ra');
			}
		}
	});
}
function SentMark(class_id)
{
	$.ajax({
		type:'POST', 
		url: nv_siteroot + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=update_mark',
		data:{sent_mark : 1, class_id: class_id},
		success: function(res){
			res = res.split('_');
			if(res[0] == 'ok')
			{
				alert('Gửi điểm thành công!');
			}
			else alert('Lỗi gửi điểm!');
		}
	});
}
function nv_update_mark_res(res)
{
	res = res.split('_');
	if(res[0] == 'ok')
	{
		nv_settimeout_disable('std-' + res[1],1);
	}
	else
	{
		alert('Có lỗi sảy ra');
	}
}

function nv_open_browse_file(theURL, winName, w, h, features) {
	LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
	TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
	settings = 'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition;
	if (features != '') {
		settings = settings + ',' + features;
	}
	window.open(theURL, winName, settings);
	window.blur();
}
$(document).ready(function() {
	var pageData = '';
	$('ul.vnp-pagination').each(function() {
		$(this).find('*').each(function(){
			if( $(this).prop("tagName") == 'STRONG' )
			{
				pageData += '<li class="active"><a><strong>' + $(this).html() + '</strong></a></li>';
			}
			else if( $(this).prop("tagName") == 'A' )
			{
				pageData += '<li><a href="' + $(this).attr('href') + '">' + $(this).html() + '</a></li>';
			}
		});
		$(this).html(pageData);
    });    
});