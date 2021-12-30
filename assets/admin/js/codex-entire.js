(function ($) {
  var table;
  var app = {
    init: function () {
      app.ready();
    },

    ready: function () {
      // load data to datatable
      table = $("#entire_form").DataTable();

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

      $("#selectAll").on("click", function () {
        $(".cf-select").attr("checked", this.checked);
      });

      $(".cf-select").on("change", function () {
        var select = $(".cf-select:checked");
        console.log(select);
        if (select.length) {
          var list = [];

          for (var i = 0; i < select.length; i++) {
            list.push(select[i].value);
          }

          console.log(list);
        }
      });

      // Create date inputs
      app.search_by_date();

      $(".view-entry").on("click", function () {
        var id = $(this).data("entry-id");

        var data = {
          id: id,
          action: "load_entire_value",
        };

        $.post(codex_admin.ajax_url, data, function (res) {
          if (res.success) {
            // show modal detail value
            $(".modal-view").modal("show");

            // show id entry
            $(".modal-view .header").html("Entry ID:" + id);

            let content = "";

            res.data.forEach(function (val) {
              content += val["field_id"] + " :" + val["value"] + "<br>";
            });

            $(".modal-view .content").html(content);

            $(".modal-view .actions .entry-edit").attr("data-entry-id", id);
          } else {
            console.log($(this));
          }
        }).fail(function (xhr, textStatus, e) {
          console.log(xhr.responseText);
        });
      });

      $(".modal-view .actions .entry-edit").on("click", function () {
        // show modal edit value
        $(".modal-edit").modal("show");
      });
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

      $("#min, #max").on("change", function () {
        table.draw();
      });
    },
  };

  app.init();
})(jQuery);
