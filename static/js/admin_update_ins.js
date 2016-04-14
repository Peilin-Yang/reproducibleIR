var add_mathjax = false;

function reg_form_submission() {
  var $editor = $('#editor');
  $editor.closest('form').submit(function() {
    $('input#uid').val($('#cur_uid').html());
    $('input#apikey').val($('#cur_apikey').html());
  });
}

function reg_post_form() {
  $("form").submit(function(e){
    e.preventDefault();
    $.blockUI({ message: "updating model implementation instruction..." });
    $.post($(this).attr('action'), $(this).serialize(), function() {})
      .done(function(data) {
        //console.log(data);
        if (data['status'] == 200) {
          toastr.success('Successfully updated model implementation instruction');
        } else {
          toastr.error('Failed to update model implementation instruction:'+data['reason']);
        }
      })
      .fail(function() {
        toastr.error('Failed to update model implementation instruction for unknown reason!');
      })
      .always(function() {
        $.unblockUI();
      }, "json");
  });
}

function get_data() {
    $.blockUI({ message: "getting model implementation instruction..." });
    $("#editor").markdown({
      onShow: function(e){
        var cur_uid = $("#cur_uid").text();
        var cur_apikey = $("#cur_apikey").text();
        $.getJSON('/api/admin/get_instruction.php', { uid: cur_uid, apikey: cur_apikey })
          .done(function(data) {
            //console.log(data);
            if (data['status'] == 200) {
              e.setContent(data['data']['content']);
            } else {
              
            }
          })
          .fail(function() {
            
          })
          .always(function() {
            $.unblockUI();
        });
      },
      onPreviewDone: function(e) {
        MathJax.Hub.Typeset();
      }
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

  init_markdown();
  init_mathjax();
  get_data();
  reg_form_submission();
  reg_post_form();
});