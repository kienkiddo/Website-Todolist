<?php

include "Config.php";

$page = "dang-nhap";
if (isset($_GET['page'])){
  $page = $_GET['page'];
}

if ($login) {
  $arr = array("user" => "user", "index" => "index", "chi-tiet" => "view", "join" => "join");
} else {
  $arr = array("dang-nhap" => "login", "dang-ky" => "register", "join" => "login");
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