<?php
$boxId = isset($_GET['boxId']) ? $_GET['boxId'] : 0;
include_once "model/Box.php";
if ($boxId == 0){
  $box = Box::getBoxOff($db, $member->getId());
} else if ($boxId > 0){
  $box = Box::withId($db, $boxId);
}
?>

<div class="container" style="margin-top: 50px">
  <div class="row">
    <div class="col-lg-4 text-center" style="padding-right: 30px;">
      <div class="bg bg-light pb-3" id="navBox">
        <div class="btn-group-vertical" style="width: 100%">
          <a href="index.php?boxId=0"><i class="fas fa-sun"></i>&ensp;HÔM NAY</a>
          <a href="javascript:void(0);"><i class="fas fa-star"></i>&ensp;QUAN TRỌNG</a>
          <a href="javascript:void(0);"><i class="fas fa-clock"></i>&ensp;QUÁ HẠN</a>
          <a href="javascript:void(0);"><i class="fas fa-meteor"></i>&ensp;GIAO CHO TÔI</a>
        </div>
        <hr style="width: 95%" class="mb-0">
        <div class="btn-group-vertical" style="width: 100%" id="listBox">
          <?php
          $boxs = Box::all($db, $member->getId());
          foreach ($boxs as $b) : ?>
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

    <script>
      function fAddBox() {
        $.post({
          url: "controller.php?router=addbox",
          data: "name=" + $("#nameBox").val(),
          success: function(res) {
            console.log(res);
            if (res.includes("OK|")) {
              var id = res.replace("OK|", "");
              $("#listBox").html($("#listBox").html() + '<a href="index.php?box=' + id + '" ><i class="fas fa-bars"></i>&ensp;' + $("#nameBox").val() + '</a>');
            }
            $("#nameBox").val("");
            $("#divAddBox").hide();
            $("#divAddBox2").show();
          }
        })
      }

      $(document).ready(function() {
        $("#addBox").on("click", function() {
          fAddBox();
        })
      });
    </script>



    <div class="col-lg-8" id="list">
      <h4>HÔM NAY</h4>
      <hr style="width: 5%; height: 2px; display: inline-block;" class="bg bg-info mt-0">

      <div class="row bg bg-light rounded pt-3 pb-2 mb-3" style="display: none" id="divAdd">
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
      <div class="row bg bg-light rounded pt-2 pb-2 mb-3" style="display: block" id="divAdd2" onclick="$('#divAdd').show(); $(this).hide();">
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
          <div class="item">
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
                  </span>
                </div>
              </div>
              <div class="col-1">
                <?php if ($t->getStar()) : ?>
                  <i class="fas fa-star text-primary pointer" onclick="fSetStar(this, <?= $t->getId() ?>)"></i>
                <?php else : ?>
                  <i class="far fa-star pointer" onclick="fSetStar(this, <?= $t->getId() ?>)"></i>
                <?php endif; ?>
                <a href="index.php?page=chi-tiet&id=<?= $t->getId() ?>" style="color: #6c757d"><i class="fas fa-ellipsis-h"></i></a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>



<script>
  $(document).ready(function() {

    $("#name").on("keyup", function() {
      $("#add").attr("disabled", $(this).val().length == 0)
    })
    $("#add").on("click", function() {
      addTopic();
    })

    $("#nameBox").on("keyup", function() {
      $("#addBox").attr("disabled", $(this).val().length == 0)
    })

    function addTopic() {
      $.post({
        url: "controller.php?router=addtopic",
        data: "id=<?= $box->getId() ?>&name=" + $("#name").val(),
        success: function(res) {
          if (res.includes("OK|")) {
            var id = res.replace("OK|", "");
            $("#listItem").html('<div class="item" style="display: none"><div class="row"> <div class="col-1 item-radio d-flex align-items-center "> <input type="checkbox" class="option-input">  </div> <div class="col-10 item-info"> <div id="item-name">' + $("#name").val() + '</div> <div> <span style="font-size: 12px"> Tác vụ </span> </div> </div> <div class="col-1">  <i class="far fa-star pointer" onclick="fSetStar(this, ' + id + ')"></i> <a href="index.php?page=chi-tiet&id=' + id + '" style="color: #6c757d"><i class="fas fa-ellipsis-h"></i></a> </div> </div> </div>' + $("#listItem").html());
            $(".item").first().fadeIn("slow");

          }
          $("#name").val("");
          $('#divAdd').hide();
          $('#divAdd2').show()
        }
      });
    }
  });
</script>