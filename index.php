<?php

include "Config.php";

$page = "index";
if (isset($_GET['page'])){
  $page = $_GET['page'];
}



include "view/header.php";
include "view/" . $page . ".php";
include "view/footer.php";