<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post">
	<input name="save" type="hidden" value="1" />
	<table class="tab1">
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