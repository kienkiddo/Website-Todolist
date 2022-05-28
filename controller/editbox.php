<?php

include "model/Box.php";

$action = $_GET['action'];

switch ($action) {
  case "Delete":
    $id = sql_refesh($_POST['id']);
    $box = Box::withId($db, $id);
    if ($box != null && $box->isMember($db, $member->getId())) {
      if ($box->delete($db)) {
        set_notify("success", "Xóa danh sách thành công");
        echo "OK";
      } else {
        echo "ERROR";
      }
    } else {
      echo "ID NOT FOUND";
    }
    break;
  case "UpdateJoin":
    $id = sql_refesh($_POST['id']);
    $checked = sql_refesh($_POST['checked']);
    $box = Box::withId($db, $id);
    if ($box != null) {
      if ($box->isMember($db, $member->getId()) && !$box->getOff()) {
        $box->setJoin(($checked == "true" ? true : false));
        $box->updateJoin($db);
        echo "OK";
      } else {
        echo "NOT ROLE OR OFFLINE";
      }
    } else {
      echo "ID NOT FOUND";
    }
    break;
  case "DeleteMember":
    $id = sql_refesh($_POST['id']);
    $userId = sql_refesh($_POST['userId']);
    $box = Box::withId($db, $id);
    if ($box != null) {
      if ($box->getUserId() == $member->getId()) {
        $role = $box->getRole($db, $userId);
        if ($role != null) {
          if ($role->delete($db)) {
            echo "OK";
          } else {
            echo "TRY AGAIN";
          }
        } else {
          echo "USER NOT EXIST OF BOX";
        }
      } else {
        echo "NOT ADMIN";
      }
    } else {
      echo "ID NOT FOUND";
    }
    break;
}
