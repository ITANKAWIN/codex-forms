jQuery(function ($) {
  var app = {
    init: function () {
      $(app.ready);
    },

    ready: function () {
      $(".entry-content").find("[disabled]").removeAttr("disabled");

      $(".ui.dropdown").dropdown();

      $(".entry-content").on("submit", "form.codex_forms_form", function (e) {
        e.preventDefault();
        var form_data = JSON.stringify($(".codex_forms_form").serializeArray());

        app.formEntry(form_data);
      });
    },

    formEntry: function (data) {
      var input_file = $(".layout-panel").find("input[type=file]");

      var file_upload = "";
      if (input_file.length > 0) {
        file_upload = input_file[0].files[0];
      }

      form_data = new FormData();
      form_data.append("data", data);
      form_data.append("file", file_upload);
      form_data.append("file_id", input_file.attr("id"));
      form_data.append("action", "entry_value");

      $.ajax({
        url: codex_admin.ajax_url,
        type: "POST",
        contentType: false,
        processData: false,
        data: form_data,
        success: function (res) {
          console.log(res);
          alert("Submit Success");
        },
        error: function () {
          alert("something went wrong");
        },
      });
    },
  };

  app.init();
});
