function reg_post() {
  $('#submit_index').on('click', function (e) {
    e.preventDefault();
    $(this).prepend('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>');
    $(this).prop("disabled", true);

    $('input#uid').val($('#cur_uid').html());
    $('input#apikey').val($('#cur_apikey').html());
    $('input#iid').val($('#cur_iid').html());
    $.post($('#fform').attr('action'), $('#fform').serialize(), function() {})
      .done(function(data) {
        //console.log(data);
        if (data['status'] == 200) {
          toastr.success('Successfully updated the index!');
          setTimeout(function() {
            window.location.href = '/admin/add_index_path.php';
          }, 500);
        } else {
          toastr.error('Failed to update the index:'+data['reason']);
        }
      })
      .fail(function() {
        toastr.error('Failed to update the index for unknown reason!');
      })
      .always(function() {
        $('#submit_index').find("i").remove();
        $('#submit_index').prop("disabled", false);
      }, "json");
  });
}

function fillin_form(data) {
  $("input#name").val(data['iname']);
  $("#index_path").val(data['path']);
  $("input#notes").val(data['notes']);
}

function get_index_details() {
  $.blockUI({ message: "getting index details..." });
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  var cur_index_id = $("#cur_iid").text();
  $.getJSON('/api/play/get_index_details.php', { uid: cur_uid, apikey: cur_apikey, iid: cur_index_id })
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

  get_index_details();
  reg_post();
});