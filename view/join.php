<?php

include_once "model/Box.php";

$id = sql_refesh($_GET['id']);

$box = Box::withId($db, $id);
if ($box == null) {
  echo "<div class='text-center mt-3'>Oh !!! Không tìm thấy danh sách.</div>";
  return;
}
if (!$box->getJoin()) {
  echo "<div class='text-center mt-3'>Oh !!! Danh sách hiện đang chế độ riêng tư.</div>";
  return;
}

if ($box->isMember($db, $member->getId())) {
  header("Location: $home" . "index.php?boxId=" . $id);
  return;
}

?>

<div class="container" style="margin-top: 50px">
  <div class="row">
    <div class="col text-center">
      <h5><?= $box->getName() ?></h5>
      <div>
        Người tạo: <span class="font-weight-bold"><?= Member::withId($db, $box->getUserId())->getName() ?></span>
      </div>
      <div>
        Thành viên: <span class="font-weight-bold"><?= count($box->getRoles($db)) + 1 ?> người</span>
      </div>
      <hr style="width: 25%">
      <div>Bạn có chắn chắn tham gia vào Danh sách này?</div>
      <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#modal-confirm"><span class="fas fa-key"></span> THAM GIA NGAY</button>
    </div>
  </div>
</div>


<div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg bg-info text-white">
        <h5 class="modal-title" id="">XÁC NHẬN</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6>BẠN CÓ CHẮC CHẮN MUỐN THAM GIA?</h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">ĐÓNG</button>
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal" onclick="fSubmit()">THỰC HIỆN</button>
      </div>
    </div>
  </div>
</div>

<script>
 function fSubmit(){
    $.post({
      url: "controller.php?router=join",
      data: "id=" + <?= $box->getId() ?>,
      success: function(res){
        console.log(res);
        if (res == "OK"){
          window.location.reload();
        }
      }
    });
  };
</script>