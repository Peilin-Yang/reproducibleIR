var g_index_dict = {};

function reg_post() {
  $('#submit_query').on('click', function (e) {
    e.preventDefault();
    $(this).prepend('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>');
    $(this).prop("disabled", true);

    $('input#uid').val($('#cur_uid').html());
    $('input#apikey').val($('#cur_apikey').html());
    $.post($('#fform').attr('action'), $('#fform').serialize(), function() {})
      .done(function(data) {
        //console.log(data);
        if (data['status'] == 200) {
          toastr.success('Successfully added query!');
          setTimeout(function() {
            window.location.href = '/admin/add_query_path.php';
          }, 500);
        } else {
          toastr.error('Failed to add query:'+data['reason']);
        }
      })
      .fail(function() {
        toastr.error('Failed to add query for unknown reason!');
      })
      .always(function() {
        $('#submit_query').find("i").remove();
        $('#submit_query').prop("disabled", false);
      }, "json");
  });
}

function update_query_table(data) {
  if (data.length == 0) {
    $("#query-list-table").hide();
  } else {
    $.each(data, function(i, obj) {
      var replacements = {
        "%ID%":obj.query_tag,
        "%UID%":obj.uid,
        "%INDEX%":g_index_dict[obj.index_id],
        "%NAME%":obj.name,
        "%QUERY_PATH%":obj.query_path,
        "%EVALUATION_PATH%":obj.evaluation_path,
        "%ADDED_AT%":moment.utc(obj.add_dt, "YYYY-MM-DD HH:mm:ss").local(),
        "%NOTES%":obj.notes
      },
      table_row = 
      '<tr id="%ID%"> \
        <td><a href="update_query_path.php?querytag=%ID%">%NAME%</a></td> \
        <td>%INDEX%</td> \
        <td>%QUERY_PATH%</td> \
        <td>%EVALUATION_PATH%</td> \
        <td data-dateformat="YYYY-MM-DD HH:mm:ss" data-value="%ADDED_AT%">%ADDED_AT%</td> \
      </tr>';

      table_row = table_row.replace(/%\w+%/g, function(all) {
         return replacements[all] || "NULL";
      });
      //console.log(table_row);
      $('table#query-list-table').append(table_row);
    });
  }
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

function get_query_list() {
  $.blockUI({ message: "getting existing query list..." });
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  $.getJSON('/api/play/get_query_list.php', { uid: cur_uid, apikey: cur_apikey })
    .done(function(data) {
      //console.log(data);
      if (data['status'] == 200) {
        update_query_table(data['data']);
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
  get_query_list();
  reg_post();
});