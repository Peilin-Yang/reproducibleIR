var g_editor;

function show_model_code(data) {
  $("#mname").html(data['mname']);
  $("#mpara").html(data['mpara']);
  $("#mnotes").html(data['mnotes']);
  g_editor.setValue(data['mbody']);
}

function get_model_details() {
  $.blockUI({ message: "getting model details..." });
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  var cur_model_id = $("#cur_mid").text();
  $.getJSON('/api/play/get_model_details.php', { uid: cur_uid, apikey: cur_apikey, mid: cur_model_id })
    .done(function(data) {
      //console.log(data);
      if (data['status'] == 200) {
        show_model_code(data['data']);
      } else {
        toastr.error('Failed to get model details:'+data['reason']);
      }
    })
    .fail(function() {
      toastr.error('Failed to get model details for unknown reasons!');
    })
    .always(function() {
      $.unblockUI();
      $('p#waiting-span').hide();
  });
}

function init_ace_editor() {
  g_editor = ace.edit("editor");
  g_editor.setTheme("ace/theme/iplastic");
  g_editor.getSession().setMode("ace/mode/c_cpp");
  g_editor.setReadOnly(true);
  g_editor.setOptions({
    maxLines: Infinity
  });
}

function reg_copy_create_btn() {
  $('#copy_create_btn').on('click', function (e) {
    e.preventDefault();
    var mid = $('#cur_mid').html();
    window.location.href = '/play/add_model.php?copyfrom='+mid;
  });
}

$(document).ready(function() {
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "progressBar": false,
    "positionClass": "toast-bottom-full-width",
    "onclick": null,
    "showDuration": "500",
    "hideDuration": "500",
    "timeOut": "5000",
    "extendedTimeOut": "500",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  };

  reg_copy_create_btn();
  init_ace_editor();
  get_model_details();
});