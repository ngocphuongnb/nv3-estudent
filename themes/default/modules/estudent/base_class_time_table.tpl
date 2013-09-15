<!-- BEGIN: main -->
<!-- BEGIN: select_term -->
<strong>{LANG.term}</strong>
{TERM_SLB}
<!-- END: select_term -->
<!-- BEGIN: add -->
<form action="{FORM_ACTION}" method="post">
	<input name="save" type="hidden" value="1" />
    <input type="hidden" id="term-class-reged" value="{BASE_CLASS.vnp_class}" name="term_class_reged" />
	<table class="tab1">
    	<caption>{LANG.set_time_table} <a href="{BASE_CLASS.url_edit}" title="{BASE_CLASS.base_class_name}">{BASE_CLASS.base_class_name}</a></caption>
        <tbody>
			<tr>
				<td><strong>{LANG.term}</strong></td>
				<td>
                	<strong>{BASE_CLASS.term}</strong>
                </td>
			</tr>
		</tbody>
        <tbody>
			<tr>
				<td><a href="#" class="vnp-button" onclick="nv_opener( '{GET_CLASS}', 'NVImg', '950', '600', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' ); return false">{LANG.choose_class}</a></td>
                <td style="background: #FFF">
                	<h3 class="vnp-button list-header">{LANG.registered_class}</h3>
                	<ol class="vnp-registered" id="class_ctner">
                    	<!-- BEGIN: reged_class -->
                        {CLASS}
                        <!-- END: reged_class -->
                    </ol>
                    <h3 class="vnp-button list-header">{LANG.registered_subject}</h3>
                    <ol class="vnp-registered" id="subject_ctner">
                    	<!-- BEGIN: reged_subject -->
                        {SUBJECT}
                        <!-- END: reged_subject -->
                    </ol>
              	</td>
			</tr>
		</tbody>
        <tbody>
			<tr>
				<td><strong>{LANG.class_time}</strong></td>
				<td>
                	{BASE_CLASS.class_time}
                </td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" align="center"><input type="submit" value="{LANG.save}"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$(document).ready(function(e) {
    $('.class-time input:checked').parent().addClass('class-time-active');
	$('.class-time input').click(function(e) {
        $('.class-time input:checked' ).parent().addClass('class-time-active');
		if( $( this ).is(':not(:checked)') )
		{
			$(this).parent().removeClass('class-time-active');
		}
    });
});
</script>
<!-- END: add -->
<!-- END: main -->