function get_instruction() {
  $.blockUI({ message: "getting model implementation instruction..." });
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  $.getJSON('/api/admin/get_instruction.php', { uid: cur_uid, apikey: cur_apikey })
    .done(function(data) {
      //console.log(data);
      if (data['status'] == 200) {
        $('#instruction').html(marked(data['data']['content']));
      } else {
        
      }
    })
    .fail(function() {
      
    })
    .always(function() {
      $.unblockUI();
  });
}

function init_markdown() {
  hljs.initHighlightingOnLoad();
  marked.setOptions({
    highlight: function (code) {
      return hljs.highlightAuto(code).value;
    }
  });
}

function init_ace_editor() {
  var editor = ace.edit("editor");
  editor.setTheme("ace/theme/iplastic");
  editor.getSession().setMode("ace/mode/c_cpp");
  var $editor = $('#editor');
  $editor.closest('form').submit(function() {
    var code = editor.getSession().getValue();
    $editor.prev('input[type=hidden]').val(code);
  });
}

$(document).ready(function() {
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "progressBar": false,
    "positionClass": "toast-bottom-left",
    "onclick": null,
    "showDuration": "500",
    "hideDuration": "500",
    "timeOut": "3000",
    "extendedTimeOut": "500",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  };

  init_ace_editor();
  init_markdown();
  get_instruction();

  $('#editor').height($(window).height()*0.6);
});