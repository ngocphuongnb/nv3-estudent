<!-- BEGIN: main -->

<form class="vnp-search" action="{FORM_ACTION}" method="get">    
    <input name="{NV_NAME_VARIABLE}" type="hidden" value="{MODULE_NAME}" />
    <input name="{NV_OP_VARIABLE}" type="hidden" value="{OP}" />
    <input name="search" type="hidden" value="1" />
    <table class="tab1">
    	<tbody class="second">
        	<tr>
            	<td><strong>{LANG.term}</strong></td>
                <td>{TERM_SLB}</td>
            	<td><strong>{LANG.search}</strong></td>
                <td><input name="q" value="{SEARCH.q}" type="text" /></td>
                <td><strong>{LANG.number}</strong></td>
                <td><strong>{SHOW_NUMBER}</strong></td>
         	</tr>
            <tr>
            	<td style="width:75px"><strong>{LANG.number_student}</strong></td>
                <td><input style="width: 100px" name="number_student" value="{SEARCH.number_student}" type="text" /></td>
            	<td><strong>{LANG.class_mark}</strong></td>
                <td id="enter-mark"><strong>{ENTER_MARK}</strong></td>
                <td><strong>{LANG.status} {LANG.class}</strong></td>
                <td><strong>{STATUS}</strong></td>
            </tr>
            <tr>
                <td align="center" colspan="6"><input type="submit" value="{LANG.search}" class="btn btn-primary" /></td>
           	</tr>
        </tbody>
    </table>
</form>

<table class="tab1">
    <thead>
        <td>{LANG.class_name}</td>
        <td>{LANG.subject}</td>
        <td>{LANG.faculty}</td>
        <td style="width:75px">{LANG.number_student}</td>
        <td style="width:75px">{LANG.class_room}</td>
        <td style="width:100px">{LANG.feature}</td>
    </thead>
    <!-- BEGIN: row -->
    <tbody class="{ROW.class}">
        <tr>
            <td style="width:150px"><strong>{ROW.class_name}</strong></td>
            <td style="width:150px"><strong>{ROW.subject}</strong></td>
            <td style="width:150px"><strong>{ROW.faculty}</strong></td>
            <td><strong>{ROW.number_student}</strong></td>
            <td><strong>{ROW.class_room}</strong></td>
            <td>
                <span class="edit_icon"><a href="{ROW.url_enter_mark}">{LANG.class_mark}</a></span>
            </td>
        </tr>
    </tbody>
    <!-- END: row -->
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