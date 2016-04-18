var g_index_dict = {};

function reg_post() {
  $('#submit_query').on('click', function (e) {
    e.preventDefault();
    $(this).prepend('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>');
    $(this).prop("disabled", true);

    $('input#uid').val($('#cur_uid').html());
    $('input#apikey').val($('#cur_apikey').html());
    $('input#querytag').val($('#cur_querytag').html());
    $.post($('#fform').attr('action'), $('#fform').serialize(), function() {})
      .done(function(data) {
        //console.log(data);
        if (data['status'] == 200) {
          toastr.success('Successfully updated query!');
          setTimeout(function() {
            window.location.href = '/admin/add_query_path.php';
          }, 500);
        } else {
          toastr.error('Failed to update query:'+data['reason']);
        }
      })
      .fail(function() {
        toastr.error('Failed to update query for unknown reason!');
      })
      .always(function() {
        $('#submit_query').find("i").remove();
        $('#submit_query').prop("disabled", false);
      }, "json");
  });
}

function update_index_select(data) {
  $.each(data, function(i, obj) {
    g_index_dict[obj.id] = obj.iname;
    var replacements = {
      "%INDEX_ID%":obj.id,
      "%INDEX_NAME%":obj.iname
    },
    table_row = 
    '<option value="%INDEX_ID%">%INDEX_NAME%</option>';

    table_row = table_row.replace(/%\w+%/g, function(all) {
       return replacements[all] || "NULL";
    });
    //console.log(table_row);
    $('#index_id').append(table_row);
  });
}

function get_index_list() {
  $.blockUI({ message: "getting existing index list..." });
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  $.getJSON('/api/play/get_index_list.php', { uid: cur_uid, apikey: cur_apikey })
    .done(function(data) {
      //console.log(data);
      if (data['status'] == 200) { 
        update_index_select(data['data']);
      } else {
        toastr.error('Failed to get index list:'+data['reason']);
      }
    })
    .fail(function() {
        toastr.error('Failed to get index list for unknown reason!');
    })
    .always(function() {
      $.unblockUI();
  });
}

function fillin_form(data) {
  $("input#name").val(data['name']);
  $("#index_id").val(data['index_id']);
  $("#query_path").val(data['query_path']);
  $("#evaluation_path").val(data['evaluation_path']);
  $("input#notes").val(data['notes']);
}

function get_query_details() {
  $.blockUI({ message: "getting index details..." });
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  var cur_querytag = $("#cur_querytag").text();
  $.getJSON('/api/play/get_query_details.php', { uid: cur_uid, apikey: cur_apikey, query_tag: cur_querytag })
    .done(function(data) {
      //console.log(data);
      if (data['status'] == 200) {
        //show_status(data['data']);
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

  get_index_list();
  get_query_details();
  reg_post();
});