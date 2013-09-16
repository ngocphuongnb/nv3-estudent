<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post">
	<input name="save" type="hidden" value="1" />
	<table class="tab1">
		<tbody>
			<tr>
				<td style="width:150px"><strong>{LANG.subject_name}</strong></td>
				<td><input class="txt-half" type="text" value="{SUBJECT.subject_name}" name="subject[subject_name]" id="subject_name" maxlength="255" /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.subject_code}</strong></td>
				<td><input class="txt-half" type="text" value="{SUBJECT.subject_code}" name="subject[subject_code]" id="subject_code" maxlength="255" /></td>
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
				<td><strong>{LANG.alias}</strong></td>
				<td><input class="txt-half" type="text" value="{SUBJECT.subject_alias}" name="subject[subject_alias]" id="subject_alias" maxlength="255" /><img src="{NV_BASE_SITEURL}images/refresh.png" width="16" class="refresh" onclick="get_alias('{ID}');" alt="Get alias..." height="16" /></td>
			</tr>
		</tbody>
        <tbody>
			<tr>
				<td style="width:150px"><strong>{LANG.clpart}</strong></td>
				<td><input class="txt-half" type="number" value="{SUBJECT.clpart}" name="subject[clpart]" id="subject_clpart" maxlength="255" /></td>
			</tr>
		</tbody>
        <tbody>
			<tr>
				<td style="width:150px"><strong>{LANG.practice_require}</strong></td>
				<td><input class="txt-half" type="checkbox" {SUBJECT.practice_require} value="1" name="subject[practice_require]" id="subject_practice_require" maxlength="255" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td colspan="2">
				<p>
					<strong>{LANG.subject_desc}</strong>
				</p> {SUBJECT.subject_desc} </td>
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