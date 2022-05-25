<?php

$router = "router";
if (isset($_GET['router'])){
  $router = $_GET['router'];
}

include "Config.php";
include "controller/" . $router . ".php";