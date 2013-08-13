/**
 * @Project NUKEVIET 3.x
 * @Author Nguyen Ngoc Phuong (nguyenngocphuongnb@gmail.com )
 * @Copyright Nguyen Ngoc Phuong (nguyenngocphuongnb@gmail.com )
 * @Createdate 05-08-2013 10:51
 */
 
 
(function($){
	
    $.fn.InputToggle = function(options) {

        var settings = $.extend({
            childInput         	: '',
			dataAttribute		: '',
			storageVar			: 'checkedInputs',
			titleData			: '',
			featureAction		: [],
			callBackFunction	: '',
			enableCookie		: true,
			cookieKey			: '_vnp_inputToggle_cookie_tmp'
        }, options);
		
		var featureNums = settings.featureAction.length;
		for( var i = 0; i < featureNums; i++ )
		{
			var feature = settings.featureAction[i];
			$(feature.container).attr('onclick', feature.callback + ';return false;');
		}
		
		var toggleAllID = $(this).attr('id');
		$(this).click(function(e) {
            if( $('input#' + toggleAllID + ':checked').val() == 1 )
			{
				$(settings.childInput).each(function() {
                    $(this).attr('checked', 'checked');
                });
				var obj = 'add';
			}
			else
			{
				$(settings.childInput).each(function() {
                    $(this).removeAttr('checked');
                });
				var obj = 'remove';
			}
			updateCheckedList(obj );
        });
		$(settings.childInput).click(function(e) {
            if($('input:checkbox:checked' + settings.childInput).length === ($('input:checkbox' + settings.childInput)).length)
			{
				$('input#' + toggleAllID).attr('checked', 'checked');
			}
			else
			{
				$('input#' + toggleAllID).removeAttr('checked');
			}
			if( $(this).is(':checked') ) var obj = 'add_' + $(this).val() + '_' + $(this).attr(settings.dataAttribute);
			else var obj = 'remove_' + $(this).val() + '_' + $(this).attr(settings.dataAttribute);
			
			updateCheckedList(obj);
        });
		
		function updateCheckedList(obj)
		{
			var _checkedInputs = new Array();
			var _checkedTitle = new Array();

			if( settings.enableCookie )
			{
				if( obj !== 'start' )
				{
					if( obj == 'add' || obj == 'remove' )
					{
						$(settings.childInput).each(function() {
							_checkedInputs.push( $(this).val() );
							if( settings.dataAttribute != '' ) _checkedTitle.push($(this).attr(settings.dataAttribute));
						});		
						withCookie( obj, String( arrayUnique(_checkedInputs)), String( arrayUnique(_checkedTitle)) );
					}
					else
					{
						obj = obj.split('_');
						if( obj[0] == 'add' || obj[0] == 'remove' )
						{
							if( typeof obj[1] !== 'undefined' )
							{
								withCookie( obj[0], String( obj[1]), String( obj[2]) );
							}
						}
					}
					window[settings.storageVar] = String( getCookie(settings.cookieKey) );
					if( settings.dataAttribute != '' )
					{
						window[settings.titleData] = String( getCookie(settings.cookieKey + '_title') );
					}
				}
				else
				{
					setCookie(settings.cookieKey, '');
					setCookie(settings.cookieKey + '_title', '');
					window[settings.storageVar] = String( getCookie(settings.cookieKey) );
					if( settings.dataAttribute != '' )
					{
						window[settings.titleData] = String( getCookie(settings.cookieKey + '_title') );
					}
				}
			}
			else
			{
				$(settings.childInput).each(function() {
					if( $(this).is(':checked') )
					{
						_checkedInputs.push( $(this).val() );
					}
				});
				window[settings.storageVar] = String( arrayUnique(_checkedInputs) );
				if( settings.dataAttribute != '' )
				{
					window[settings.titleData] = String( getCookie(settings.cookieKey + '_title') );
				}
			}
			if( settings.callBackFunction != '' )
			{
				eval(settings.callBackFunction);
			}
		}
		
		function withCookie( action, value, title)
		{
			if( typeof value !== 'undefined' && value != '' )
			{
				var _checkedList = getCookie(settings.cookieKey);
				if( _checkedList == null ) _checkedList = '';
				else _checkedList = String(_checkedList);
				var _checkedArray = new Array();
				_checkedArray = _checkedList.split(',');
				value = value.split(',');
				
				if( settings.dataAttribute != '' )
				{
					var _checked_title_List = getCookie(settings.cookieKey + '_title');
					if( _checked_title_List == null ) _checked_title_List = '';
					else _checked_title_List = String(_checked_title_List);
					var _checked_title_Array = new Array();
					_checked_title_Array = _checked_title_List.split(',');
					title = title.split(',');
				}
				
				if( action == 'add' )
				{
					for( var i = 0; i < value.length; i++ )
					{
						_checkedArray.push(value[i]);
						if( settings.dataAttribute != '' )
						{
							_checked_title_Array.push(title[i]);
						}
					}
				}
				else if( action == 'remove' )
				{
					for( var i = 0; i < value.length; i++ )
					{
						var removeIndex = _checkedArray.indexOf(value[i]);
						_checkedArray.splice(removeIndex, 1);
						
						if( settings.dataAttribute != '' )
						{
							var remove_title_Index = _checked_title_Array.indexOf(title[i]);
							_checked_title_Array.splice(remove_title_Index, 1);
						}
					}
				}
				_checkedArray = arrayUnique(_checkedArray);
				_checkedList = _checkedArray.join(',');
				setCookie(settings.cookieKey, _checkedList);
				
				if( settings.dataAttribute != '' )
				{
					_checked_title_Array = arrayUnique(_checked_title_Array);
					_checked_title_List = _checked_title_Array.join(',');
					setCookie(settings.cookieKey + '_title', _checked_title_List);
				}
			}
		}
		//setCookie(settings.cookieKey, '1,2,3,4,5');
		//alert(getCookie(settings.cookieKey));
		
		function setCookie(name, value, expiredays)
		{
			if (expiredays) {
				var exdate = new Date();
				exdate.setDate(exdate.getDate() + expiredays);
				var expires = exdate.toGMTString();
			}
			var is_url = /^([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/;
			var domainName = document.domain;
			domainName = domainName.replace(/www\./g, '');
			domainName = is_url.test(domainName) ? '.' + domainName : '';
			document.cookie = name + "=" + escape(value) + ((expiredays) ? "; expires=" + expires : "") + ((domainName) ? "; domain=" + domainName : "");
		}
		
		function getCookie(name) {
			var cookie = " " + document.cookie;
			var search = " " + name + "=";
			var setStr = null;
			var offset = 0;
			var end = 0;
			if (cookie.length > 0) {
				offset = cookie.indexOf(search);
				if (offset != -1) {
					offset += search.length;
					end = cookie.indexOf(";", offset)
					if (end == -1) {
						end = cookie.length;
					}
					setStr = unescape(cookie.substring(offset, end));
				}
			}
			return setStr;
		}
		
		function arrayUnique(arrayObj)
		{
			var u = {}, a = [];
			for(var i = 0, l = arrayObj.length; i < l; ++i)
			{
				if(u.hasOwnProperty(arrayObj[i]))
				{
					continue;
				}
				a.push(arrayObj[i]);
				u[arrayObj[i]] = 1;
			}
			return a;
		}
				
		return updateCheckedList('start');
    }
}(jQuery));