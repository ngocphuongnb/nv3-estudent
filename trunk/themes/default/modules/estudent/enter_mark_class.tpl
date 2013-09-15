<!-- BEGIN: main -->
<table class="tab1">
    <thead>
        <td>{LANG.class_name}</td>
        <td>{LANG.subject}</td>
    </thead>
    <!-- BEGIN: loop -->
    <tbody class="{ROW.class}">
        <tr>
            <td style="width:150px"><strong>{ROW.student_name}</strong></td>
            <td style="width:150px"><strong>{ROW.subject}</strong></td>
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