jQuery(function ($) {
  $("#sidebarCollapse").on("click", function () {
    $("#sidebar").toggleClass("sidebar_close");
    $("#content").toggleClass("sidebar_close");

    if ($("#sidebar").hasClass("sidebar_close")) {
      $("#sidebarCollapse").html('<i class="angle left icon"></i>');
    } else {
      $("#sidebarCollapse").html('<i class="angle right icon"></i>');
    }
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
