<!-- BEGIN: main -->
<span class="vnp-add"><a class="vnp-button" href="{ADD_LINK}">{LANG.add_term}</a></span>
<form action="{FORM_ACTION}" method="post">
	<input name="save" type="hidden" value="1" />
	<table class="tab1">
    	<thead>
        	<td>{LANG.term_name}</td>
            <td style="width:150px">{LANG.status}</td>
			<td style="width:100px">{LANG.feature}</td>
        </thead>
        <!-- BEGIN: row -->
		<tbody class="{ROW.class}">
			<tr>
				<td style="width:150px"><strong>{ROW.term_name}</strong></td>
                <td>
                	<select id="change_status_{ROW.term_id}" onchange="nv_chang_status('{ROW.term_id}', 'term');">
                        <!-- BEGIN: status -->
                        <option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
                        <!-- END: status -->
                    </select>
           		</td>
                <td>
                	<span class="edit_icon"><a href="{ROW.url_edit}">{GLANG.edit}</a></span> &nbsp;&nbsp; <span class="delete_icon"><a href="javascript:void(0);" onclick="nv_module_del({ROW.term_id}, 'term');">{GLANG.delete}</a></span>
                </td>
			</tr>
		</tbody>
        <!-- END: row -->
		<tfoot>
			<tr>
				<td colspan="3" align="center"><input type="submit" value="{LANG.save}"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->