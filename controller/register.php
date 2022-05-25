<?php

$name = mb_strtoupper($_POST['name']);
$user = mb_strtolower($_POST['user']);
$pass = mb_strtolower($_POST['pass']);

$name = sql_refesh($name);
$user = sql_refesh($user);
$pass = sql_refesh($pass);

if (strlen($name) < 6 || strlen($name) > 32) {
  json_notify_with("#name", "warn", "Họ tên phải từ 6 đến 32 kí tự.");
} else if (strlen($user) < 6 || strlen($user) > 12) {
  json_notify_with("#user", "warn", "Tên người dùng phải từ 6 đến 12 kí tự.");
} else if (strlen($pass) < 5 || strlen($pass) > 12) {
  json_notify_with("#pass", "warn", "Mật khẩu phải từ 6 đến 12 kí tự.");
} else {
  $m = new Member();
  $m->setUser($user)->setPass(md5($pass))->setName($name);
  if ($m->isExist($db)) {
    json_notify_with("#user", "error", "Tên người dùng đã có người đăng ký.");
  } else {
    if ($m->register($db)) {
      if ($m->login($db)){
        save_session($m);
        save_cookie($m);
        set_notify("success", "Đăng ký thành công !!!");
        json_notify("success", "REGISTER OK !!!");
      }
    } else {
      json_notify_with("#btn", "error", "Đã xảy ra lỗi, vui lòng liên hệ Admin để biết thêm.");
    }
  } 
}
