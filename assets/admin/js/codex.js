(function ($) {
  var app = {
    init: function () {
      app.ready();
    },

    ready: function () {
      // Event show button menu
      $(".new-form").on("click", function () {
        $(".ui.modal").modal("show");
        $(".message").addClass("hidden");
      });

      $(".special.cards .image").dimmer({
        on: "hover",
      });

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
        navigator.clipboard.writeText(text);
      });

      // Generate to DataTable
      $("#show_form").DataTable();

      // Create new form
      $(".create-form").on("click", function () {
        app.Create_Form();
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

      // import json file form template to new form
      $(".btn_import_form").on("click", function () {
        $("#import_form").click();
      });

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

    Create_Form: function (id) {
      var alert = $(this).parent().prev();
      var formName = $("#form_name");
      var loading = $(this).parent();

      loading.addClass("loading");
      var data = {
        title: formName.val(),
        action: "new_form",
      };

      $.post(codex_admin.ajax_url, data, function (res) {
        if (res.success) {
          window.location.href = res.data.redirect;
        } else {
          alert.removeClass("hidden");
          loading.removeClass("loading");
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
          $(".notify-alert").html("Something wrong");
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
          $(".notify-alert").html("Something wrong");
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
