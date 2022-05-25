<?php

$user = mb_strtolower($_POST['user']);
$pass = mb_strtolower($_POST['pass']);

$user = sql_refesh($user);
$pass = sql_refesh($pass);


if (strlen($user) < 6 || strlen($user) > 12) {
  json_notify_with("#user", "warn", "Tên người dùng phải từ 6 đến 12 kí tự.");
} else if (strlen($pass) < 5 || strlen($pass) > 12) {
  json_notify_with("#pass", "warn", "Mật khẩu phải từ 6 đến 12 kí tự.");
} else {
  $m = new Member();
  $m->setUser($user)->setPass(md5($pass));
  if ($m->login($db)) {
    save_session($m);
    save_cookie($m);
    set_notify("success", "Đăng nhập thành công !!!");
    json_notify("success", "LOGIN OK !!!");
  } else {
    json_notify_with("#btn", "error", "Tên người dùng hoặc mật khẩu không chính xác.");
  }
}
