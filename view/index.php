<?php
$boxId = isset($_GET['boxId']) ? $_GET['boxId'] : 0;
$titles = array("0" => "LỊCH TRÌNH", "-1" => "QUAN TRỌNG", "-2" => "GIAO CHO TÔI");

include_once "model/Box.php";
include_once "model/Topic.php";

if ($boxId == 0) {
  $box = Box::getBoxOff($db, $member->getId());
} else if ($boxId > 0) {
  $box = Box::withId($db, $boxId);
} else if ($boxId < 0) {
  if ($boxId == -1){
    $box = Box::getBoxForMe($db, $member->getId());
  }
}

if ($box == null || !$box->isMember($db, $member->getId())) {
  echo "<div class='text-center mt-4'>Ohh !!! Bạn không có quyền truy cập vào trang này.</div>";
  return;
}


?>

<div class="container" style="margin-top: 50px">
  <div class="row">
    <div class="col-lg-4 text-center" style="padding-right: 30px;">
      <div class="bg bg-light pb-3" id="navBox">
        <div class="btn-group-vertical" style="width: 100%">
          <a href="index.php?boxId=0"><i class="fas fa-sun"></i>&ensp;LỊCH TRÌNH</a>
          <a href="index.php?boxId=-1"><i class="fas fa-meteor"></i>&ensp;GIAO CHO TÔI</a>
        </div>
        <hr style="width: 95%" class="mb-0">
        <div class="btn-group-vertical" style="width: 100%" id="listBox">
          <?php
          $boxs = Box::all($db, $member->getId());
          foreach ($boxs as $b) :
            $titles += [$b->getId() => $b->getName()];
          ?>
            <a href="index.php?boxId=<?= $b->getId() ?>"><i class="fas fa-bars"></i>&ensp;<?= $b->getName() ?></a>
          <?php endforeach; ?>
        </div>
        <div class="bg bg-light pt-3" id="divAddBox2" onclick="$(this).hide(); $('#divAddBox').show()">
          <label class="text-primary"><span class="fas fa-plus"></span> Danh sách mới</label>
        </div>
        <div class="bg bg-light pt-3" id="divAddBox" style="display: none">
          <input class="form-control mb-1 ml-4" type="text" id="nameBox" placeholder="Danh sách mới" style="width: 85%">
          <div class="row">
            <div class="col text-right">
              <button style="border: none; background-color: transparent" type="button" id="addBox" disabled>Thêm</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8" id="list">
      <div>
        <h4>
          <?php
          foreach ($titles as $key => $value) {
            if ($key == $boxId) {
              echo $value;
              break;
            }
          }
          ?></h4>
        <div class="btn-group" style="float: right; bottom: 15px" <?php if ($box->getId() < 0) echo "hidden"; ?>>
          <button class="dropdown-toggle" data-toggle="dropdown" style="border: none; background-color: transparent">
            <i class="fas fa-ellipsis-h"></i>
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#modal-member">Người tham gia</a>
            <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#modal-invite">Mời người khác</a>
            <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#modal-delete">Xóa danh sách</a>
          </div>
        </div>
      </div>

      <hr style="width: 5%; height: 2px; display: inline-block;" class="bg bg-info mt-0">

      <div class="row bg bg-light rounded pt-3 pb-2 mb-3" id="divAdd" style="display: block">
        <div class="col">
          <div class="form-group mb-1">
            <input class="form-control" placeholder="Thêm tác vụ" type="text" id="name">
          </div>
          <div class="row">
            <div class="col">
            </div>
            <div class="col text-right">
              <button style="border: none; background-color: transparent" type="button" id="add" disabled>Thêm</button>
            </div>
          </div>
        </div>
      </div>
      <script>
        $("#divAdd").hide();
      </script>
      <div class="row bg bg-light rounded pt-2 pb-2 mb-3" style="display: block" id="divAdd2" onclick="$('#divAdd').show(); $(this).hide();" <?php if ($box->getId() < 0) echo "hidden"; ?>>
        <div class="col">
          <div class="form-group mb-1 pl-2">
            <span class="fas fa-plus"></span> Thêm tác vụ
          </div>
        </div>
      </div>

      <div id="listItem">
        <?php
        $topics = $box->getTopics($db);
        foreach ($topics as $t) :
        ?>
          <div class="item" id="topic-<?= $t->getId() ?>">
            <div class="row">
              <div class="col-1 item-radio d-flex align-items-center ">
                <input type="checkbox" onchange="fChecker(this)" <?php if ($t->getDone()) echo "checked" ?>>
              </div>
              <div class="col-10 item-info">
                <div id="item-name" data-target="1" data-id="<?= $t->getId() ?>" <?php if ($t->getDone()) echo "style='text-decoration: line-through'" ?>>
                  <?= $t->getName() ?>
                </div>
                <div>
                  <span style="font-size: 12px">
                    Tác vụ
                    <?php if (count($t->getCmts($db)) > 0) : ?>
                      - <?= $t->getTextCmt(); ?>
                    <?php endif; ?>
                    <?php if ($t->getDescription() != "") : ?>
                      - <i class="far fa-sticky-note"></i>
                    <?php endif; ?>
                    <?php if ($t->getColorId() >= 0) : ?>
                      - <span class="border border-<?= $arrColor[$t->getColorId()] ?> text-<?= $arrColor[$t->getColorId()] ?>  rounded pr-1 pl-1"><?= $t->getTag() ?></span>
                    <?php endif; ?>
                  </span>
                  <?php if ($t->getUserId() > 0) : ?>
                    <span class="badge badge-info rounded-circle" style="float: right; bottom: 15px; padding: 5px"><?= Member::withId($db, $t->getUserId())->getCharIcon() ?></span>
                  <?php endif; ?>

                </div>
              </div>
              <div class="col-1">
                <?php if ($t->getStar()) : ?>
                  <i class="fas fa-star text-primary pointer" onclick="fSetStar(this, <?= $t->getId() ?>)"></i>
                <?php else : ?>
                  <i class="far fa-star pointer" onclick="fSetStar(this, <?= $t->getId() ?>)"></i>
                <?php endif; ?>

                <div class="btn-group">
                  <button class="dropdown-toggle pl-0" data-toggle="dropdown" style="border: none; background-color: transparent">
                    <i class="fas fa-ellipsis-h"></i>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="index.php?page=chi-tiet&id=<?= $t->getId() ?>">Chi tiết</a>
                    <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#modal-remote" onclick="fSetId(<?= $t->getId() ?>)">Thực hiện</a>
                    <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#modal-tag" onclick="fSetId(<?= $t->getId() ?>)">Gán nhãn</a>
                    <a class="dropdown-item" href="javascript:void(0)" onclick="fDeleteTopic(<?= $t->getId() ?>)">Xóa tác vụ</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg bg-info text-white">
        <h5 class="modal-title" id="">XÁC NHẬN</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6>BẠN CÓ CHẮC CHẮN MUỐN XÓA?</h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">ĐÓNG</button>
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" onclick="fDeleteBox(<?= $box->getId() ?>)">THỰC HIỆN</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="modal-member" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg bg-info text-white">
        <h5 class="modal-title" id="">NGƯỜI THAM GIA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-sm table-borderless">
          <tr>
            <th>Người dùng</th>
            <th>Họ và tên</th>
            <th></th>
          </tr>
          <?php
          $members = $box->getMembers($db);
          for ($i = 0; $i < count($members); $i++) :
            $m = $members[$i];
          ?>
            <tr>
              <td><?= $m->getUser() ?></td>
              <td><?= $m->getName() ?></td>
              <?php if ($i == 0) : ?>
                <td><i class="fas fa-user-cog"></i></td>
              <?php elseif ($box->getUserId() == $member->getId()) : ?>
                <td><i class="fas fa-times text-primary pointer" onclick="fRemoteMember(this, <?= $box->getId() ?>, <?= $m->getId() ?>)"></i></td>
              <?php endif; ?>
            </tr>
          <?php
          endfor;
          ?>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">ĐÓNG</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-tag" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg bg-info text-white">
        <h5 class="modal-title" id="">GÁN NHÃN</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label>Gán nhãn:</label>
            <input class="form-control" type="text" placeholder="Hôm nay" id="tagName">
          </div>
          <div class="form-group">
            <label>Màu sắc:</label>
            <select class="form-control" id="tagColor">
              <option value="0">Blue</option>
              <option value="1">Green</option>
              <option value="2">Red</option>
              <option value="3">Yellow</option>
              <option value="4">Black</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">ĐÓNG</button>
        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" onclick="fUpdateTag()">THỰC HIỆN</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-remote" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg bg-info text-white">
        <h5 class="modal-title" id="">TÁC VỤ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label>Người thực hiện:</label>
            <select class="form-control" id="remoteUserId">
              <option value="-1">KHÔNG MỘT AI</option>
              <?php
              $members = $box->getMembers($db);
              for ($i = 0; $i < count($members); $i++) :
                $m = $members[$i];
              ?>
                <option value="<?= $m->getId() ?>"><?= $m->getName() ?></option>
              <?php
              endfor;
              ?>
            </select>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">ĐÓNG</button>
        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" onclick="fUpdateRemote()">THỰC HIỆN</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modal-invite" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg bg-info text-white">
        <h5 class="modal-title" id="">MỜI NGƯỜI THAM GIA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="custom-control custom-switch text-center">
          <input type="checkbox" class="custom-control-input" id="acceptJoin" <?php if ($box->getJoin()) echo "checked"; ?>>
          <label class="custom-control-label" for="acceptJoin">Cho phép tham gia</label>
        </div>
        <div id="divLinkInvite" <?php if (!$box->getJoin()) echo 'style="display: none;"' ?>>
          <label>Đường dẫn:</label>
          <div class="input-group">
            <input class="form-control readonly" readonly="" type="text" value="<?= $home ?>index.php?page=join&id=<?= $box->getId() ?>" id="link">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="button" onclick="copy('link')">Copy</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">ĐÓNG</button>
      </div>
    </div>
  </div>
