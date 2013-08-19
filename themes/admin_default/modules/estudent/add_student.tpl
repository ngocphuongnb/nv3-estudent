<!-- BEGIN: select_year -->
<form action="" method="get">
	<input name="action" type="hidden" value="add" />    
    <input name="{NV_NAME_VARIABLE}" type="hidden" value="{MODULE_NAME}" />
    <input name="{NV_OP_VARIABLE}" type="hidden" value="{OP}" />
    <table class="tab1">
        <thead>
            <tr>
                <td colspan="2"><strong>{LANG.add_student_form}</strong></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>{LANG.select_year}</strong></td>
                <td>{YEAR_SLB}</td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td><strong>{LANG.select_faculty}</strong></td>
                <td>{FACULTY_SLB}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" align="center"><input type="submit" value="{LANG.add_student}"/></td>
            </tr>
        </tfoot>
    </table>
</form>
<!-- END: select_year -->

<!-- BEGIN: main -->
<table class="tab1">
    <tbody>
        <tr>
            <td>{ADD_STUDENT_HEADER}</td>
        </tr>
    </tbody>
</table>
<form action="{FORM_ACTION}" method="post">
	<input name="save" type="hidden" value="1" />
	<table class="tab1">
    	<tbody>
        	<tr>
                <td><strong>{LANG.select_member}:</strong><sup class="required">∗</sup></td>
                <td>
                	<input name="student[userid]" id="student_userid" type="text" value="{STUDENT.userid}" style="width:300px" maxlength="20" />
                    
                    <input type="button" value="Chọn" onclick="nv_open_browse_file( '{NV_BASE_ADMINURL}index.php?' + nv_name_variable + '=users&amp;' + nv_fc_variable + '=getuserid&amp;area=student_userid&amp;filtersql={FILTERSQL}', 'NVImg', '850', '600', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' )" />
             	</td>
            </tr>
        </tbody>
		<tbody class="second">
			<tr>
				<td style="width:150px"><strong>{LANG.student_name}</strong><sup class="required">∗</sup></td>
				<td><input class="txt-half" type="text" value="{STUDENT.student_name}" name="student[student_name]" id="student_name" maxlength="255" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.student_code}</strong><sup class="required">∗</sup></td>
				<td><input class="txt-half" type="text" value="{STUDENT.student_code}" name="student[student_code]" id="student_alias" maxlength="255" /><img src="{NV_BASE_SITEURL}images/refresh.png" width="16" class="refresh" onclick="get_alias('{ID}');" alt="Get alias..." height="16" /></td>
			</tr>
		</tbody>
        <tbody class="second">
			<tr>
				<td style="width:150px"><strong>{LANG.level}</strong><sup class="required">∗</sup></td>
				<td>{LEVEL_SLB}</td>
			</tr>
		</tbody>
        <tbody>
			<tr>
				<td style="width:150px"><strong>{LANG.faculty}</strong><sup class="required">∗</sup></td>
				<td>{FACULTY_SLB}</td>
			</tr>
		</tbody>
        <tbody>
			<tr>
				<td style="width:150px"><strong>{LANG.select_year}</strong><sup class="required">∗</sup></td>
				<td>{YEAR_SLB}</td>
			</tr>
		</tbody>
        <tbody class="second">
			<tr>
				<td style="width:150px"><strong>{LANG.base_class}</strong><sup class="required">∗</sup></td>
				<td>
                	<input type="hidden" value="{STUDENT.base_class_id}" name="student[base_class_id]" id="student_base_class_id" maxlength="255" />
                    <input type="button" onclick="getIDs('base_class', 'student_base_class_id', '{STUDENT.base_class_id}'); return false" value="{LANG.choose_base_class}" />
                    
                    <ul id="student_base_class_id_title" class="selected-title">
                    {STUDENT.base_class}
                    </ul>
			</td></tr>
		</tbody>
        <tbody>
			<tr>
				<td style="width:150px"><strong>{LANG.course}</strong><sup class="required">∗</sup></td>
				<td>{COURSE_SLB}</td>
         	</tr>
		</tbody>
   	</table>
    <table class="tab1">
    
    	<tbody class="second">
			<tr>
				<td style="width:150px"><strong>{LANG.birthday}</strong></td>
				<td><input type="text" value="{STUDENT.birthday}" name="student[birthday]" id="student_birthday" maxlength="255" /></td>
			</tr>
		</tbody>
        <tbody>
			<tr>
				<td style="width:150px"><strong>{LANG.hometown}</strong></td>
				<td><textarea class="txt-half" name="student[hometown]" id="student_hometown" maxlength="255">{STUDENT.hometown}</textarea></td>
			</tr>
		</tbody>
        <tbody class="second">
			<tr>
				<td style="width:150px"><strong>{LANG.address}</strong></td>
				<td><textarea class="txt-half" name="student[address]" id="student_address" maxlength="255">{STUDENT.address}</textarea></td>
			</tr>
		</tbody>
        
        <tbody>
			<tr>
				<td style="width:150px"><strong>{LANG.email}</strong><sup class="required">∗</sup></td>
				<td><input class="txt-half" type="text" value="{STUDENT.email}" name="student[email]" id="student_email" maxlength="255" /></td>
			</tr>
		</tbody>
        
        
        <tbody class="second">
			<tr>
				<td style="width:150px"><strong>{LANG.phone}</strong></td>
				<td><input class="txt-half" type="text" value="{STUDENT.phone}" name="student[phone]" id="student_phone" maxlength="255" /></td>
			</tr>
		</tbody>
        
        
		<tbody>
			<tr>
				<td colspan="2">
				<p>
					<strong>{LANG.student_desc}</strong>
				</p> {STUDENT.student_desc} </td>
			</tr>
		</tbody>
        
		<tfoot>
			<tr>
				<td colspan="2" align="center"><input type="submit" value="{LANG.save}"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$(document).ready(function() {
		$("#student_birthday").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_siteroot + "images/calendar.gif",
			buttonImageOnly : true,
			yearRange: "1980:2100"
		});
})
</script>
<!-- END: main -->