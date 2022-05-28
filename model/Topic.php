<?php

include_once "Comment.php";

class Topic
{
  private $id;

  private $boxId;

  private $name;

  private $description;

  private $star;

  private $done;

  private $colorId;

  private $tag;

  private $userId;

  private $timecreat;

  private $timeupdate;

  private $cmts = null;

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
    return $this;
  }

  public function getBoxId()
  {
    return $this->boxId;
  }

  public function setBoxId($boxId)
  {
    $this->boxId = $boxId;
    return $this;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setName($name)
  {
    $this->name = $name;
    return $this;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function setDescription($description)
  {
    $this->description = $description;
    return $this;
  }

  public function getStar()
  {
    return $this->star;
  }

  public function setStar($star)
  {
    $this->star = $star;
    return $this;
  }

  public function getDone()
  {
    return $this->done;
  }

  public function setDone($done)
  {
    $this->done = $done;
    return $this;
  }

  public function getColorId(){
    return $this->colorId;
  }

  public function setColorId($colorId){
    $this->colorId = $colorId;
  }

  public function getTag(){
    return $this->tag;
  }

  public function setTag($tag){
    $this->tag = $tag;
  }

  public function getUserId(){
    return $this->userId;
  }

  public function setUserId($userId){
    $this->userId = $userId;
    return $this;
  }

  public function getTimeCreat()
  {
    return $this->timecreat;
  }

  public function getTimeUpdate()
  {
    return $this->timeupdate;
  }


  public function getTimeAgo(){
    $time = time() - $this->timeupdate;
    if ($time > 86400){
      $day = (int) ($time / 86400);
      return $day . " ngày";
    }
    if ($time > 3600){
      $hour = (int) ($time / 3600);
      return $hour . " giờ";
    }
    if ($time > 60){
      $minute = (int) ($time / 60);
      return $minute . " phút";
    }
    return $time . " giây";
  }

  public function getCmts($db)
  {
    if ($this->cmts == null) {
      $this->cmts = Comment::all($db, $this->id);
    }
    return $this->cmts;
  }

  public function getTextCmt()
  {
    $count = 0;
    foreach ($this->cmts as $cmt) {
      if ($cmt->getDone()) {
        $count += 1;
      }
    }
    return $count . " trên " . count($this->cmts);
  }

  protected function fill(array $data)
  {
    $this->id = $data['id'];
    $this->boxId = $data['boxId'];
    $this->name = $data['name'];
    $this->description = $data['description'];
    $this->star = $data['star'];
    $this->done = $data['done'];
    $this->colorId = $data['colorId'];
    $this->tag = $data['tag'];
    $this->userId = $data['userId'];
    $this->timecreat = $data['timecreat'];
    $this->timeupdate = $data['timeupdate'];
  }

  public function updateTimeUpdate($db)
  {
    $db->execute("UPDATE topic SET timeupdate='" . time() . "' WHERE id='$this->id' LIMIT 1");
  }

  public function updateDone($db)
  {
    return $db->execute("UPDATE topic SET done='$this->done', timeupdate='" . time() . "' WHERE id='$this->id' LIMIT 1");
  }

  public function updateStar($db)
  {
    return $db->execute("UPDATE topic SET star='$this->star', timeupdate='" . time() . "' WHERE id='$this->id' LIMIT 1");
  }

  public function updateName($db)
  {
    return $db->execute("UPDATE topic SET name='$this->name', timeupdate='" . time() . "' WHERE id='$this->id' LIMIT 1");
  }

  public function updateDescription($db)
  {
    return $db->execute("UPDATE topic SET description='$this->description', timeupdate='" . time() . "' WHERE id='$this->id' LIMIT 1");
  }

  public function updateTag($db)
  {
    return $db->execute("UPDATE topic SET colorId='$this->colorId', tag='$this->tag', timeupdate='" . time() . "' WHERE id='$this->id' LIMIT 1");
  }

  public function updateUserId($db)
  {
    return $db->execute("UPDATE topic SET userId='$this->userId', timeupdate='" . time() . "' WHERE id='$this->id' LIMIT 1");
  }

  public function delete($db)
  {
    return $db->execute("DELETE FROM topic WHERE id='$this->id' LIMIT 1");
  }

  public function addComment($db, $name)
  {
    $db->execute("INSERT INTO comment(id, topicId, name) VALUES(NULL, '$this->id', '$name')");
    $id = $db->getArr("SELECT LAST_INSERT_ID() as id")['id'];
    return Comment::withId($db, $id);
  }

  public function clearComment($db)
  {
    return $db->execute("DELETE FROM comment WHERE topicId='$this->id'");
  }

  public static function withId($db, $id)
  {
    $data = $db->getArr("SELECT * FROM topic WHERE id='$id' LIMIT 1");
    if (!empty($data)) {
      $t = new Topic();
      $t->fill($data);
      return $t;
    }
    return null;
  }

  public static function all($db, $boxId)
  {
    $data = $db->getArrs("SELECT * FROM topic WHERE boxId='$boxId' ORDER BY id DESC");
    if (!empty($data)) {
      $topics = array();
      foreach ($data as $item) {
        $topic = new Topic();
        $topic->fill($item);
        $topics[] = $topic;
      }
      return $topics;
    }
    return array();
  }
}
