<!-- BEGIN: main -->
    <div class="vnp-info">
        <div class="vnp-label label label-info">
        Xin chào {INFO.name}<br />
        Bạn đang đăng nhập với tư cách {INFO.type}<br />
        {INFO.info}
        </div>
        <!-- BEGIN: required_mark -->
        <div class="vnp-label label label-warning">
        Bạn có {INFO.mark.required_mark} lớp cần nhập điểm <a href="{INFO.mark.required_mark_link}" title="Xem chi tiết lớp cần nhập điểm">Xem chi tiết</a>
        </div>
        <!-- END: required_mark -->
    </div>
	<!-- BEGIN: menu -->
    <ul class="vnp-menu">
    	<!-- BEGIN: loop -->
        <li class="menu-item {MENU.active}">
        	<a href="{MENU.link}" title="{MENU.title}">{MENU.title}</a>
       	</li>
        <!-- END: loop -->
    </ul>
    <!-- END: menu -->
    <div class="vnp-content">
    {CONTENT}
    </div>
<!-- END: main -->