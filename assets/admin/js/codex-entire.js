(function ($) {
  var app = {
    init: function () {
      app.ready();
    },

    ready: function () {
      // select form
      $("#select-form").on("change", function () {
        var url = $(this).val(); // get selected value
        if (url) {
          if (url == "-") {
            return false;
          }
          // require a URL
          window.location = "?page=entire-codex-forms&form=" + url; // redirect
        }
        return false;
      });

      // Create date inputs
      app.search_by_date();
    },

    search_by_date: function () {
      var minDate, maxDate;
      minDate = new DateTime($("#min"), {
        format: "MMMM Do YYYY",
      });
      maxDate = new DateTime($("#max"), {
        format: "MMMM Do YYYY",
      });

      // Custom filtering function which will search data in column four between two values
      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        var min = minDate.val();
        var max = maxDate.val();
        var date = new Date(data[2]);

        if (
          (min === null && max === null) ||
          (min === null && date <= max) ||
          (min <= date && max === null) ||
          (min <= date && date <= max)
        ) {
          return true;
        }
        return false;
      });

      // load data to datatable
      var table = $("#entire_form").DataTable();

      $("#min, #max").on("change", function () {
        table.draw();
      });
    },
  };

  app.init();
})(jQuery);
