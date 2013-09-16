<!-- BEGIN: main -->
<table class="tab1">
	<caption>
    	Thông tin điểm danh
   	</caption>
    <thead>
    	<tr>
            <td>{LANG.class_name}</td>
            <td>Môn học</td>
            <td>Số buổi nghỉ</td>
            <td>{LANG.status}</td>
       	</tr>
    </thead>
    <!-- BEGIN: loop -->
    <tbody class="{ROW.class}">
        <tr>
            <td style="width:150px"><strong>{ROW.class_name}</strong></td>
             <td style="width:150px"><strong>{ROW.subject_name}</strong></td>
            <td style="width:150px"><strong>{ROW.miss_class}</strong> <div class="tool-tip">{ROW.miss_class_desc}</div></td>
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
<!-- END: main -->