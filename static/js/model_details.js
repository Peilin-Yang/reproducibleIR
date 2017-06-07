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
          toastr.success('Successfully updated model!');
          setTimeout(function() {
            window.location.href = '/play/';
          }, 500);
        } else {
          toastr.error('Failed to update model:'+data['reason']);
        }
      })
      .fail(function() {
        toastr.error('Failed to update model for unknown reason!');
      })
      .always(function() {
        $('#submit_model').find("i").remove();
        $('#submit_model').prop("disabled", false);
      }, "json");
  });
}

function show_evaluations() {
  var evaluation_list = '<ul>';
  $.each($("#evaluate_select option:selected"), function(i, obj) {
    var option_idx = i+1;
    evaluation_list += '<li>'+option_idx+'：'+obj.text+'</li>';
  });
  evaluation_list += '</ul>';
  return evaluation_list;
}

function reg_evaluate_btn() {
  $('#evaluate_btn').on('click', function (e) {
    var evaluations_summary = show_evaluations();
    $("#evaluate-modal-body").html(evaluations_summary);
    $('#confirmModal').modal('show'); 
  });
}

function reg_confirm_evaluate() {
  $('#confirm-evaluate').on('click', function (e) {
    $(this).prepend('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>');
    $(this).prop("disabled", true);
    var cur_uid = $("#cur_uid").text();
    var cur_apikey = $("#cur_apikey").text();
    var model_id = $("#cur_mid").text();
    var evaluation_list = [];
    $("#evaluate_select option:selected").each(function()
    {
      evaluation_list.push($(this).val());
    });
    query_list = evaluation_list.join(',');
    $.post('/api/play/evaluate_model.php', { 
        uid: cur_uid, 
        apikey: cur_apikey, 
        mid: model_id,
        query_list: query_list,
        pertube_type: 0, 
        pertube_paras_str: ""
      })
      .done(function(data) {
        //console.log(data);
        if (data['status'] == 200) {
          toastr.success('Successfully submitted the evaluation!');
          setTimeout(function() {
            location.reload();
          }, 500);
        } else {
          toastr.error('Failed to submit the evaluation: '+data['reason']);
        }
      })
      .fail(function() {
        toastr.error('Failed to submit the evaluation for unknown reasons!');
      })
      .always(function() {
        $("#confirmModal").modal('hide');
        $('#confirm-evaluate').find("i").remove();
        $('#confirm-evaluate').prop("disabled", false);
      }, "json");
  });
}

function fillin_form(data) {
  $("#mname").val(data['mname']);
  $("#mpara").val(data['mpara']);
  $("#mnotes").val(data['mnotes']);
  var $editor = $('#editor');
  //console.log($editor);
  g_editor.setValue(data['mbody']);
}


function update_query_multiselect(data) {
  $.each(data, function(i, obj) {
    var replacements = {
      "%QUERY_TAG%":obj.query_tag,
      "%QUERY_NAME%":obj.name,
      "%DISABLED%": obj.evaluate_status < 0 ? "disabled" : ""
    },
    table_row = 
    '<option value="%QUERY_TAG%" %DISABLED%>%QUERY_NAME%</option>';

    table_row = table_row.replace(/%\w+%/g, function(all) {
       return replacements[all] || "NULL";
    });
    //console.log(table_row);
    $('#evaluate_select').append(table_row);
  });
}

function fill_query_info(data) {
  var qinfo = '<br> \
      <div class="row"> \
        <div class="col-sm-1 col-md-1 col-lg-1 bg-primary"> \
          Query \
        </div> \
        <div class="col-sm-3 col-md-3 col-lg-3 bg-primary"> \
          Query Notes\
        </div> \
        <div class="col-sm-1 col-md-1 col-lg-1 bg-primary"> \
          Index \
        </div> \
        <div class="col-sm-3 col-md-3 col-lg-3 bg-primary"> \
          Index Notes \
        </div> \
        <div class="col-sm-4 col-md-4 col-lg-4 bg-primary"> \
          Index Stats \
        </div> \
      </div>';
  var stats = {};
  $.each(data, function(i, obj) {
    var replacements = {
      "%IDX%": i+1,
      "%QUERY_NAME%":obj.name,
      "%QUERY_NOTES%": obj.qnotes,
      "%INDEX_NAME%": obj.iname,
      "%INDEX_NOTES%": obj.inotes,
      "%INDEX_STATS%": obj.stats
    };
    // console.log(replacements);
    stats[i+1] = obj.stats;
    var tmp = '<br> \
      <div class="row"> \
        <div class="col-sm-1 col-md-1 col-lg-1"> \
          %QUERY_NAME% \
        </div> \
        <div class="col-sm-3 col-md-3 col-lg-3"> \
          %QUERY_NOTES% \
        </div> \
        <div class="col-sm-1 col-md-1 col-lg-1"> \
          %INDEX_NAME% \
        </div> \
        <div class="col-sm-3 col-md-3 col-lg-3"> \
          %INDEX_NOTES% \
        </div> \
        <div id="qinfo_%IDX%" class="col-sm-4 col-md-4 col-lg-4"> \
          %INDEX_STATS% \
        </div> \
      </div>';
    tmp = tmp.replace(/%\w+%/g, function(all) {
       return replacements[all] || "NULL";
    });
    qinfo += tmp;
  });
  $("#qinfo_body").html(qinfo);
  for (var i = 1; i < data.length+1; i++) {
    $("#qinfo_"+i).JSONView(stats[i], { collapsed: true });
  }
}

