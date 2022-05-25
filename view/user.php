<?php
?>

<div class="container" style="margin-top: 50px">
  <div class="row">
    <div class="col-lg-8 offset-lg-2">
      <h3 class="text-center">THÔNG TIN CÁ NHÂN</h3>
      <hr style="width: 15%; height: 2px" class="color-m">
      <div class="mt-5">
        <table class="table">
          <tr>
            <td>Tham gia:</td>
            <th><?= $member->getTimeCreat() ?></th>
          </tr>
          <tr>
            <td>Họ và tên:</td>
            <th><?= $member->getName() ?></th>
          </tr>
          <tr>
            <td>Người dùng:</td>
            <th><?= $member->getUser() ?></th>
          </tr>
          <tr>
            <td>Tác vụ:</td>
            <th><button class="btn btn-sm color-m" href="javascript:void(0)" data-toggle="modal" data-target="#modal">Chỉnh sửa thông tin</button></th>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header  color-m">
        <h4 class="modal-title">THAY ĐỔI THÔNG TIN</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <h4 class="text-center mb-4">THÔNG TIN</h4>
        <form id="form">
          <div class="input-group form-group">
            <span class="input-group-prepend">
              <div class="input-group-text bg-light border-right-0"><i class="fa fa-user"></i>
              </div>
            </span>
            <input class="form-control py-2 border-left-0 border" id="name" name="name" value="<?= $member->getName() ?>">
          </div>
          <div class="input-group form-group">
            <span class="input-group-prepend">
              <div class="input-group-text bg-light border-right-0"><i class="fas fa-key"></i>
              </div>
            </span>
            <input class="form-control py-2 border-left-0 border" id="pass2" name="pass2" placeholder="Mật khẩu mới" type="password">
          </div>
          <div class="input-group form-group">
            <span class="input-group-prepend">
              <div class="input-group-text bg-light border-right-0"><i class="fas fa-lock"></i>
              </div>
            </span>
            <input class="form-control py-2 border-left-0 border" id="pass" name="pass" placeholder="Xác nhận mật khẩu hiện tại" type="password">
          </div>
          <div class="form-group mb-1">
            <small><span class="text-danger">(*)</span> Nhập mật khẩu mới nếu muốn đổi mật khẩu</small>
          </div>
          <div class="form-group">
            <button class="btn color-m btn-sm font-weight-bold form-control" type="button" id="btn"><span class="fas fa-key"></span> THỰC HIỆN</button>
          </div>
        </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>


<script>
  $("#btn").on("click", function() {
    $.post({
      url: "controller.php?router=user",
      data: $("#form").serialize(),
      success: function(res) {
        var json = JSON.parse(res);
        console.log(json);
        if (json.type == "success"){
          window.location.reload();
        } else {
          $.notify($(json.selector), json.message, {
          className: json.type,
          position: "top-center"
        });
        }
      }
    })
  });
</script>