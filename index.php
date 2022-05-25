<?php

include "Config.php";

$page = "index";
if (isset($_GET['page'])){
  $page = $_GET['page'];
}

if ($login) {
  $arr = array("user" => "user");
} else {
  $arr = array("dang-nhap" => "login", "dang-ky" => "register");
}

$file = "index";
foreach ($arr as $key => $value) {
  if ($page == $key) {
    $file = $value;
  }
}

include "view/header.php";
include "view/" . $file . ".php";
include "view/footer.php";