<?php

class Member {

  private $id;

  private $user;

  private $pass;

  private $name;

  private $timecreat;

  public function getId(){
    return $this->id;
  }

  public function setId($id){
    $this->id = $id;
    return $this;
  }

  public function getUser(){
    return $this->user;
  }

  public function setUser($user){
    $this->user = $user;
    return $this;
  }

  public function getPass(){
    return $this->pass;
  }

  public function setPass($pass){
    $this->pass = $pass;
    return $this;
  }

  public function getName(){
    return $this->name;
  }

  public function setName($name){
    $this->name = $name;
    return $this;
  }

  public function getCharIcon(){
    $res = "";
    $arr = explode(" ", $this->name);
    if (count($arr) == 1){
      $res = $this->name[0] . $this->name[0];
    } else {
      $res = $arr[0][0] . $arr[count($arr) - 1][0];
    }
    return mb_strtoupper($res);
  }

  public function getTimeCreat(){
    return $this->timecreat;
  }

  protected function fill(array $data){
    $this->id = $data['id'];
    $this->user = $data['user'];
    $this->pass = $data['pass'];
    $this->name = $data['name'];
    $this->timecreat = $data['timecreat'];
  }

  public function login($db){
    $data = $db->getArr("SELECT * FROM member WHERE user='$this->user' and pass='$this->pass' LIMIT 1");
    if (!empty($data)){
      $this->fill($data);
      return true;
    }
    return false;
  }

  public function isExist($db){
    $data = $db->getArr("SELECT id FROM member WHERE user='$this->user' LIMIT 1");
    if (!empty($data)){
      return true;
    }
    return false;
  }

  public function register($db){
    return $db->execute("INSERT INTO member(id, user, pass, name) VALUES(NULL, '$this->user', '$this->pass', '$this->name')");
  }

  public function updateInfo($db){
    return $db->execute("UPDATE member SET name='$this->name', pass='$this->pass' WHERE id='$this->id'");
  }

  public static function withId($db, $id){
    $data = $db->getArr("SELECT * FROM member WHERE id='$id' LIMIT 1");
    if (!empty($data)){
      $m = new Member();
      $m->fill($data);
      return $m;
    }
    return null;
  }

  public static function withBox($db, $boxId){
    $members = array();
    $data = $db->getArrs("SELECT member.* FROM role INNER JOIN member ON member.id = role.userId WHERE role.boxId='$boxId'");
    foreach ($data as $item){
      $m = new Member();
      $m->fill($item);
      $members[] = $m;
    }
    return $members;
  }

}