function get_queries() {
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  var model_id = $("#cur_mid").text();
  $.getJSON('/api/play/get_query_list_full.php', { 
    uid: cur_uid, 
    apikey: cur_apikey, 
    mid: model_id
   })
    .done(function(data) {
      //console.log(data);
      if (data['status'] == 200) {
        update_query_multiselect(data['data']);
        $("#evaluate_select").multiselect();
        reg_confirm_evaluate();
        fill_query_info(data['data']);
      } else {
        toastr.error('Failed to get query list:'+data['reason']);
      }
    })
    .fail(function() {
        toastr.error('Failed to get query list for unknown reason!');
    })
    .always(function() {
  });  
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
    $("#evaluate_btn").attr("disabled", true);
    $("#evaluate_select").hide();
    $("#query_info").hide();
  } else if (data['compile_status'] == 0) {
    status_str = "Compile Successed";
    status_class = "compile-status compile-success";
    $("#evaluate_btn").attr("disabled", false);
    get_queries();
  } else if (data['compile_status'] == 1) {
    status_str = "Compile Failed";
    status_class = "compile-status compile-fail";
    $("#compile_msg").addClass("alert alert-danger");
    $("#evaluate_btn").attr("disabled", true);
    $("#evaluate_select").hide();
    $("#query_info").hide();
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
        toastr.error('Failed to get model details:'+data['reason']);
      }
    })
    .fail(function() {
      toastr.error('Failed to add get model details for unknown reasons!');
    })
    .always(function() {
      $.unblockUI();
      $('p#waiting-span').hide();
  });
}


function get_model_evaluation_details() {
  $.blockUI({ message: "getting model evaluation details..." });
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  var cur_model_id = $("#cur_mid").text();
  $.getJSON('/api/play/get_model_evaluation_details.php', { uid: cur_uid, apikey: cur_apikey, mid: cur_model_id, pertube_type: 0 })
    .done(function(data) {
      //console.log(data);
      if (data['status'] == 200) {
        add_evaluation_table(data['data']);
      } else {
        toastr.error('Failed to get model evaluation details:'+data['reason']);
      }
    })
    .fail(function() {
        toastr.error('Failed to add get model evaluation details for unknown reasons!');
    })
    .always(function() {
      $.unblockUI();
      $('p#waiting-span').hide();
  });
}

function add_evaluation_table(_data) {
  $.each(_data, function(i, obj) {
    var status_str = "";
    var status_class = "";
    var msg = "";
    if (obj.evaluate_status == -1) {
      status_str = "Waiting For Evaluation";
      status_class = "alert alert-info";
    } else if (obj.evaluate_status == -2) {
      status_str = "Being Evaluated";
      status_class = "alert alert-info";
    } else if (obj.evaluate_status == 0) {
      status_str = "Evaluation Successed";
      status_class = "alert alert-success";
    } else if (obj.evaluate_status == 1) {
      status_str = "Evaluation Failed";
      status_class = "alert alert-danger";
      msg = obj.evaluate_msg;
    } 
    var replacements = {
      "%ID%":obj.id,
      "%MODEL_NAME%":obj.mname,
      "%QUERY_NAME%":obj.name,
      "%LAST_EVALUATED%":moment.utc(obj.evaluated_dt, "YYYY-MM-DD HH:mm:ss").local(),
      "%EVALUATE_STATUS%":status_str,
      "%STATUS_CLASS%":status_class,
      "%MSG%":msg
    },
    table_row = 
    '<tr id="%ID%"> \
      <td>%MODEL_NAME%</td> \
      <td>%QUERY_NAME%</td> \
      <td data-dateformat="YYYY-MM-DD HH:mm:ss" data-value="%LAST_EVALUATED%">%LAST_EVALUATED%</td> \
      <td class="%STATUS_CLASS%">%EVALUATE_STATUS%</td> \
      <td id="MSG_%ID%">%MSG%</td> \
    </tr>';

    table_row = table_row.replace(/%\w+%/g, function(all) {
       return replacements[all] || "NULL";
    });
    //console.log(table_row);
    $('table#evaluation-list-table').append(table_row);
    if (obj.evaluate_status === "0") {
      $("#MSG_"+obj.id).JSONView(obj.performances, { collapsed: true });
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

  get_model_details();

  get_model_evaluation_details();

  init_ace_editor();
  init_markdown();
  init_mathjax();
  get_instruction();

  reg_post();
  reg_evaluate_btn();

  $('#editor').height($(window).height()*0.6);

  var json = {"hey": "guy","anumber": 243,"anobject": {"whoa": "nuts","anarray": [1,2,"thr<h1>ee"], "more":"stuff"},"awesome": true,"bogus": false,"meaning": null, "japanese":"明日がある。", "link": "http://jsonview.com", "notLink": "http://jsonview.com is great"};
  $("#json-collasped").JSONView(json, { collapsed: true });
});