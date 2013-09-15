<!-- BEGIN: main -->
<span class="vnp-add"><a class="vnp-button" href="{ADD_LINK}">{LANG.add_class}</a></span>
<form class="vnp-search" action="{FORM_ACTION}" method="get">    
    <input name="{NV_NAME_VARIABLE}" type="hidden" value="{MODULE_NAME}" />
    <input name="{NV_OP_VARIABLE}" type="hidden" value="{OP}" />
    <input name="search" type="hidden" value="1" />
    <table class="tab1">
    	<tbody class="second">
        	<tr>
            	<td><strong>{LANG.search}</strong></td>
                <td><input name="q" value="{SEARCH.q}" type="text" /></td>
                <td>{LANG.term}</td>
                <td>{SEARCH_TERM}</td>
                <td>{LANG.number}</td>
                <td>{SHOW_NUMBER}</td>
         	</tr>
            <tr>
            	<td style="width:75px">{LANG.number_student}</td>
                <td><input name="number_student" value="{SEARCH.number_student}" type="text" /></td>
            	<td>{LANG.class_mark}</td>
                <td>{ENTER_MARK}</td>
                <td>{LANG.status}</td>
                <td>{STATUS}</td>
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
        <td>{LANG.term}</td>
        <td>{LANG.subject}</td>
        <td style="width:75px">{LANG.number_student}</td>
        <td style="width:75px">{LANG.registered_student}</td>
        <td style="width:75px">{LANG.class_room}</td>
        <td style="width:150px">{LANG.class_mark}</td>
        <td style="width:150px">{LANG.status}</td>
        <td style="width:100px">{LANG.feature}</td>
    </thead>
    <!-- BEGIN: row -->
    <tbody class="{ROW.class}">
        <tr>
            <td style="width:150px"><strong>{ROW.class_name}</strong></td>
            <td style="width:150px"><strong>{ROW.term}</strong></td>
            <td style="width:150px"><strong>{ROW.subject}</strong></td>
            <td><input type="text" id="change_class_number_student_{ROW.class_id}" onchange="vnp_update_class({ROW.class_id}, 'number_student', this.value);" style="width:70px" value="{ROW.number_student}"  /></td>
            <td><strong>{ROW.registered_student}</strong></td>
            <td><strong>{ROW.class_room}</strong></td>
            <td>{ROW.class_mark}</td>
            <td>{ROW.class_status}</td>
            <td>
                <span class="edit_icon"><a href="{ROW.url_edit}">{GLANG.edit}</a></span> &nbsp;&nbsp; <span class="delete_icon"><a href="javascript:void(0);" onclick="nv_module_del({ROW.class_id}, 'class');">{GLANG.delete}</a></span>
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
<!-- END: main -->