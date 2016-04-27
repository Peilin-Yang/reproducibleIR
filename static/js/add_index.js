function reg_post() {
  $('#submit_index').on('click', function (e) {
    e.preventDefault();
    $(this).prepend('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>');
    $(this).prop("disabled", true);

    $('input#uid').val($('#cur_uid').html());
    $('input#apikey').val($('#cur_apikey').html());
    $.post($('#fform').attr('action'), $('#fform').serialize(), function() {})
      .done(function(data) {
        //console.log(data);
        if (data['status'] == 200) {
          toastr.success('Successfully added index!');
          setTimeout(function() {
            window.location.href = '/admin/add_index_path.php';
          }, 500);
        } else {
          toastr.error('Failed to add index:'+data['reason']);
        }
      })
      .fail(function() {
        toastr.error('Failed to add index for unknown reason!');
      })
      .always(function() {
        $('#submit_index').find("i").remove();
        $('#submit_index').prop("disabled", false);
      }, "json");
  });
}

function reg_evaluate() {
  $('#evaluate_btn').on('click', function (e) {
    e.preventDefault();
    $(this).prepend('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>');
    $(this).prop("disabled", true);

    $('input#uid').val($('#cur_uid').html());
    $('input#apikey').val($('#cur_apikey').html());
    $.post($('#fform').attr('action'), $('#fform').serialize(), function() {})
      .done(function(data) {
        //console.log(data);
        if (data['status'] == 200) {
          toastr.success('Successfully added index!');
          setTimeout(function() {
            window.location.href = '/admin/add_index_path.php';
          }, 500);
        } else {
          toastr.error('Failed to add index:'+data['reason']);
        }
      })
      .fail(function() {
        toastr.error('Failed to add index for unknown reason!');
      })
      .always(function() {
        $('#submit_index').find("i").remove();
        $('#submit_index').prop("disabled", false);
      }, "json");
  });
}

function update_index_table(data) {
  if (data.length == 0) {
    $("#index-list-table").hide();
  } else {
    $.each(data, function(i, obj) {
      var replacements = {
        "%ID%":obj.id,
        "%UID%":obj.uid,
        "%NAME%":obj.iname,
        "%PATH%":obj.path,
        "%ADDED_AT%":moment.utc(obj.add_dt, "YYYY-MM-DD HH:mm:ss").local(),
        "%NOTES%":obj.notes
      },
      table_row = 
      '<tr id="%ID%"> \
        <td><a href="update_index_path.php?iid=%ID%">%NAME%</a></td> \
        <td>%PATH%</td> \
        <td data-dateformat="YYYY-MM-DD HH:mm:ss" data-value="%ADDED_AT%">%ADDED_AT%</td> \
      </tr>';

      table_row = table_row.replace(/%\w+%/g, function(all) {
         return replacements[all] || "NULL";
      });
      //console.log(table_row);
      $('table#index-list-table').append(table_row);
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
  reg_post();
});