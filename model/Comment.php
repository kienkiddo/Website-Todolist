<?php

use function PHPSTORM_META\map;

class Comment{
  private $id;

  private $name;

  private $topicId;

  private $done;

  public function getId(){
    return $this->id;
  }

  public function getName(){
    return $this->name;
  }

  public function setName($name){
    $this->name = $name;
    return $this;
  }

  public function getTopicId(){
    return $this->topicId;
  }

  public function getDone(){
    return $this->done;
  }

  public function setDone($done){
    $this->done = $done;
    return $this;
  }

  protected function fill(array $data){
    $this->id = $data['id'];
    $this->name = $data['name'];
    $this->topicId = $data['topicId'];
    $this->done = $data['done'];
  }

  public function updateName($db){
    return $db->execute("UPDATE comment SET name='$this->name' WHERE id='$this->id' LIMIT 1");
  }

  public function updateDone($db){
    return $db->execute("UPDATE comment SET done='$this->done' WHERE id='$this->id' LIMIT 1");
  }

  public function delete($db){
    return $db->execute("DELETE FROM comment WHERE id='$this->id'");
  }

  public static function withId($db, $id){
    $data = $db->getArr("SELECT * FROM comment WHERE id='$id' LIMIT 1");
    if (!empty($data)){
      $cmt = new Comment();
      $cmt->fill($data);
      return $cmt;
    }
    return null;
  }

  public static function all($db, $topicId){
    $data = $db->getArrs("SELECT * FROM comment WHERE topicId='$topicId' ORDER BY id DESC");
    if (!empty($data)){
      $cmts = array();
      foreach ($data as $item){
        $cmt = new Comment();
        $cmt->fill($item);
        $cmts[] = $cmt;
      }
      return $cmts;
    }
    return array();
  }

}