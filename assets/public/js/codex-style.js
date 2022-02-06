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

      // Field  star rating
      $(".codex-rating").rating("setting", "onRate", function (value) {
        var id = $(this).data("id");
        $('input[name="field_id[' + id + ']"]').val(value);
      });

      // Field Scale Range
      $(".codex-range").on("change", function () {
        var id = $(this).data("id");
        $("#" + id).html($(this).val());
      });
    },

    formEntry: function (data) {
      var input_file = $(".layout-panel").find("input[type=file]");

      var file_upload = "";
      if (input_file.length > 0) {
        file_upload = input_file[0].files[0];
      }

      var checkbox = $("input:checkbox").map(function () {
        return { name: this.name, value: this.checked ? this.value : "false" };
      });

      form_data = new FormData();
      form_data.append("data", data);
      form_data.append("file", file_upload);
      form_data.append("file_id", input_file.attr("id"));

      console.log($(".layout-panel").data("template"));

      if ($(".layout-panel").data("template") == "login") {
        form_data.append("action", "submit_form_login");
      } else if ($(".layout-panel").data("template") == "register") {
        form_data.append("action", "submit_form_register");
      } else {
        form_data.append("action", "submit_form");
      }

      $.ajax({
        url: codex_admin.ajax_url,
        type: "POST",
        contentType: false,
        processData: false,
        data: form_data,
        success: function (res) {
          console.log(res);
          if (res.success) {
            // alert(res.data.succ_msg);
            // if (res.data.redirect === "") {
            //   location.reload();
            // } else {
            //   window.location = res.data.redirect;
            // }
          } else {
            alert("something went wrong");
          }
        },
        error: function () {
          alert("something went wrong");
        },
      });
    },
  };

  app.init();
});
