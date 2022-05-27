<div class="container" style="margin-top: 50px">
  <div class="row">
    <div class="col-lg-6 offset-lg-3">
      <h2 class="text-center mb-4">ĐĂNG NHẬP</h2>
      <form id="form">
        <div class="input-group form-group">
          <span class="input-group-prepend">
            <div class="input-group-text bg-light border-right-0"><i class="far fa-envelope"></i>
            </div>
          </span>
          <input class="form-control py-2 border-left-0 border" id="user" name="user"
            placeholder="Tên người dùng">
        </div>
        <div class="input-group form-group">
          <span class="input-group-prepend">
            <div class="input-group-text bg-light border-right-0"><i class="fas fa-lock"></i>
            </div>
          </span>
          <input class="form-control py-2 border-left-0 border" id="pass" name="pass"
            placeholder="Mật khẩu" type="password">
        </div>
        <div class="form-group">
          <button class="btn btn-info btn-sm font-weight-bold form-control" type="button" id="btn"><span class="fas fa-key"></span> ĐĂNG NHẬP</button>
        </div>
      </form>
      <p class="text-center">Bạn chưa có tài khoản? <a href="index.php?page=dang-ky">Đăng ký</a></p>
    </div>
  </div>
</div>


<script>
  $("#btn").on("click", function() {
    $.post({
      url: "controller.php?router=login",
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