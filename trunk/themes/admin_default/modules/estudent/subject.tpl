<!-- BEGIN: main -->
<span class="vnp-add"><a class="add_icon" href="{ADD_LINK}">{LANG.add_subject}</a></span>
<form action="{FORM_ACTION}" method="get">    
    <input name="{NV_NAME_VARIABLE}" type="hidden" value="{MODULE_NAME}" />
    <input name="{NV_OP_VARIABLE}" type="hidden" value="{OP}" />
    <input name="search" type="hidden" value="1" />
    <table class="tab1">
    	<tbody class="second">
        	<tr>
            	<td><strong>{LANG.search}</strong></td>
                <td><input name="q" value="{SEARCH.q}" type="text" /></td>
                <td>{LANG.faculty}</td>
                <td>{SEARCH_FACULTY}</td>
                <td>{LANG.number}</td>
                <td>{SHOW_NUMBER}</td>
                <td><input type="submit" value="{LANG.search}" class="btn btn-primary" /></td>
           	</tr>
        </tbody>
    </table>
</form>

<table class="tab1">
    <thead>
        <td>{LANG.subject_name}</td>
        <td style="width:100px">{LANG.subject_code}</td>
        <td style="width:100px">{LANG.faculty}</td>
        <td style="width:100px">{LANG.clpart}</td>
        <td style="width:150px">{LANG.status}</td>
        <td style="width:100px">{LANG.feature}</td>
        
    </thead>
    <!-- BEGIN: row -->
    <tbody class="{ROW.class}">
        <tr>
            <td style="width:150px"><strong>{ROW.subject_name}</strong></td>
            <td>{ROW.subject_code}</td>
            <td>{ROW.faculty}</td>
            <td>{ROW.clpart}</td>
            <td>
                <select id="change_status_{ROW.subject_id}" onchange="nv_chang_status('{ROW.subject_id}', 'subject');">
                    <!-- BEGIN: status -->
                    <option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
                    <!-- END: status -->
                </select>
            </td>
            <td>
                <span class="edit_icon"><a href="{ROW.url_edit}">{GLANG.edit}</a></span> &nbsp;&nbsp; <span class="delete_icon"><a href="javascript:void(0);" onclick="nv_module_del({ROW.subject_id}, 'subject');">{GLANG.delete}</a></span>
            </td>
            
        </tr>
    </tbody>
    <!-- END: row -->
    <tfoot>
        <tr>
            <td colspan="6" align="center"></td>
        </tr>
    </tfoot>
</table>
<center>
    <div class="pagination" style="margin-top: -15px">
        <ul class="vnp-pagination">
        {PAGE_GEN}
        </ul>
    </div>
</center>
<!-- END: main -->