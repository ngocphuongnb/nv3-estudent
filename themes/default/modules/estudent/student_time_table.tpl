<!-- BEGIN: main -->
<!-- BEGIN: select_term -->
<strong>{LANG.term}</strong>
{TERM_SLB}
<!-- END: select_term -->
<!-- BEGIN: add -->
<table class="tab1">
    <caption>Thời khóa biểu lớp {BASE_CLASS.base_class_name}</caption>
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
            <td><strong>Thông tin</strong></td>
            <td style="background: #FFF">
                {BASE_CLASS.main_time}
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
            <td colspan="2" align="center"></td>
        </tr>
    </tfoot>
</table>
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