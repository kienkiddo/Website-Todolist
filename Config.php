<?php
if (!isset($_SESSION)) {
  session_start();
}

$home = "http://localhost/todolist/";
date_default_timezone_set("Asia/Ho_Chi_Minh");
$time = time();
$arrColor = array("primary", "success", "danger", "warning", "dark");
include_once "Util.php";
include_once "model/DBConnect.php";
include_once "model/Member.php";

$db = new DBConnect();
$db->connect();

$login = false;

if (isset($_SESSION['id'])) {
  $login = true;
  $id = $_SESSION['id'];
  $member = Member::withId($db, $id);
}
