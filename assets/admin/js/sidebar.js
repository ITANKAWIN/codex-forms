jQuery(function ($) {
  $("#sidebarCollapse").on("click", function () {
    $("#sidebar").toggleClass("sidebar_close");
    $("#content").toggleClass("sidebar_close");
  });

  $(".top.menu .item").tab({
    onLoad: function () {
      $(this).addClass("loading");
      setTimeout(function () {
        $(".tab.active").removeClass("loading");
      }, 500);
    },
  });
});
