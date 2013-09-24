<!-- BEGIN: main -->
<table class="tab1">
    <thead>
        <td>{LANG.class_name}</td>
        <td>{LANG.subject}</td>
        <td style="width:75px">{LANG.class_room}</td>
        <td style="width:100px">Điểm quá trình</td>
         <td style="width:100px">Điểm cuối kì</td>
    </thead>
    <!-- BEGIN: loop -->
    <tbody class="{ROW.class}">
        <tr>
            <td style="width:150px"><strong>{ROW.class_name}</strong></td>
            <td style="width:150px"><strong>{ROW.subject_name}</strong></td>
            <td style="width:150px"><strong>{ROW.class_room}</strong></td>
            <td><strong>{ROW.time_mark}</strong></td>
            <td><strong>{ROW.end_mark}</strong></td>
        </tr>
    </tbody>
    <!-- END: loop -->
    <tfoot>
        <tr>
            <td colspan="5" align="center"></td>
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