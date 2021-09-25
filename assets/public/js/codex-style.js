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

    formEntry: function (form_data) {
      var data = {
        entry_value: form_data,
        action: "entry_value",
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
});
