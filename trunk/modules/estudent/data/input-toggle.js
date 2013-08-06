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
			storageVar			: 'checkedInputs',
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
			if( $(this).is(':checked') ) var obj = 'add_' + $(this).val();
			else var obj = 'remove_' + $(this).val();
			
			updateCheckedList(obj);
        });
		
		function updateCheckedList(obj)
		{
			var _checkedInputs = new Array();

			if( settings.enableCookie && typeof obj !== 'undefined' )
			{
				var _checkedList = getCookie(settings.cookieKey);
				if( obj == 'add' || obj == 'remove' )
				{
					$(settings.childInput).each(function() {
						_checkedInputs.push( $(this).val() );
					});
					withCookie( obj, String( arrayUnique(_checkedInputs)) );
				}
				else
				{
					obj = obj.split('_');
					if( obj[0] == 'add' || obj[0] == 'remove' )
					{
						if( typeof obj[1] !== 'undefined' )
						{
							withCookie( obj[0], String( obj[1]) );
						}
					}
				}
				window[settings.storageVar] = String( getCookie(settings.cookieKey) );
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
			}
			if( settings.callBackFunction != '' )
			{
				eval(settings.callBackFunction);
			}
		}
		
		function withCookie( action, value)
		{
			if( typeof value !== 'undefined' && value != '' )
			{
				var _checkedList = getCookie(settings.cookieKey);
				if( _checkedList == null ) _checkedList = '';
				else _checkedList = String(_checkedList);
				var _checkedArray = new Array();
				_checkedArray = _checkedList.split(',');
				value = value.split(',');
				if( action == 'add' )
				{
					for( var i = 0; i < value.length; i++ )
					{
						_checkedArray.push(value[i]);
					}
				}
				else if( action == 'remove' )
				{
					for( var i = 0; i < value.length; i++ )
					{
						var removeIndex = _checkedArray.indexOf(value[i]);
						_checkedArray.splice(removeIndex, 1);
					}
				}
				_checkedArray = arrayUnique(_checkedArray);
				_checkedList = _checkedArray.join(',');
				setCookie(settings.cookieKey, _checkedList);
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
				
		return updateCheckedList();
    }
}(jQuery));