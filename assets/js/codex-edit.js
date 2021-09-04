(function ($) {
  //Event mouseenter show button delete, join, split
  $(".layout-panel").on("mouseenter", ".layout-row", function (e) {
    var setrow = $(this);
    setrow
      .children()
      .children()
      .first()
      .append(
        '<div class="delete-row column-tools"><i class="dashicons dashicons-remove"></i></div>'
      );

    setrow
      .children()
      .children()
      .not(":first")
      .prepend(
        '<div class="join-column column-tools"><i class="dashicons dashicons-leftright"></i></div>'
      );

    setrow
      .children()
      .children()
      .each(function (k, v) {
        var column = $(v);
        var width = column.width() / 2 - 5;
        if (!column.parent().hasClass("col-1")) {
          column.prepend(
            '<div class="split-column column-tools"><i class="dashicons dashicons-image-flip-horizontal"></i></div>'
          );
          column.find(".split-column").css("left", width);
        }
      });
  });

  //Event mouseleave hide button delete, join, split
  $(".layout-panel").on("mouseleave", ".layout-row", function (e) {
    $(".column-tools").remove();
  });

  //Event mouse click delete row
  $(".layout-panel").on("click", ".delete-row", function (e) {
    var row = $(this).closest(".layout-row"),
      fields = row.find(".field-row");

    //find fields
    if (fields.length) {
      if (!confirm("Are you sure to delete.")) {
        return;
      }

      fields.each(function (k, v) {
        var field_id = $(v).data("field-id");

        // remove config

        $('[data-field-id="' + field_id + '"]').remove();
      });
    }

    row.slideUp(200, function () {
      $(this).remove();

      buildLayout();
    });
  });

  //Event mouse click spilt row to column
  $(".layout-panel").on("click", ".split-column", function (e) {
    var column = $(this).parent().parent(),
      size = column.attr("class").split("-"),
      newcol = $("<div>").insertAfter(column);

    var left = Math.ceil(size[1] / 2),
      right = Math.floor(size[1] / 2);

    size[1] = left;

    column.attr("class", size.join("-"));

    size[1] = right;

    newcol.addClass(size.join("-")).append('<div class="layout-column">');

    $(this).remove();

    $(".column-tools").remove();

    buildLayout();
  });

  //Event mouse click join column
  $(".layout-panel").on("click", ".join-column", function (e) {
    var column = $(this).parent().parent();

    var prev = column.prev(),
      left = prev.attr("class").split("-"),
      right = column.attr("class").split("-");

    left[1] = parseFloat(left[1]) + parseFloat(right[1]);

    column

      .find(".layout-column")

      .contents()

      .appendTo(prev.find(".layout-column"));

    prev.attr("class", left.join("-"));

    column.remove();

    buildLayout();

    $(".column-tools").remove();
  });

  //Event mouseenter show button config and delete field
  $(".layout-panel").on("mouseenter", ".field-row", function (e) {
    $(this).append(codex_admin.config_field);
    $(this).append(codex_admin.delete_field);
  });

  //Event mouseleave hide button config and delete field
  $(".layout-panel").on("mouseleave", ".field-row", function (e) {
    $(".config-field").remove();
    $(".delete-field").remove();
  });

  $(".layout-panel").on("click", ".config-field", function (e) {
    var id = $(this).parent().data("field-id");

    var field = $(this).parent();

    if ($("#sidebar").hasClass("active")) {
      $("#sidebar").toggleClass("active");
    }

    $("[data-tab=config]").trigger("click");

    $(".wrapper-instance-pane").hide();

    if (field.hasClass("config-open")) {
      $(".config_field_" + id).show();
    } else {
      $(".field-row").removeClass("config-open");

      field.addClass("config-open");

      $(".config_field_" + id).show();
    }
  });

  $(".layout-panel").on("click", ".delete-field", function (e) {
    if (confirm("Are you sure you want to delete?")) {
      var id = $(this).parent().data("field-id");

      $("[data-field-id='" + id + "']").remove();
    }
  });

  $("#save_form").click(function () {
    //ajax save change edit

    var formName = $("#form_name");

    var formID = $("#form_id");

    $("#save_form").html(
      "<strong class='saving'>Saving<span>.</span><span>.</span><span>.</span></strong>"
    );

    var data = {
      title: formName.val(),

      id: formID.val(),

      data: JSON.stringify($("#panel").serializeArray()),

      action: "save_form",
    };

    console.log($("#panel").serializeArray());

    $.post(codex_admin.ajax_url, data, function (res) {
      if (res.success) {
        $("#save_form").html("<strong>Saved</strong>");
      } else {
        $("#save_form").html("<strong>Save is not successful</strong>");
      }
    }).fail(function (xhr, textStatus, e) {
      console.log(xhr.responseText);
    });
  });

  function jQueryui() {
    $(".layout-column, .panel-row").sortable({
      connectWith: ".layout-column",

      items: ".field-row",

      cursor: "move",

      stop: function (e, ui) {
        ui.item.removeAttr("style");

        buildLayout();
      },

      receive: function (event, ui) {
        var formID = $("#form_id");

        var field_id = Math.floor(Math.random() * 900000) + 100000;

        var ui_field = ui.item;

        if (ui.helper != null) {
          ui_field = ui.helper;
        }

        var field_type = ui_field.data("field-type");

        var data = {
          id: formID.val(),

          field_id: field_id,

          action: "codex_new_field_" + field_type,
        };

        $.post(codex_admin.ajax_url, data, function (res) {
          if (res.success) {
            ui_field.attr("data-field-id", field_id);
            ui_field.append(res.data.preview);
            ui_field.append(res.data.position);
            $(".config-fields").append(res.data.config);
            buildLayout();
          } else {
            console.log(res);
          }
        }).fail(function (xhr, textStatus, e) {
          console.log(xhr.responseText);
        });
      },
    });

    $(".field-item").draggable({
      connectToSortable: ".layout-column",

      cursor: "grabbing",

      opacity: 0.75,

      revert: "invalid",

      start: function (e, ui) {
        ui.helper.addClass("ui segment");
      },

      helper: function () {
        var $this = $(this),
          type = $this.data("field-type"),
          $el = $('<div class="field-row in-panel"></div>'),
          text = $this.html();

        return $el

          .html(text)

          .css("height", "70px")

          .attr("data-field-type", type);
      },

      cancel: false,

      containment: "document",
    });
  }

  function buildLayout() {
    var grid_panels = $(".layout-panel"),
      row_index = 0;

    grid_panels.each(function (pk, pv) {
      var panel = $(pv),
        capt = panel.find("input[name='panels']"),
        rows = panel.find(".layout-row"),
        struct = [];

      rows.each(function (k, v) {
        var row = $(v),
          cols = row.children(),
          rowcols = [];

        row_index += 1;

        cols.each(function (p, c) {
          span = $(c).attr("class").split("-");

          rowcols.push(span[1]);

          var fields = $(c).find(".panel");

          if (fields.length) {
            fields.each(function (x, f) {
              var field = $(f);

              field.val(row_index + ":" + (p + 1)).removeAttr("disabled");
            });
          }
        });

        struct.push(rowcols.join(":"));
      });

      capt.val(struct.join("|"));
    });

    jQueryui();
  }

  $("#add-row").click(function () {
    $(".layout-panel").append(
      `

      <div class="layout-row">

        <div class="col-12">

            <div class='layout-column'>

            </div>

        </div>

      </div>

      `
    );

    buildLayout();
  });

  jQueryui();
})(jQuery);
