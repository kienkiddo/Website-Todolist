<?php

include_once "model/Box.php";
include_once "model/Role.php";

$id = sql_refesh($_POST['id']);

$box = Box::withId($db, $id);

if ($box != null && $box->getJoin() && !$box->isMember($db, $member->getId())){
  $role = Role::insert($db, $member->getId(), $box->getId());
  set_notify("success", "Tham gia thành công !!!");
  echo "OK";
} else {
  echo "NOT AVAILABE JOIN";
}
