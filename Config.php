<?php
if (!isset($_SESSION)) {
  session_start();
}

$home = "http://localhost/todolist/";
date_default_timezone_set("Asia/Ho_Chi_Minh");
$time = time();

include "Util.php";
include "model/DBConnect.php";
include "model/Member.php";

$db = new DBConnect();
$db->connect();

$login = false;

if (isset($_SESSION['id'])) {
  $login = true;
  $id = $_SESSION['id'];
  $member = Member::withId($db, $id);
}
