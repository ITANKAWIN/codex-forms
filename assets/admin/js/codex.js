(function ($) {
  var app = {
    init: function () {
      app.ready();
    },

    ready: function () {
      $(".message .close").on("click", function () {
        $(this).closest(".message").transition("fade");
      });

      $(".option-show-form").on("mouseenter", function (e) {
        $(this).children().find(".menu-button").show();
      });

      $(".option-show-form").on("mouseleave", function (e) {
        $(".menu-button").hide();
      });

      $(".short-code-copy").on("click", function (e) {
        var text = $(this).text();
        
        var sampleTextarea = document.createElement("textarea");
        document.body.appendChild(sampleTextarea);
        sampleTextarea.value = text;
        sampleTextarea.select();
        document.execCommand("copy");
        document.body.removeChild(sampleTextarea);
      });

      // Generate to DataTable
      $("#show_form").DataTable();

      // Event show button menu
      $(".new-form").on("click", function (e) {
        e.preventDefault();
        $(".modal_template").modal("show");
      });

      // click select template
      $(".form-template").on("click", function (e) {
        e.preventDefault();
        var template = $(this).data("template-name");

        $(".modal_create_form").modal("show");
        $(".create-form").attr("data-template", template);
      });

      // Create new form
      $(".create-form").on("click", function (e) {
        e.preventDefault();
        var title = $("#form_name").val();
        var template = $(this).data("template");

        app.Create_Form(title, template);
      });

      // Delete Form
      $(".delete-form").click(function () {
        let text = "Do you want to delete form ?";
        if (confirm(text) == true) {
          var id = $(this).data("id");
          app.Delete_Form(id);
        }
      });

      // Duplicate to new form
      $(".duplicate-form").on("click", function () {
        let text = "Do you want to Duplicate form ?";
        if (confirm(text) == true) {
          var id = $(this).data("id");
          app.Duplicate_Form(id);
        }
      });

      // click button import file
      $(".btn_import_form").on("click", function () {
        $("#import_form").click();
      });

      // import json file form template to new form
      $("#import_form").on("change", function () {
        var GetFile = new FileReader();
        file = this.files[0];
        let name = file.name;
        name = name.substring(0, name.length - 5);
        GetFile.readAsText(file);
        GetFile.onload = function () {
          let data = JSON.parse(GetFile.result);
          app.Import_Form(name, data);
        };
      });
    },

    Create_Form: function (title, template) {
      var data = {
        title: title,
        template: template,
        action: "new_form",
      };

      $.post(codex_admin.ajax_url, data, function (res) {
        if (res.success) {
          window.location.href = res.data.redirect;
        } else {
          alert("Something went wrong");
        }
      }).fail(function (xhr, textStatus, e) {
        console.log(xhr.responseText);
      });
    },

    Delete_Form: function (id) {
      var data = {
        id: id,
        action: "delete_form",
      };

      $.post(codex_admin.ajax_url, data, function (res) {
        if (res.success) {
          window.location.href = res.data.redirect;
        } else {
          alert("Something went wrong");
        }
      }).fail(function (xhr, textStatus, e) {
        console.log(xhr.responseText);
      });
    },

    Duplicate_Form: function (id) {
      var data = {
        id: id,
        action: "duplicate_form",
      };

      $.post(codex_admin.ajax_url, data, function (res) {
        if (res.success) {
          window.location.href = res.data.redirect;
        } else {
          alert("Something went wrong");
        }
      }).fail(function (xhr, textStatus, e) {
        console.log(xhr.responseText);
      });
    },

    Import_Form: function (name, data) {
      var data = {
        title: name,
        data: data,
        action: "new_form",
      };

      $.post(codex_admin.ajax_url, data, function (res) {
        if (res.success) {
          window.location.href = res.data.redirect;
        } else {
          alert("Something went wrong");
        }
      }).fail(function (xhr, textStatus, e) {
        console.log(xhr.responseText);
      });
    },
  };
  app.init();
})(jQuery);
