<!-- BEGIN: main -->
<table class="tab1">
	<caption>
    	Điểm danh cho lớp {CLASS.class_name} - {CLASS.year} - Ngày:
    	<input type="text" id="roll-call-dat" value="{TODAY}" />
   	</caption>
    <thead>
    	<tr>
            <td>{LANG.student_name}</td>
            <td>Số buổi nghỉ</td>
            <td>Điểm danh hôm nay</td>
            <td>{LANG.status}</td>
       	</tr>
    </thead>
    <!-- BEGIN: loop -->
    <tbody class="{ROW.class}">
        <tr>
            <td style="width:150px"><strong>{ROW.student_name}</strong></td>
            <td style="width:150px"><strong>{ROW.miss_class}</strong> <div class="tool-tip">{ROW.miss_class_desc}</div></td>
            <td style="width:150px"><strong>{ROW.vnp_roll_call}</strong></td>
            <td style="width:150px"><strong>{ROW.test_status}</strong></td>
        </tr>
    </tbody>
    <!-- END: loop -->
    <tfoot>
        <tr>
            <td colspan="9" align="center"></td>
        </tr>
    </tfoot>
</table>
<center>
    <div class="pagination">
        <ul class="vnp-pagination">
        {PAGE_GEN}
        </ul>
    </div>
</center>
<style type="text/css">
#enter-mark select {
	max-width: 200px;
	width: 155px;
	color: #F00;
	font-weight: bold;
	line-height: 28px;
	height: 28px;
}
</style>
<script type="text/javascript">
$(document).ready(function(e) {
    $( '#roll-call-dat' ).datepicker({
		dateFormat: "dd/mm/yy",
		showOn : "both",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});
});
</script>
<!-- END: main -->