<?php

$name = mb_strtoupper($_POST['name']);
$pass = mb_strtolower($_POST['pass']);
$pass2 = mb_strtolower($_POST['pass2']);

$name = sql_refesh($name);
$pass = sql_refesh($pass);
$pass2 = sql_refesh($pass2);

if (strlen($pass2) == 0){
  $pass2 = $pass;
}

if (strlen($name) < 6 || strlen($name) > 32) {
  json_notify_with("#name", "warn", "Họ tên phải từ 6 đến 32 kí tự.");
} else if (strlen($pass) < 5 || strlen($pass) > 12) {
  json_notify_with("#pass", "warn", "Mật khẩu phải từ 6 đến 12 kí tự.");
} else if ((strlen($pass2) < 5 || strlen($pass2) > 12)) {
  json_notify_with("#pass2", "warn", "Mật khẩu phải từ 6 đến 12 kí tự.");
} else {
 if ($member->getPass() != md5($pass)){
  json_notify_with("#pass", "warn", "Mật khẩu hiện tại không chính xác.");
 } else {
  $member->setPass(md5($pass2))->setName($name);
  if ($member->updateInfo($db)){
    set_notify("success", "Cập nhật thông tin thành công !!!");
    json_notify("success", "EDIT INFO OK !!!");
  } else {
    json_notify_with("#btn", "error", "Đã xảy ra lỗi, vui lòng liên hệ Admin để biết thêm.");
  }
 }
}
