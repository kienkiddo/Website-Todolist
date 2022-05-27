<?php

include "Config.php";

if ($login) {
  $arr = array("user", "logout", "addtopic", "addbox");
} else {
  $arr = array("login", "register");
}


$router = "index";
if (isset($_GET['router'])){
  $router = $_GET['router'];
}

foreach ($arr as $value) {
  if ($router == $value) {
    $router = $value;
  }
}

if ($router == "index"){
  return;
}

include "controller/" . $router . ".php";