<?php
include_once "model/Box.php";
$box = Box::getBoxOff($db, $member->getId());
?>

<style>

</style>

<div class="container" style="margin-top: 50px">
  <div class="row">
    <div class="col-lg-4 text-center">
      <div class="btn-group-vertical" style="width: 90%">
        <button type="button" class="btn btn-outline-info"><i class="fas fa-sun"></i> HÔM NAY</button>
        <button type="button" class="btn btn-outline-info"><i class="fas fa-star"></i> QUAN TRỌNG</button>
        <button type="button" class="btn btn-outline-info"><i class="fas fa-clock"></i> QUÁ HẠN</button>
        <button type="button" class="btn btn-outline-info"><i class="fas fa-meteor"></i> GIAO CHO TÔI</button>
      </div>
    </div>
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

      <div class="row bg bg-light rounded pt-3 pb-2 mb-3" style="display: block" onclick="$('#divAdd').show(); $(this).hide();">
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
        }
      });
    }
  });
</script>