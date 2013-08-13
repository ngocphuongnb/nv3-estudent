<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post">
	<input name="save" type="hidden" value="1" />
	<table class="tab1">
		<tbody>
			<tr>
				<td style="width:150px"><strong>{LANG.base_class_name}</strong></td>
				<td><input class="txt-half" type="text" value="{BASE_CLASS.base_class_name}" name="base_class[base_class_name]" id="base_class_name" maxlength="255" /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.alias}</strong></td>
				<td><input class="txt-half" type="text" value="{BASE_CLASS.base_class_alias}" name="base_class[base_class_alias]" id="base_class_alias" maxlength="255" /><img src="{NV_BASE_SITEURL}images/refresh.png" width="16" class="refresh" onclick="get_alias('{ID}');" alt="Get alias..." height="16" /></td>
			</tr>
		</tbody>
        <tbody>
			<tr>
				<td style="width:150px"><strong>{LANG.faculty}</strong></td>
				<td>{FACULTY_SLB}</td>
			</tr>
		</tbody>
        <tbody class="second">
			<tr>
				<td style="width:150px"><strong>{LANG.level}</strong></td>
				<td>{LEVEL_SLB}</td>
			</tr>
		</tbody>
        <tbody class="second">
			<tr>
				<td style="width:150px"><strong>{LANG.course}</strong></td>
				<td>{COURSE_SLB}</td>
			</tr>
		</tbody>
        <tbody>
			<tr>
				<td style="width:150px"><strong>{LANG.teacher}</strong></td>
				<td>
                	<input type="hidden" value="{BASE_CLASS.teacher_id}" name="base_class[teacher_id]" id="base_class_teacher_id" maxlength="255" />
                    <input type="button" onclick="getIDs('teacher', 'base_class_teacher_id', '{BASE_CLASS.teacher_id}'); return false" value="{LANG.choose_teacher}" />
                    
                    <ul id="base_class_teacher_id_title" class="selected-title">
                    {BASE_CLASS.teacher_title}
                    </ul>
			</td></tr>
		</tbody>
         <tbody class="second">
			<tr>
				<td style="width:150px"><strong>{LANG.number_student}</strong></td>
				<td><input class="txt-half" type="number" value="{BASE_CLASS.number_student}" name="base_class[number_student]" id="base_class_number_student" maxlength="255" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td colspan="2">
				<p>
					<strong>{LANG.base_class_desc}</strong>
				</p> {BASE_CLASS.base_class_desc} </td>
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