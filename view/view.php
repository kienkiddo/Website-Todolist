<?php
$id = sql_refesh($_GET['id']);

include_once "model/Topic.php";
$topic = Topic::withId($db, $id);
if ($topic == null) {
  header("Location: $home");
  return;
}
?>


<div class="container" style="margin-top: 50px">
  <div class="row">
    <div class="col-lg-4 text-center">
      <div class="btn-group-vertical" style="width: 90%">
        <button type="button" class="btn btn-outline-info"><i class="fas fa-sun"></i> HÔM NAY</button>
        <button type="button" class="btn btn-outline-info"><i class="fas fa-star"></i> QUAN TRỌNG</button>
        <button type="button" class="btn btn-outline-info"><i class="fas fa-meteor"></i> GIAO CHO TÔI</button>
      </div>
    </div>
    <div class="col-lg-8" id="list">
      <div class="row">
        <div class="col-8">
          <h4>CHI TIẾT</h4>
          <hr style="width: 10%; height: 2px; display: inline-block;" class="bg bg-info mt-0">
        </div>
        <div class="col-4 text-right">
          <button class="btn btn-sm btn-primary mt-3" type="button" data-toggle="modal" data-target="#modal-add"><span class="fas fa-plus"></span> Thêm bước</button>
          <button class="btn btn-sm btn-danger mt-3" type="button" data-toggle="modal" data-target="#modal-delete"><span class="fas fa-trash"></span> Xóa</button>
        </div>
      </div>

      <div class="item mb-1 " style="border: none !important">
        <div class="row pb-2">
          <div class="col-1 item-radio d-flex align-items-center ">
            <input type="checkbox" onchange="fChecker(this)" <?php if ($topic->getDone()) echo "checked" ?>>
          </div>
          <div class="col-10 item-info">
            <div id="item-name" class="font-weight-bold" onclick="fEdit(this)" data-target="1" data-id="<?= $topic->getId() ?>" <?php if ($topic->getDone()) echo "style='text-decoration: line-through'" ?>><?= $topic->getName() ?></div>
          </div>
          <div class="col-1">
            <?php if ($topic->getStar()) : ?>
              <i class="fas fa-star text-primary pointer" onclick="fSetStar(this, <?= $topic->getId() ?>)"></i>
            <?php else : ?>
              <i class="far fa-star pointer" onclick="fSetStar(this, <?= $topic->getId() ?>)"></i>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div id="listCmt">
        <?php
        $data = $topic->getCmts($db);
        foreach ($data as $cmt) :
        ?>
          <div class="item" id="cmt-<?= $cmt->getId() ?>">
            <div class="row pb-2 pt-2">
              <div class="col-1 item-radio d-flex align-items-center">
                <input type="checkbox" onchange="fChecker(this)" style="width: 1.1rem; height: 1.1rem" <?php if ($cmt->getDone()) echo "checked" ?>>
              </div>
              <div class="col-10 item-info">
                <div id="item-name" onclick="fEdit(this)" data-target="<?= $cmt->getId() + 1 ?>" data-id="<?= $cmt->getId() ?>" <?php if ($cmt->getDone()) echo "style='text-decoration: line-through'" ?>><?= $cmt->getName() ?></div>
              </div>
              <div class="col-1">
                <i class="fas fa-times pointer" onclick="fDelete(2, <?= $cmt->getId() ?>)"></i>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="row mt-4">
        <div class="col">
          <textarea class="form-control" placeholder="Ghi chú" rows="5" id="note"><?= $topic->getDescription() ?></textarea>
        </div>
      </div>

      <div class="row">
        <div class="col text-right">
          <small>Cập nhật: <?= $topic->getTimeAgo() ?> trước</small>
        </div>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg bg-info text-white">
        <h5 class="modal-title" id="">THÊM BƯỚC</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Bước tiếp theo" id="step-name">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">ĐÓNG</button>
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal" onclick="fAddStep()">THỰC HIỆN</button>
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
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" onclick="fDelete(1, <?= $topic->getId() ?>)">THỰC HIỆN</button>
      </div>
    </div>
  </div>
</div>

<script>
  var id = -1;
  var targetId = "-1";

  var lastTimeNote;
  var timeOut = null;
  const timeSaveNote = 1000;


  $(document).ready(function() {


    $(document).click(function(event) {
      var target = $(event.target);
      if ($(target).prop("tagName") != "INPUT" && $(target).attr("data-target") != targetId && targetId != "-1") {
        var t = $('div [data-target="' + targetId + '"]');
        var name = t.children().val();
        t.html(name);
        console.log(id + " => " + name);
        fUpdateName(targetId, id, name);
        targetId = "-1";
      }
    });

    $("#note").on("keydown", function() {
      var text = $("#note").val();
      if (timeOut != null && new Date().getTime() - lastTimeNote < timeSaveNote) {
        clearTimeout(timeOut);
      }
      lastTimeNote = new Date().getTime();
      timeOut = setTimeout(() => {
        $.post({
          url: "controller.php?router=edittopic&action=updateDescription",
          data: "id=<?= $topic->getId() ?>&text=" + $("#note").val(),
          success: function(res) {
            console.log(res);
          }
        });
      }, timeSaveNote);
    })


    function fUpdateName(type, id, name) {
      $.post({
        url: "controller.php?router=edittopic&action=UpdateName",
        data: "type=" + type + "&id=" + id + "&name=" + name,
        success: function(res) {
          console.log(res);
        }
      });
    }
  });


  function fEdit(t) {
    var name = $(t).html();
    id = $(t).attr("data-id");
    if (targetId != $(t).attr("data-target")) {
      $(t).html("<input type='text' class='form-control' value='" + name + "'>");
    }
    targetId = $(t).attr("data-target");
  }


  function fAddStep() {
    $.post({
      url: "controller.php?router=edittopic&action=AddStep",
      data: "id=<?= $topic->getId() ?>&name=" + $("#step-name").val(),
      success: function(res) {
        console.log(res);
        if (res.includes("OK|")) {
          var id = res.replace("OK|", "");
          console.log(id);
          $("#listCmt").html('<div class="item" id="cmt-' + id + '"> <div class="row pb-2 pt-2"> <div class="col-1 item-radio d-flex align-items-center">  <input type="checkbox" onchange="fChecker(this)" style="width: 1.1rem; height: 1.1rem" > </div>  <div class="col-10 item-info"> <div id="item-name" onclick="fEdit(this)" data-target="' + (id + 1) + '" data-id="' + id + '">' + $("#step-name").val() + '</div> </div>  <div class="col-1">  <i class="fas fa-times pointer" onclick="fDelete(2, ' + id + ')"></i>  </div> </div> </div>' + $("#listCmt").html());
        }
        $("#step-name").val("");
      }
    })
  }


  function fDelete(type, id) {
    $.post({
      url: "controller.php?router=edittopic&action=Delete",
      data: "type=" + type + "&id=" + id,
      success: function(res) {
        console.log(res);
        if (res == "OK") {
          if (type == 1) {
            window.location.replace("");
          } else {
            var div = $('div #cmt-' + id);
            div.fadeOut("slow");
          }
        }
      }
    })
  }
</script>