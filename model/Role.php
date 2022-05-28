<?php 

class Role{
  private $id;

  private $userId;

  private $boxId;

  public function getId(){
    return $this->id;
  }

  public function getUserId(){
    return $this->userId;
  }

  public function getBoxId(){
    return $this->boxId;
  }

  protected function fill(array $data){
    $this->id = $data['id'];
    $this->userId = $data['userId'];
    $this->boxId = $data['boxId'];
  }

  public function delete($db){
    return $db->execute("DELETE FROM role WHERE id='$this->id' LIMIT 1");
  }

  public static function insert($db, $userId, $boxId){
    if ($db->execute("INSERT INTO role(id, userId, boxId) VALUES(NULL, '$userId', '$boxId')")){
      $id = $db->getArr("SELECT LAST_INSERT_ID() as id")['id'];
      return Role::withId($db, $id);
    }
    return null;
  }

  public static function withId($db, $id){
    $data = $db->getArr("SELECT * FROM role WHERE id='$id' LIMIT 1");
    if (!empty($data)){
      $role = new Role();
      $role->fill($data);
      return $role;
    }
    return null;
  }

  public static function withUserId($db, $userId){
    $data = $db->getArrs("SELECT * FROM role WHERE userId='$userId'");
    if (!empty($data)){
      $roles = array();
      foreach ($data as $item){
        $role = new Role();
        $role->fill($item);
        $roles[] = $role;
      }
      return $roles;
    }
    return array();
  }

  public static function withBoxId($db, $boxId){
    $data = $db->getArrs("SELECT * FROM role WHERE boxId='$boxId'");
    if (!empty($data)){
      $roles = array();
      foreach ($data as $item){
        $role = new Role();
        $role->fill($item);
        $roles[] = $role;
      }
      return $roles;
    }
    return array();
  }
}