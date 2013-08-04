<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post">
	<input name="save" type="hidden" value="1" />
	<table class="tab1">
		<tbody class="second">
			<tr>
				<td style="width:150px"><strong>{LANG.term_name}</strong></td>
				<td><input class="txt-half" type="text" value="{TERM.term_name}" name="term[term_name]" id="term_name" maxlength="255" /></td>
			</tr>
		</tbody>
        <tbody>
			<tr>
            	<td><strong>{LANG.term_weeks}</strong></td>
				<td colspan="2"><input class="txt-half" type="text" value="{TERM.weeks}" name="term[weeks]" id="term_weeks" maxlength="255" />({LANG.term_weeks_note})</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
            	<td><strong>{LANG.select_year}</strong></td>
				<td colspan="2">{TERM_SLB}</td>
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