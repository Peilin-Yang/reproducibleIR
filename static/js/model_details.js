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
    $('input#mid').val($('#cur_mid').html());
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

function fillin_form(data) {
  $("input#mname").val(data['mname']);
  $("input#mpara").val(data['mpara']);
  $("input#mnotes").val(data['mnotes']);
  var $editor = $('#editor');
  //console.log($editor);
  g_editor.setValue(data['mbody']);
}

function show_status(data) {
  $("#submitted_dt").text(moment.utc(data['submitted_dt'], "YYYY-MM-DD HH:mm:ss").local());
  $("#last_modified_dt").text(moment.utc(data['last_modified_dt'], "YYYY-MM-DD HH:mm:ss").local());
  $("#last_compile_dt").text(moment.utc(data['last_compile_dt'], "YYYY-MM-DD HH:mm:ss").local());
  var status_str = "";
  var status_class = "";
  if (data['compile_status'] == -1) {
    status_str = "Waiting For Compile";
    status_class = "compile-status compile-waiting";
  } else if (data['compile_status'] == 0) {
    status_str = "Compile Successed";
    status_class = "compile-status compile-success";
  } else if (data['compile_status'] == 1) {
    status_str = "Compile Failed";
    status_class = "compile-status compile-fail";
    $("#compile_msg").addClass("alert alert-danger");
  } 
  $("#compile_status").text(status_str);
  $("#compile_status").addClass(status_class);
  $("#compile_msg").html(data['compile_msg'].replace(/[\n\r]/g, '</br>'));
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
        show_status(data['data']);
        fillin_form(data['data']);
      } else {
        
      }
    })
    .fail(function() {
      
    })
    .always(function() {
      $.unblockUI();
      $('p#waiting-span').hide();
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

  init_ace_editor();
  init_markdown();
  init_mathjax();
  get_model_details();
  get_instruction();
  reg_post();

  $('#editor').height($(window).height()*0.6);
});