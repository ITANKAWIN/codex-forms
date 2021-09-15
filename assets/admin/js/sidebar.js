jQuery(function ($) {
  $("#sidebarCollapse").on("click", function () {
    $("#sidebar").toggleClass("active");
    $("#content").toggleClass("active");
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
