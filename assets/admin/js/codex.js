jQuery(function ($) {
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
});
