<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post">
	<input name="save" type="hidden" value="1" />
	<table class="tab1">
    	<tbody class="second">
        	<tr>
                <td><strong>{LANG.select_member}:</strong><sup class="required">∗</sup></td>
                <td>
                	<input name="teacher[userid]" id="teacher_userid" type="text" value="{TEACHER.userid}" style="width:300px" maxlength="20" />
                    
                    <input type="button" value="Chọn" onclick="nv_open_browse_file( '{NV_BASE_ADMINURL}index.php?' + nv_name_variable + '=users&amp;' + nv_fc_variable + '=getuserid&amp;area=teacher_userid&amp;filtersql={FILTERSQL}', 'NVImg', '850', '600', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' )" />
             	</td>
            </tr>
        </tbody>
		<tbody>
			<tr>
				<td style="width:150px"><strong>{LANG.teacher_name}</strong></td>
				<td><input class="txt-half" type="text" value="{TEACHER.teacher_name}" name="teacher[teacher_name]" id="teacher_name" maxlength="255" /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.alias}</strong></td>
				<td><input class="txt-half" type="text" value="{TEACHER.teacher_alias}" name="teacher[teacher_alias]" id="teacher_alias" maxlength="255" /><img src="{NV_BASE_SITEURL}images/refresh.png" width="16" class="refresh" onclick="get_alias('{ID}');" alt="Get alias..." height="16" /></td>
			</tr>
		</tbody>
        <tbody>
			<tr>
				<td style="width:150px"><strong>{LANG.faculty}</strong></td>
				<td>{FACULTY_SLB}</td>
			</tr>
		</tbody>
        <tbody>
			<tr>
				<td style="width:150px"><strong>{LANG.type}</strong></td>
				<td>{TEACHER_TYPE}</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td colspan="2">
				<p>
					<strong>{LANG.teacher_desc}</strong>
				</p> {TEACHER.teacher_desc} </td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" align="center"><input type="submit" value="{LANG.save}"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->