
function fChecker(t) {
  if ($(t).is(":checked")) {
    $(t).parent().next().children().eq(0).css("text-decoration", "line-through");
  } else {
    $(t).parent().next().children().eq(0).css("text-decoration", "none");
  }
  var type = $(t).parent().next().children().eq(0).attr("data-target");
  var id = $(t).parent().next().children().eq(0).attr("data-id");
  console.log("type: " + type + " => id: " + id);
  $.post({
    url: "controller.php?router=edittopic&action=UpdateDone",
    data: "type=" + type + "&id=" + id + "&checked=" + $(t).is(":checked"),
    success: function(res) {
      console.log(res);
    }
  })
}

function fSetStar(t, id) {
  console.log("set star id= " + id);
  $.post({
    url: "controller.php?router=edittopic&action=UpdateStar",
    data: "id= " + id,
    success: function(res) {
      var star = Number(res);
      console.log(star);
      var arr = ["far fa-star pointer", "fas fa-star text-primary pointer"];
      $(t).attr("class", arr[star]);
    }
  });
}


