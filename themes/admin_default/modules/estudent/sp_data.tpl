<!-- BEGIN: main -->
<style type="text/css">
.vnp-item {
	line-height: 20px;
	font-weight: bold;
	margin-left: 20px;
}
</style>
<button id="add-teacher">Cài dữ liệu giáo viên</button><br />
<ol class="add-data" id="teacher">
</ol>

<button id="add-base_class">Cài dữ liệu lớp chính quy</button><br />
<ol class="add-data" id="base_class">
</ol>

<button id="add-student">Cài dữ liệu sinh viên</button><br />
<ol class="add-data" id="student">
</ol>

<script type="text/javascript">
var i = 1;
function send(mod, num)
{
	if( i <= num )
	{
		$.ajax({
			type: 'GET',
			url: '{AJAX_LINK}',
			dataType: 'json',
			data: {
				mod: mod,
				index: i
			},
			success: function(data)
			{
				$.each(data, function()
				{
					$('#' + mod).append('<li class="vnp-item">' + this.fullname + ' - ' + this.faculty + '</li>');
				});
				i++;
				send(mod, num)
			}
		})
	}
}
$(document).ready(function(e) {
    $('#add-teacher').click(function(e) {
        i = 1;
		send('teacher', 100);
    });
	$('#add-base_class').click(function(e) {
        i = 1;
		send('base_class', 7);
    });
	$('#add-student').click(function(e) {
        i = 1;
		send('student', 1000);
    });
});
</script>
<!-- END: main -->