</div>



<script>
  var id;

  function fSetId(i) {
    id = i;
    console.log("SET ID=" + id);
  }


  $(document).ready(function() {

    $("#acceptJoin").on("change", function() {
      $.post({
        url: "controller.php?router=editbox&action=UpdateJoin",
        data: "id=<?= $box->getId() ?>&checked=" + $(this).is(":checked"),
        success: function(res) {
          console.log(res);
          if (res == "OK") {
            if ($("#acceptJoin").is(":checked")) {
              $("#divLinkInvite").show();
            } else {
              $("#divLinkInvite").hide();
            }
          }
        }
      })
    })

    $("#name").on("keyup", function() {
      $("#add").attr("disabled", $(this).val().length == 0)
    })
    $("#add").on("click", function() {
      addTopic();
    })

    $("#nameBox").on("keyup", function() {
      $("#addBox").attr("disabled", $(this).val().length == 0)
    })

    $("#addBox").on("click", function() {
      fAddBox();
    })

    function addTopic() {
      $.post({
        url: "controller.php?router=addtopic",
        data: "id=<?= $box->getId() ?>&name=" + $("#name").val(),
        success: function(res) {
          if (res.includes("OK|")) {
            var id = res.replace("OK|", "");
            $("#listItem").html('<div class="item" id="topic-' + id + '" style="display: none"><div class="row"> <div class="col-1 item-radio d-flex align-items-center "> <input type="checkbox" class="option-input">  </div> <div class="col-10 item-info"> <div id="item-name">' + $("#name").val() + '</div> <div> <span style="font-size: 12px"> Tác vụ </span> </div> </div> <div class="col-1">  <i class="far fa-star pointer" onclick="fSetStar(this, ' + id + ')"></i>  <div class="btn-group"> <button class="dropdown-toggle pl-0" data-toggle="dropdown" style="border: none; background-color: transparent"> <i class="fas fa-ellipsis-h"></i>  </button>   <div class="dropdown-menu"> <a class="dropdown-item" href="index.php?page=chi-tiet&id=' + id + '">Chi tiết</a> <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#modal-member">Tác vụ</a> <a class="dropdown-item" href="javascript:void(0)" onclick="fDeleteTopic(' + id + ')">Xóa</a> </div> </div> </div> </div> </div>' + $("#listItem").html());
            $(".item").first().fadeIn("slow");
          }
          $("#name").val("");
          $('#divAdd').hide();
          $('#divAdd2').show()
        }
      });
    }
  });

  function fUpdateRemote() {
    $.post({
      url: "controller.php?router=edittopic&action=UpdateRemote",
      data: "id=" + id + "&userId=" + $("#remoteUserId").val(),
      success: function(res) {
        console.log(res);

      }
    })
  }

  function fUpdateTag() {
    $.post({
      url: "controller.php?router=edittopic&action=UpdateTag",
      data: "id=" + id + "&colorId=" + $("#tagColor").val() + "&tag=" + $("#tagName").val(),
      success: function(res) {
        console.log(res);
        $("#tagName").val("");
        $("#tagColor").val(0).change();
      }
    })
  }

  function fDeleteTopic(id) {
    if (!confirm("Bạn có chắc chắn muốn xóa?")) {
      return;
    }
    $.post({
      url: "controller.php?router=edittopic&action=Delete",
      data: "type=1&id=" + id,
      success: function(res) {
        console.log(res);
        if (res == "OK") {
          var div = $('#topic-' + id);
          div.fadeOut("slow");
        }
      }
    })
  }

  function fRemoteMember(t, boxId, userId) {
    if (!confirm("Bạn có chắc chắn muốn loại bỏ thành viên này không?")) {
      return;
    }
    $.post({
      url: "controller.php?router=editbox&action=DeleteMember",
      data: "id=" + boxId + "&userId=" + userId,
      success: function(res) {
        console.log(res);
        if (res == "OK") {
          $(t).parent().parent().html("");
          $.notify("Thực hiện thành công !!!", "success");
        }
      }
    })
  }


  function fAddBox() {
    $.post({
      url: "controller.php?router=addbox",
      data: "name=" + $("#nameBox").val(),
      success: function(res) {
        console.log(res);
        if (res.includes("OK|")) {
          var id = res.replace("OK|", "");
          $("#listBox").html($("#listBox").html() + '<a href="index.php?boxId=' + id + '" ><i class="fas fa-bars"></i>&ensp;' + $("#nameBox").val().toUpperCase() + '</a>');
        }
        $("#nameBox").val("");
        $("#divAddBox").hide();
        $("#divAddBox2").show();
      }
    })
  }

  function fDeleteBox(id) {
    console.log("delete id => " + id);
    $.post({
      url: "controller.php?router=editbox&action=Delete",
      data: "id=" + id,
      success: function(res) {
        console.log(res);
        if (res == "OK") {
          window.location.replace(home);
        }
      }
    })
  }
</script>