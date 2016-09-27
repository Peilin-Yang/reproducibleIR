var g_editor;

function get_instruction() {
  $.blockUI({ message: "getting model implementation instruction..." });
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  $.getJSON('/api/admin/get_instruction.php', { uid: cur_uid, apikey: cur_apikey })
    .done(function(data) {
      //console.log(data);
      if (data['status'] == 200) {
        $('#instruction').html(marked(data['data']['content']));
        MathJax.Hub.Typeset();
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

function init_mathjax() {
  MathJax.Hub.Config({
    tex2jax: {
        inlineMath: [['$','$'], ['\\(','\\)']],
        skipTags: ['script', 'noscript', 'style', 'textarea', 'pre'] // removed 'code' entry
    }
  });
  MathJax.Hub.Queue(function() {
      var all = MathJax.Hub.getAllJax(), i;
      for(i = 0; i < all.length; i += 1) {
          all[i].SourceElement().parentNode.className += ' has-jax';
      }
  });
}

function init_ace_editor() {
  g_editor = ace.edit("editor");
  g_editor.setTheme("ace/theme/iplastic");
  g_editor.getSession().setMode("ace/mode/c_cpp");
  var $editor = $('#editor');
  $editor.closest('form').submit(function() {
    var code = editor.getSession().getValue();
    $editor.prev('input[type=hidden]').val(code);
  });
}

function reg_post() {
  $('#submit_model').on('click', function (e) {
    e.preventDefault();
    $(this).prepend('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>');
    $(this).prop("disabled", true);

    var $editor = $('#editor');
    var code = g_editor.getSession().getValue();
    $editor.prev('input[type=hidden]').val(code);
    $('input#uid').val($('#cur_uid').html());
    $('input#apikey').val($('#cur_apikey').html());
    $.post($('#fform').attr('action'), $('#fform').serialize(), function() {})
      .done(function(data) {
        //console.log(data);
        if (data['status'] == 200) {
          toastr.success('Successfully added model!');
          setTimeout(function() {
            window.location.href = '/play/';
          }, 500);
        } else {
          toastr.error('Failed to add model:'+data['reason']);
        }
      })
      .fail(function() {
        toastr.error('Failed to add model for unknown reason!');
      })
      .always(function() {
        $('#submit_model').find("i").remove();
        $('#submit_model').prop("disabled", false);
      }, "json");
  });
}

function copy_model(model_data) {
  $("#mname").val(model_data['mname']);
  $("#mpara").val(model_data['mpara']);
  $("#mnotes").val(model_data['mnotes']);
  g_editor.setValue(model_data['mbody']);  
}

function get_function_implementation(_mid) {
  $.blockUI({ message: "getting other model..." });
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  $.getJSON('/api/play/get_model_details.php', { uid: cur_uid, apikey: cur_apikey, mid: _mid })
    .done(function(data) {
      //console.log(data);
      if (data['status'] == 200) {
        copy_model(data['data']);
      } else {
        toastr.error('Failed to get other model:'+data['reason']);
      }
    })
    .fail(function() {
      toastr.error('Failed to get other model for unknown reasons!');
    })
    .always(function() {
      $.unblockUI();
      $('p#waiting-span').hide();
  });
}

function copyfrom() {
  var copyfrom_mid = $('#cur_copyfrom').html();
  if (copyfrom_mid!=="") {
    get_function_implementation(copyfrom_mid);
  }
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
  reg_toggle_menu();
  init_ace_editor();
  init_markdown();
  init_mathjax();
  get_instruction();
  reg_post();
  copyfrom();

  $('#editor').height($(window).height()*0.6);
});