(function ($) {
  var app = {
    init: function () {
      $(app.ready);
    },

    ready: function () {
      // call function drag&drop&sort field
      app.jQueryui();

      // check template field one time use
      app.field_ots();

      // load on action
      app.load_on_action();

      // Event mouse click add row panel
      $("#add-row").click(function () {
        app.addRow_panel();
      });

      //Event mouse click delete row panel
      $(".layout-panel").on("click", ".delete-row", function (e) {
        e.preventDefault();
        app.deleteRow_panel($(this));
      });

      //Event mouseenter show button delete, join, split
      $(".layout-panel").on("mouseenter", ".layout-row", function (e) {
        app.showTool_panel($(this));
      });

      //Event mouseleave hide button delete, join, split
      $(".layout-panel").on("mouseleave", ".layout-row", function (e) {
        $(".tools-column").remove();
      });

      //Event mouse click spilt row to column
      $(".layout-panel").on("click", ".split-column", function (e) {
        app.splitColumn_panel($(this));
      });

      //Event mouse click join column
      $(".layout-panel").on("click", ".join-column", function (e) {
        app.joinColumn_panel($(this));
      });

      //Event mouseenter show button config and delete field
      $(".layout-panel").on("mouseenter", ".field-row", function (e) {
        app.showTool_field($(this));
      });

      //Event mouseleave hide button config and delete field
      $(".layout-panel").on("mouseleave", ".field-row", function (e) {
        $(".config-field").remove();
        $(".delete-field").remove();
      });

      //Event mouse click show tab config field
      $(".layout-panel").on("click", ".config-field", function (e) {
        app.config_field($(this));
      });

      //Event mouse click delete field
      $(".layout-panel").on("click", ".delete-field", function (e) {
        if (confirm("Are you sure you want to delete?")) {
          var id = $(this).parent().data("field-id");

          $("[data-field-id='" + id + "']").remove();

          app.field_ots();
        }
      });

      // Save Form setting and field
      $(".save_form").on("click", function (e) {
        e.preventDefault();
        app.save_form();
      });

      //
      // For config realtime preview
      //

      // realtime chang placeholder
      $(".config-form-label").keyup(function () {
        app.fieldconfig_label($(this));
      });

      // realtime chang placeholder
      $(".config-form-placeholder").keyup(function () {
        app.fieldconfig_placeholder($(this));
      });

      // realtime chang button text
      $(".config-form-text-button").keyup(function () {
        app.fieldconfig_text_button($(this));
      });

      // realtime chang button align
      $(".config-form-align-button").on("change", function () {
        app.fieldconfig_align_button($(this));
      });

      //
      // For config field
      //

      // Add Option Field Select
      $(".config-fields").on("click", ".add", function (e) {
        app.fieldSelectOptionAdd(e, $(this));
      });

      // Remove Option Field Select
      $(".config-fields").on("click", ".remove", function (e) {
        app.fieldSelectOptionRemove(e, $(this));
      });

      // show modal setting
      $(".setting-form").on("click", function () {
        $(".modal-setting").modal("show");
      });

      // field image select media
      $(".select_media").on("click", function (e) {
        e.preventDefault();

        var mediaUploader,
          id = $(this).data("id");

        if (mediaUploader) {
          mediaUploader.open();
          return;
        }

        // Extend the wp.media object
        mediaUploader = wp.media.frames.file_frame = wp.media({
          title: "Choose Image",
          button: {
            text: "Choose Image",
          },
          multiple: false,
        });

        // When a file is selected, grab the URL and set it as the text field's value
        mediaUploader.on("select", function () {
          attachment = mediaUploader.state().get("selection").first().toJSON();

          // change preview image
          $("#" + id).attr("src", attachment.url);

          $("input[name='fields[" + id + "][image]']").val(attachment.url);
        });
        // Open the uploader dialog
        mediaUploader.open();
      });

      $(".field-type").on("change", function () {
        var formID = $("#form_id");
        var field_id = $(this)
          .parent()
          .parent()
          .parent()
          .parent()
          .data("field-id");
        var field_type = $(this).children("select").val();
        var ui_field = $(".config-open");
        var ui_config = $(this).parent().parent().parent().parent();

        var data = {
          id: formID.val(),
          field_id: field_id,
          action: "codex_new_field_" + field_type,
        };

        console.log(data);

        $.post(codex_admin.ajax_url, data, function (res) {
          if (res.success) {
            ui_field.attr("data-field-type", field_type);
            ui_field.html(res.data.preview);
            ui_field.append(res.data.position);
            ui_config.replaceWith(res.data.config);

            // field in panel success
            app.buildLayout();
            app.field_ots();
            app.load_on_action();
            app.config_field(ui_field.children());
          } else {
            console.log(res);
          }
        }).fail(function (xhr, textStatus, e) {
          console.log(xhr.responseText);
        });
      });
    },

    // Function for Drag & Drop & Sort item field
    jQueryui: function () {
      $(".layout-column, .panel-row").sortable({
        connectWith: ".layout-column",
        items: ".field-row",
        cursor: "move",
        placeholder: "field-highlight",

        // dropped event
        stop: function (e, ui) {
          ui.item.removeAttr("style");
          app.buildLayout();
          app.field_ots();
        },

        receive: function (event, ui) {
          var formID = $("#form_id");
          var field_id = Math.floor(Math.random() * 900000) + 100000;
          var ui_field = ui.item;

          if (ui.helper != null) {
            ui_field = ui.helper;
          }

          // Check field no id, true is load layout field
          if (!ui_field.attr("data-field-id")) {
            var field_type = ui_field.data("field-type");

            var data = {
              id: formID.val(),
              field_id: field_id,
              action: "codex_new_field_" + field_type,
            };

            $.post(codex_admin.ajax_url, data, function (res) {
              if (res.success) {
                ui_field.attr("data-field-id", field_id);
                ui_field.html(res.data.preview);
                ui_field.append(res.data.position);
                $(".config-fields").append(res.data.config);

                // field in panel success
                app.buildLayout();
                app.field_ots();
                app.load_on_action();
              } else {
                console.log(res);
              }
            }).fail(function (xhr, textStatus, e) {
              console.log(xhr.responseText);
            });
          } else {
            app.buildLayout();
            app.field_ots();
            app.load_on_action();
          }
        },
      });

      $(".field-item").draggable({
        connectToSortable: ".layout-column",
        cursor: "grabbing",
        // revert: "invalid",
        placeholder: true,

        start: function (e, ui) {
          ui.helper
            .addClass("ui segment")
            .css("width", "500px")
            .css("font-size", "20px")
            .css("text-align", "center");
        },

        helper: function () {
          var $this = $(this),
            type = $this.data("field-type"),
            $el = $('<div class="field-row"></div>'),
            text = $this.html();

          return $el
            .html(text)
            .css("height", "70px")
            .attr("data-field-type", type);
        },

        // cancel: false,
        containment: "document",
      });
    },

    // Disable or Enable field one time use
    field_ots: function () {
      var template = $('select[name*="setting[template]"]').val();

      if (template == "login") {
        template = codex_admin.field_login;
      } else if (template == "register") {
        template = codex_admin.field_register;
      } else {
        return;
      }

      // foreach field type
      $.each(template, function (name, field) {
        var field_in_panel = $(".layout-column").find(
          '*[data-field-type="' + field.type + '"]'
        );

        var field_type = $(".item").find(
          '*[data-field-type="' + field.type + '"]'
        );

        if (field_in_panel.length > 0) {
          field_type.draggable("disable");
        } else {
          field_type.draggable("enable");
        }
      });
    },

    load_on_action: function () {
      // load rating star
      $(".codex-rating").rating("setting", "clearable", true);

      // set element to dropdown and checkbox
      $(".ui.dropdown").dropdown();
      $(".ui.checkbox").checkbox();
    },

    addRow_panel: function () {
      $(".layout-panel").append(codex_admin.element_addRow);

      app.buildLayout();
      app.jQueryui();
    },

    deleteRow_panel: function (el) {
      var row = $(el).closest(".layout-row"),
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
        app.buildLayout();
        app.field_ots();
      });
    },

    // Function for show tools panel
    showTool_panel: function (el) {
      var setrow = $(el);
      setrow
        .children()
        .children()
        .first()
        .append(
          '<div class="delete-row tools-column"><i class="dashicons dashicons-remove"></i></div>'
        );

      setrow
        .children()
        .children()
        .not(":first")
        .prepend(
          '<div class="join-column tools-column"><i class="dashicons dashicons-leftright"></i></div>'
        );

      setrow
        .children()
        .children()
        .each(function (k, v) {
          var column = $(v);
          var width = column.width() / 2 - 5;
          if (!column.parent().hasClass("col-1")) {
            column.prepend(
              '<div class="split-column tools-column"><i class="dashicons dashicons-image-flip-horizontal"></i></div>'
            );
            column.find(".split-column").css("left", width);
          }
        });
    },

    // Function for split column one to two
    splitColumn_panel: function (el) {
      var column = $(el).parent().parent(),
        size = column.attr("class").split("-"),
        newcol = $("<div>").insertAfter(column);

      var left = Math.ceil(size[1] / 2),
        right = Math.floor(size[1] / 2);

      size[1] = left;

      column.attr("class", size.join("-"));

      size[1] = right;

      newcol.addClass(size.join("-")).append('<div class="layout-column">');

      $(el).remove();

      $(".tools-column").remove();

      app.jQueryui();
      app.buildLayout();
    },

    // Function for join column two to one
    joinColumn_panel: function (el) {
      var column = $(el).parent().parent();

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

      app.buildLayout();

      $(".tools-column").remove();
    },

    // Function for show tools field config & delete
    showTool_field: function (el) {
      $(el).append(codex_admin.config_field);
      $(el).append(codex_admin.delete_field);
    },

    // Function for open tab config field
    config_field: function (el) {
      var id = $(el).parent().data("field-id");

      var field = $(el).parent();

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
    },

    // Function for save form
    save_form: function () {
      var formName = $("#form_name");
      var formID = $("#form_id");
      var formNonce = $("#nonce");
      var formStatus = $("#form_status");

      $(".save_form").html(
        "<strong class='saving'>Saving<span>.</span><span>.</span><span>.</span></strong>"
      );

      var data = {
        title: formName.val(),
        id: formID.val(),
        nonce: formNonce.val(),
        status: formStatus.val(),
        setting: JSON.stringify($("#setting_form").serializeArray()),
        data: JSON.stringify($("#panel").serializeArray()),
        action: "save_form",
      };

      $.post(codex_admin.ajax_url, data, function (res) {
        if (res.success) {
          $(".save_form").html("<strong>Saved</strong>");

          // ???????????????????????? 5?????????????????? ?????????????????????????????????????????? Save
          setTimeout(function () {
            $(".save_form").html("<strong>Save</strong>");
          }, 5000);
        } else {
          $(".save_form").html("<strong>Save is not successful</strong>");
        }
      }).fail(function (xhr, textStatus, e) {
        console.log(xhr.responseText);
      });
    },

    // Function for build-layout panel
    buildLayout: function () {
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

      // ???????????????????????????????????? form ???????????????????????????
      app.save_form();
    },

    //
    // For realtime config field
    //

    // Function for realtime change label
    fieldconfig_label: function (el) {
      var id = el.parent().parent().parent().parent().parent().data("field-id");
      var val = el.val();
      $("label[id=" + id + "]").text(val);
    },

    // Function for realtime change placeholder
    fieldconfig_placeholder: function (el) {
      var id = el.parent().parent().parent().parent().parent().data("field-id");
      var val = el.val();
      $("input[id=" + id + "]").attr("placeholder", val);
    },

    // Function for realtime change text button
    fieldconfig_text_button: function (el) {
      var id = el.parent().parent().parent().parent().parent().data("field-id");
      var val = el.val();
      $("button[id=" + id + "]").text(val);
    },

    // Function for realtime change align button
    fieldconfig_align_button: function (el) {
      var id = el.parent().parent().parent().parent().data("field-id");
      var val = el.children().val();
      $("button[id=" + id + "]")
        .parent()
        .attr("style", "text-align:" + val);
    },

    //
    // For config fields
    //

    // Function for add option field select
    fieldSelectOptionAdd: function (event, el) {
      event.preventDefault();

      var $this = $(el),
        $parent = $this.parent(),
        $id = $parent.parent().parent().parent().parent().data("field-id"),
        next_id = $(".config_field_" + $id).find(
          "input[name='fields[" + $id + "][next_option_id]']"
        ),
        next_id_val = next_id.val();
      $choice = $parent.clone().insertAfter($parent);

      $choice
        .find("input[type=text]")
        .val("")
        .attr("name", "fields[" + $id + "][options][" + next_id_val + "]");

      next_id_val++;
      next_id.val(next_id_val);
    },

    // Function for remove option field select
    fieldSelectOptionRemove: function (event, el) {
      event.preventDefault();

      var $this = $(el),
        $parent = $this.parent(),
        total = $parent.parent().find(".ui").length;
      if (confirm(codex_admin.confirm_remove_option)) {
        if (total === 1) {
          alert(codex_admin.confirm_remove_option_alert);
        } else {
          $parent.remove();
        }
      }
    },
  };

  app.init();
})(jQuery);
