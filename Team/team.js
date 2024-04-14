var timeout = null;
$(".diamond").click(function () {
  var $diamond = $(this);
  var $diamondParent = $diamond.parent();
  clearTimeout(timeout);
  $("#userbg").toggleClass("on");
  $(".user, .userinfo").not(this).parent().removeClass("on");

  timeout = setTimeout(function () {
    $diamondParent.toggleClass("on");
    clearTimeout(timeout);
  }, 300);
  $diamondParent.next(".userinfo").toggleClass("on"); 
});

$(".close").click(function () {
  $(".user, .userinfo, #userbg").removeClass("on");
});