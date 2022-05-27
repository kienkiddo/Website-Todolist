<?php

include_once "model/Box.php";
include_once "model/Topic.php";

$id = sql_refesh($_POST['id']);
$name = sql_refesh($_POST['name']);

$box = Box::withId($db, $id);
if ($box != null && $box->getUserId() == $member->getId()){
  $topic = $box->addTopic($db, $name);
  echo "OK|" . $topic->getId();
} else {
  echo "Infomation Invaild !!!";
}
