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

function update_index_table(data) {
  if (data.length == 0) {
    $("#index-list-table").hide();
  } else {
    $.each(_data, function(i, obj) {
      var status_str = "";
      var status_class = "";
      if (obj.compile_status == -1) {
        status_str = "Waiting For Compile";
        status_class = "alert alert-info";
      } else if (obj.compile_status == 0) {
        status_str = "Compile Successed";
        status_class = "alert alert-success";
      } else if (obj.compile_status == 1) {
        status_str = "Compile Failed";
        status_class = "alert alert-danger";
      } 
      var replacements = {
        "%ID%":obj.mid,
        "%NAME%":obj.mname,
        "%COMPILE_STATUS%":status_str,
        "%STATUS_CLASS%":status_class,
        "%CREATED_AT%":moment.utc(obj.submitted_dt, "YYYY-MM-DD HH:mm:ss").local(),
        "%LAST_MODIFIED%":moment.utc(obj.last_modified_dt, "YYYY-MM-DD HH:mm:ss").local(),
        "%LAST_COMPILED%":moment.utc(obj.last_compile_dt, "YYYY-MM-DD HH:mm:ss").local(),
        "%INDEX%":i
      },
      table_row = 
      '<tr id="%ID%"> \
        <td><a href="model_details.php?mid=%ID%">%NAME%</a></td> \
        <td data-value="%COMPILE_STATUS%" class="%STATUS_CLASS%">%COMPILE_STATUS%</td> \
        <td data-dateformat="YYYY-MM-DD HH:mm:ss" data-value="%CREATED_AT%">%CREATED_AT%</td> \
        <td data-dateformat="YYYY-MM-DD HH:mm:ss" data-value="%LAST_MODIFIED%">%LAST_MODIFIED%</td> \
        <td data-dateformat="YYYY-MM-DD HH:mm:ss" data-value="%LAST_COMPILED%">%LAST_COMPILED%</td> \
      </tr>';

      table_row = table_row.replace(/%\w+%/g, function(all) {
         return replacements[all] || "NULL";
      });
      //console.log(table_row);
      $('table#model-list-table').append(table_row);
    });
  }
}

function get_index_list() {
  $.blockUI({ message: "getting existing index list..." });
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  $.getJSON('/api/play/get_index_list.php', { uid: cur_uid, apikey: cur_apikey })
    .done(function(data) {
      //console.log(data);
      if (data['status'] == 200) {
        update_index_table(data['data']);
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
});