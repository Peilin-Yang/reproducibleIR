function get_all_performances_of_query(query_tag) {
  $.blockUI({ message: "getting the performances..." });
  var qtag = $( "#query_select option:selected" ).val();
  $.getJSON('/api/play/get_performances_nouser.php', { query_tag: qtag })
    .done(function(data) {
      if (data['status'] == 200) {
        console.log(data);
      } else {
        toastr.error('Failed to get performances:'+data['reason']);
      }
    })
    .fail(function() {
        toastr.error('Failed to get performances for unknown reason!');
    })
    .always(function() {
      $.unblockUI();
  });
}

function update_query_select(data) {
  $.each(data, function(i, obj) {
    var replacements = {
      "%QUERY_TAG%":obj.query_tag,
      "%QUERY_NAME%":obj.name
    },
    option = 
    '<option value="%QUERY_TAG%">%QUERY_NAME%</option>';

    option = option.replace(/%\w+%/g, function(all) {
       return replacements[all] || "NULL";
    });
    //console.log(table_row);
    $('#query_select').append(option);
  });
}

function get_query_list() {
  $.blockUI({ message: "getting the query list..." });
  $.getJSON('/api/play/get_query_list_nouser.php')
    .done(function(data) {
      //console.log(data);
      if (data['status'] == 200) {
        update_query_select(data['data']);
        get_all_performances_of_query();
      } else {
        toastr.error('Failed to get query list:'+data['reason']);
      }
    })
    .fail(function() {
        toastr.error('Failed to get query list for unknown reason!');
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

  get_query_list();
});