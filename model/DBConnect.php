<?php
class DBConnect
{
  private $host = "localhost"; 
  private $user = "root";
  private $pass = "";
  private $dbname = "todolist";
  private $conn = NULL;
  private $result = NULL;

  public function connect()
  {
    error_reporting(0);
    $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->dbname);
    if (!$this->conn) {
      echo "Kết nối thất bại";
      exit();
    }
    mysqli_set_charset($this->conn, "UTF8");
    return $this->conn;
  }

  public function prepare($sql)
  {
    $this->result = $this->conn->prepare($sql);
    return $this->result;
  }

  public function execute($sql)
  {
    $this->result = mysqli_query($this->conn, $sql);
    return $this->result;
  }

  public function getArr($sql)
  {
    $this->execute($sql);
    if (mysqli_num_rows($this->result) == 0) {
      $data = 0;
    } else {
      $data = mysqli_fetch_array($this->result);
    }
    return $data;
  }

  public function getArrs($sql)
  {
    $this->execute($sql);
    if (mysqli_num_rows($this->result) == 0) {
      $data = 0;
    } else {
      while ($datas = mysqli_fetch_array($this->result)) {
        $data[] = $datas;
      }
    }
    return $data;
  }

}
