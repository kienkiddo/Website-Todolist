<?php

include_once "Topic.php";
include_once "Role.php";
include_once "Member.php";

class Box
{
  private $id;

  private $off;

  private $userId;

  private $timeupdate;

  private $name;

  private $join;

  private $members = null;

  private $topics = null;

  private $roles = null;

  public function getId()
  {
    return $this->id;
  }

  public function setId($id){
    $this->id = $id;
    return $this;
  }

  public function getOff()
  {
    return $this->off;
  }

  public function setOff($off){
    $this->off = $off;
    return $this;
  }


  public function getUserId()
  {
    return $this->userId;
  }

  public function setUserId($userId){
    $this->userId = $userId;
    return $this;
  }

  public function getTimeUpdate()
  {
    return $this->timeupdate;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setName($name){
    $this->name = $name;
    return $this;
  }

  public function setJoin($join){
    $this->join = $join;
    return $this;
  }

  public function getJoin(){
    return $this->join;
  }

  public function getTopics($db)
  {
    if ($this->topics == null) {
      $this->topics = Topic::all($db, $this->id);
    }
    return $this->topics;
  }

  public function setTopics($topics){
    $this->topics = $topics;
    return $this;
  }

  public function getRoles($db){
    if ($this->roles == null){
      $this->roles = Role::withBoxId($db, $this->id);
    }
    return $this->roles;
  }

  public function getRole($db, $userId){
    $this->getRoles($db);
    foreach ($this->roles as $r){
      if ($r->getUserId() == $userId){
        return $r;
      }
    }
    return null;
  }

  public function isMember($db, $userId){
    if ($this->userId == $userId){
      return true;
    }
    $this->getRoles($db);
    foreach ($this->roles as $role){
      if ($role->getUserId() == $userId){
        return true;
      }
    }
    return false;
  }

  public function getMembers($db){
    if ($this->members == null){
      $this->members = array();
      $this->members[] = Member::withId($db, $this->userId);
      $ms = Member::withBox($db, $this->id);
      foreach ($ms as $m){
        $this->members[] = $m;
      }
    }
    return $this->members;
  }

  protected function fill(array $data)
  {
    $this->id = $data['id'];
    $this->off = $data['off'];
    $this->userId = $data['userId'];
    $this->timeupdate = $data['timeupdate'];
    $this->name = $data['name'];
    $this->join = $data['isJoin'];
  }

  public function updateJoin($db){
    return $db->execute("UPDATE box SET isJoin='$this->join' WHERE id='$this->id' LIMIT 1");
  }

  public function delete($db){
    return $db->execute("DELETE FROM box WHERE id='$this->id' LIMIT 1");
  }

  public function addTopic($db, $name)
  {
    $db->execute("INSERT INTO topic(id, boxId, name, timecreat, timeupdate) VALUES(null, '$this->id', '$name', '" . time() . "', '" . time() . "')");
    $id = $db->getArr("SELECT LAST_INSERT_ID() as id")['id'];
    return Topic::withId($db, $id);
  }

  public static function addBoxOnl($db, $userId, $name)
  {
    $db->execute("INSERT INTO box(id, userId, off, name, timeupdate) VALUES(NULL, '$userId', '0', '$name', '" . time() . "')");
    $id = $db->getArr("SELECT LAST_INSERT_ID() as id")['id'];
    return Box::withId($db, $id);
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

  public static function getBoxForMe($db, $userId){
    $box = new Box();
    $box->setId(-1);
    $box->setName("Quan trọng");
    $box->setUserId($userId);
    $box->setTopics(Topic::allForMe($db, $userId));
    return $box;
  }

  public static function withId($db, $id)
  {
    $data = $db->getArr("SELECT * FROM box WHERE id='$id' LIMIT 1");
    if (!empty($data)) {
      $box = new Box();
      $box->fill($data);
      return $box;
    }
    return null;
  }

  public static function all($db, $userId){
    $boxs = array();
    $data = $db->getArrs("SELECT * FROM box WHERE userId='$userId' and off=0 ORDER BY id ASC");
    if (!empty($data)){
      foreach ($data as $item){
        $box = new Box();
        $box->fill($item);
        $boxs[] = $box;
      }
    }
    $boxs += Box::all2($db, $userId);
    return $boxs;
  }

  public static function all2($db, $userId){
    $data = $db->getArrs("SELECT box.* FROM `box` INNER JOIN role ON role.boxId=box.id WHERE role.userId='$userId'");
    if (!empty($data)){
      $boxs = array();
      foreach ($data as $item){
        $box = new Box();
        $box->fill($item);
        $boxs[] = $box;
      }
      return $boxs;
    }
    return array();
  }


}
