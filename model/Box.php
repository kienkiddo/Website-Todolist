<?php

include_once "Topic.php";

class Box
{
  private $id;

  private $off;

  private $userId;

  private $timeupdate;

  private $name;

  private $topics = null;

  public function getId()
  {
    return $this->id;
  }

  public function getOff()
  {
    return $this->off;
  }

  public function getUserId()
  {
    return $this->userId;
  }

  public function getTimeUpdate()
  {
    return $this->timeupdate;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getTopics($db){
    if ($this->topics == null){
      $this->topics = Topic::all($db, $this->id);
    }
    return $this->topics;
  }

  protected function fill(array $data)
  {
    $this->id = $data['id'];
    $this->off = $data['off'];
    $this->userId = $data['userId'];
    $this->timeupdate = $data['timeupdate'];
    $this->name = $data['name'];
  }

  public function addTopic($db, $name){
    $db->execute("INSERT INTO topic(id, boxId, name, timecreat) VALUES(null, '$this->id', '$name', '" . time() . "')");
    $id = $db->getArr("SELECT LAST_INSERT_ID() as id")['id'];
    return Topic::withId($db, $id);
  }

  public static function getBoxOff($db, $userId)
  {
    $data = $db->getArr("SELECT * FROM box WHERE userId='$userId' and off=1 LIMIT 1");
    if (!empty($data)) {
      $box = new Box();
      $box->fill($data);
      return $box;
    }
    $db->execute("INSERT INTO box(id, userId, off, name, timeupdate) VALUES(NULL, '$userId', '1', '', '" . time() . "')");
    return Box::getBoxOff($db, $userId);
  }

  public static function withId($db, $id){
    $data = $db->getArr("SELECT * FROM box WHERE id='$id' LIMIT 1");
    if (!empty($data)){
      $box = new Box();
      $box->fill($data);
      return $box;
    }
    return null;
  }
}
