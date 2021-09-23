jQuery(function ($) {
  $(".entry-content").find("[disabled]").removeAttr("disabled");

  $(".ui.dropdown").dropdown();

  $(".entry-content").on("submit", "form.codex_forms_form", function (e) {
    e.preventDefault();
    var form_data = $(".codex_forms_form").serializeArray();
    console.log(form_data);
  });
});
