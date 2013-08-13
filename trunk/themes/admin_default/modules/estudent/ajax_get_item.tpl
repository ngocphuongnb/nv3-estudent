<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Language" content="vi" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{LANG.pagetitle1}</title>
		<link rel="StyleSheet" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.admin_theme}/css/admin.css" type="text/css" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.module_theme}/css/{MODULE_FILE}.css" rel="stylesheet" />
		<script type="text/javascript">
			//<![CDATA[
			var nv_siteroot = "{NV_BASE_SITEURL}";
			var vnp_cookie_prefix = "{VNP_COOKIE_PREFIX}";
			//]]>
		</script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/{NV_LANG_INTERFACE}.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/global.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/admin.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
        <script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/admin.js"></script>
        <script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/data/input-toggle.js"></script>
	</head>
	<body>

        <form method="get">    
            <input name="{NV_NAME_VARIABLE}" type="hidden" value="{MODULE_NAME}" />
            <input name="{NV_OP_VARIABLE}" type="hidden" value="{OP}" />
            <input name="mode" type="hidden" value="{SEARCH.table}" />
            <input name="container" type="hidden" value="{SEARCH.container}" />
            <input name="search" type="hidden" value="1" />
            <table class="tab1">
                <tbody class="second">
                    <tr>
                        <td><strong>{LANG.search}</strong></td>
                        <td><input name="q" value="{SEARCH.q}" type="text" /></td>
                      	<!-- BEGIN: faculty -->
                        <td>{LANG.faculty}</td>
                        <td>{SEARCH_FACULTY}</td>
                        <!-- END: faculty -->
                        <td>{LANG.number}</td>
                        <td>{SHOW_NUMBER}</td>
                        <td><input type="submit" value="{LANG.search}" class="btn btn-primary" /></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <button id="vnp-add" onclick="insertToPost('{SEARCH.container}', checkedInputs)">{LANG.add_selected}</button>
        <table class="tab1">
            <thead>
            	</thead><td style="width: 30px"><input id="toggle-all" value="1" type="checkbox" {CHECK_ALL} /></td>
                <td></td>
            </thead>
            <!-- BEGIN: row -->
            <tbody class="{ROW.class}">
                <tr>
                	<td><input type="checkbox" {ROW.check} data-title="{ROW.id} - {ROW.name}" class="item-toggle" value="{ROW.id}" /></td>
                    <td><strong>{ROW.name}</strong></td>   
                </tr>
            </tbody>
            <!-- END: row -->
            <tfoot>
                <tr>
                    <td colspan="4" align="center"></td>
                </tr>
            </tfoot>
        </table>    
        <center>      
            <div class="pagination" style="margin-top: -15px">
                <!-- BEGIN: page -->
                <ul class="vnp-pagination">
                {PAGE_GEN}
                </ul>
                <!-- END: page -->
            </div>       
        </center>
        <script type="text/javascript">
		$(document).ready(function() {
			var checkedInputs = '';
			var titleData = '';
			$('#toggle-all').InputToggle({
				childInput: '.item-toggle',
				dataAttribute: 'data-title',
				storageVar: 'checkedInputs',
				titleData:	'titleData',
				featureAction: [
					{container: '#vnp-add', callback: "insertToPost('{SEARCH.container}', checkedInputs, titleData)" }
				],
				callBackFunction: 'setCookieSelected(checkedInputs, "{SEARCH.table}")',
				enableCookie: true
			});
			
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
		})
		</script>
	</body>
</html>
<!-- END: main -->