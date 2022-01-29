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

      // View each entry value
      $(".view-entry").on("click", function () {
        var id = $(this).data("entry-id");
        app.entry_view(id);
      });

      $(".modal-view .actions .entry-edit").on("click", function () {
        var id = $(this).data("entry-id");
        app.entry_edit(id);
      });

      $(".modal-edit .actions .entry-view").on("click", function () {
        // show modal detail value
        var id = $(this).data("entry-id");
        app.entry_view(id);
      });

      $(".modal-edit .actions .entry-save").on("click", function (e) {
        e.preventDefault();
        var id = $(this).data("entry-id");
        var form_data = JSON.stringify($(".edit_entry_value").serializeArray());

        app.entry_save_value(id, form_data);
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

    entry_view: function (entry_id) {
      var form_id = $("#form_id").val();

      var data = {
        form_id: form_id,
        entry_id: entry_id,
        action: "load_entry_value",
      };

      $.post(codex_admin.ajax_url, data, function (res) {
        if (res.success) {
          // show modal detail value
          $(".modal-view").modal("show");

          // show id entry
          $(".modal-view .header").html("Entry ID:" + entry_id);

          var content =
            "<thead><tr><th>Field Name</th><th>Value</th></tr></thead>";

          res.data.forEach(function (val) {
            var name = val["name"];

            if (val["name"] === "") {
              name = val["field_id"];
            }

            content += "<tr>";
            content += "<td>" + name + " (" + val["type"] + ")</td>";
            content += "<td>" + val["value"] + "</td>";
            content += "</tr>";
          });

          $(".modal-view .content .table").html(content);

          $(".modal-view .actions .entry-edit").attr("data-entry-id", entry_id);
        } else {
          console.log($(this));
        }
      }).fail(function (xhr, textStatus, e) {
        console.log(xhr.responseText);
      });
    },

    entry_edit: function (entry_id) {
      var form_id = $("#form_id").val();

      var data = {
        form_id: form_id,
        entry_id: entry_id,
        action: "load_entry_value",
      };

      $.post(codex_admin.ajax_url, data, function (res) {
        if (res.success) {
          // show modal edit value
          $(".modal-edit").modal("show");

          // show id entry
          $(".modal-edit .header").html("Entry ID:" + entry_id);

          var content =
            "<thead><tr><th>Field Name</th><th>Value</th></tr></thead>";

          res.data.forEach(function (val) {
            var name = val["name"];

            if (val["name"] === "") {
              name = val["field_id"];
            }

            content += "<tr>";
            content += "<td>" + name + "</td>";
            content +=
              "<td><input type='text' name='" +
              val["field_id"] +
              "' value='" +
              val["value"] +
              "'</td>";
            content += "</tr>";
          });

          $(".modal-edit .content .table").html(content);

          $(".modal-edit .actions .entry-view").attr("data-entry-id", entry_id);
          $(".modal-edit .actions .entry-save").attr("data-entry-id", entry_id);
        }
      });
    },

    // save entry edit value
    entry_save_value: function (entry_id, form_data) {
      var data = {
        entry_id: entry_id,
        entry_value: form_data,
        action: "save_edit_entry",
      };

      console.log(data.entry_value);
      $.post(codex_admin.ajax_url, data, function (res) {
        if (res.success) {
        } else {
          console.log(res);
        }
      }).fail(function (xhr, textStatus, e) {
        console.log(xhr.responseText);
      });
    },
  };

  app.init();
})(jQuery);
