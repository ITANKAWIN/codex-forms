(function ($) {
  var app = {
    init: function () {
      app.ready();
    },

    ready: function () {
      $("#entire_form").DataTable();
    },
  };
  app.init();
})(jQuery);
