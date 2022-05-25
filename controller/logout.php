<?php

member_logout();
set_notify("success", "Đăng xuất thành công !!!");
header("Location: $home");
