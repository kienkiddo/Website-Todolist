<?php


include_once "model/Box.php";
include_once "model/Topic.php";
include_once "model/Comment.php";

$action = $_GET['action'];


switch ($action) {
  case "UpdateStar":
    $id = sql_refesh($_POST['id']);
    $topic = Topic::withId($db, $id);
    if ($topic != null) {
      $star = !$topic->getStar();
      $topic->setStar($star);
      $topic->updateStar($db);
      echo $star == 1 ? 1 : 0;
    }
    break;
  case "UpdateName":
    $id = sql_refesh($_POST['id']);
    $name = sql_refesh($_POST['name']);
    $type = sql_refesh($_POST['type']);
    if ($type == 1) {
      $topic = Topic::withId($db, $id);
      if ($topic != null) {
        $topic->setName($name);
        $topic->updateName($db);
        echo "OK";
      }
    } else if ($type > 1) {
      $cmt = Comment::withId($db, $id);
      if ($cmt != null){
        $cmt->setName($name);
        $cmt->updateName($db);
        echo "OK";
      }
    }
    break;
  case "UpdateDone":
    $id = sql_refesh($_POST['id']);
    $type = sql_refesh($_POST['type']);
    $checked = sql_refesh($_POST['checked']);
    if ($type == 1) {
      $topic = Topic::withId($db, $id);
      if ($topic != null) {
        $topic->setDone(($checked == "true" ? 1 : 0));
        $topic->updateDone($db);
        echo "OK";
      }
    } else if ($type > 1) {
      $cmt = Comment::withId($db, $id);
      if ($cmt != null){
        $cmt->setDone(($checked == "true" ? 1 : 0));
        $cmt->updateDone($db);
        echo "OK";
      }
    }
    break;
  case "Delete":
    $id = sql_refesh($_POST['id']);
    $type = sql_refesh($_POST['type']);
    if ($type == 1) {
      $topic = Topic::withId($db, $id);
      if ($topic != null) {
        $topic->clearComment($db);
        $topic->delete($db);
        echo "OK";
      }
    } else {
      $cmt = Comment::withId($db, $id);
      if ($cmt != null){
        $cmt->delete($db);
        echo "OK";
      }
    }
    break;
  case "updateDescription":
    $id = sql_refesh($_POST['id']);
    $description = sql_refesh($_POST['text']);
    $topic = Topic::withId($db, $id);
    if ($topic != null) {
      $topic->setDescription($description);
      $topic->updateDescription($db);
      echo "OK";
    }
    break;
  case "AddStep":
    $id = sql_refesh($_POST['id']);
    $name = sql_refesh($_POST['name']);
    $topic = Topic::withId($db, $id);
    if ($topic != null) {
      $cmt = $topic->addComment($db, $name);
      echo "OK|" . $cmt->getId();
    }
    break;
}
