(function ($) {
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

  $(".create-form").on("click", function () {
    var alert = $(this).parent().prev();
    var formName = $("#form_name");
    var loading = $(this).parent();

    loading.addClass("loading");
    var data = {
      title: formName.val(),
      action: "new_form",
    };

    console.log(data);
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
  });

  $(".delete-form").click(function () {
    console.log("asdasd");
    var data = {
      id: $(".delete-form").data("id"),
      action: "delete_form",
    };

    $.post(codex_admin.ajax_url, data, function (res) {
      if (res.success) {
        window.location.href = res.data.redirect;
      } else {
        console.log($(this));

        // $(".notify-alert").html("Something wrong");
      }
    }).fail(function (xhr, textStatus, e) {
      console.log(xhr.responseText);
    });
  });

  $(".option-show-form").on("mouseenter", function (e) {
    $(this).children().find(".menu-button").show();
  });

  $(".option-show-form").on("mouseleave", function (e) {
    $(".menu-button").hide();
  });

  $(".option-show-form").on("click", function (e) {
    var text = $(".short-code-copy").text();
    navigator.clipboard.writeText(text);
  });

  var app = {
    init: function () {
      app.ready();
    },

    ready: function () {
      $("#select-form").on("change", function () {
        var id = $(this).val();
        app.load_entire(id);
      });

      $("#show_form").DataTable();
    },

    load_entire: function (id) {
      var data = {
        id: id,
        action: "load_entire",
      };

      $.post(codex_admin.ajax_url, data, function (res) {
        if (res.success) {
          console.log(res.data);
          // res.data.forEach((entry) => {
          //   $("#entire-val").append("<tr><td>" + entry.id + "</td></tr>");
          // });

          var entry_val = "",
            entry_name = "";

          for (i in res.data) {
            console.log(i);
            console.log(res.data[i]);
            // entry_name += "<tr>";
            entry_val += "<tr>";
            entry_val +=
              "<td><input type='checkbox' name='check[]' value='" +
              i +
              "'></td>";

            for (j in res.data[i]) {
              // if (idx === arr.length - 1) {
              //   entry_name += "<th>" + entry.field_id + "</th>";
              // }
              entry_val += "<td>" + j + "</td>";
            }

            entry_val += "</tr>";
            // entry_name += "</tr>";
          }

          // $("#entire-name").append(entry_name);
          $("#entire-val").html(entry_val);
        } else {
          // console.log(res);
        }
      });
    },
  };
  app.init();
})(jQuery);
