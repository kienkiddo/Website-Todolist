<?php

include_once "model/Box.php";

$name = mb_strtoupper(sql_refesh($_POST['name']));
$box = Box::addBoxOnl($db, $member->getId(), $name);

if ($box != null){
  echo "OK|" . $box->getId();
} else {
  echo "Insert not OK";
}