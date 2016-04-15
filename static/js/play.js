function reg_toggle_menu() {
  $("#myNavmenu").on( "hidden.bs.offcanvas", function (e) {
    $(".main-content").removeClass("col-sm-9 col-md-9 col-lg-9");
  });
  $("#myNavmenu").on( "show.bs.offcanvas", function (e) {
    $(".main-content").addClass("col-sm-9 col-md-9 col-lg-9");
  });
}
