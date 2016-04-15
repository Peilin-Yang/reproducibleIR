function add_data_to_table(_data) {
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

function async_get_data(sort_field, sort_order, _start, _end) {
  $('p#waiting-span').show();
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  var jqxhr = $.getJSON( "/api/play/get_model_list.php", 
      { 
        uid: cur_uid, 
        apikey: cur_apikey, 
        request_uid: cur_uid, 
        sort: sort_field, 
        order: sort_order, 
        start: _start, 
        end: _end 
      })
      .done(function(data) {
        if (data.length == 0) {
          isMoreDataAvailable = false;
        }
        //toastr.success('成功获取数据！');
        add_data_to_table(data['data']);
      })
      .fail(function(data) {
        toastr.error('Failed to add model:'+data['reason']);
      })
      .always(function(data) {
        //console.log(data);
        $('p#waiting-span').hide();
        isPreviousLoadComplete = true;
    });
}

function get_more_data() {
  var cur_all_data = $("table#model-list-table").find("tbody").find("tr");
  //console.log(cur_all_data);
  var start = 1, end = 30;
  var cur_data_len = cur_all_data.length;
  var sorted_column = $("table#model-list-table").find("thead").find("tr").find("th.sorted");
  var sort_field;
  var sort_order;
  if (sorted_column.length == 0) {
    sort_field = 0; // cutoff_time
    sort_order = "desc";
  } else {
    var sortkey = sorted_column.attr('data-sortkey');
    if (sortkey === "1-0") {
        sort_field = 2; // title
      } else if (sortkey === "2-0") {
        sort_field = 3; // status
      } else if (sortkey === "3-0") {
        sort_field = 1; // cutoff_time
      } else if (sortkey === "4-0") {
        sort_field = 4; // money amount
      }

    sort_order = sorted_column.hasClass("up") ? "desc" : "asc";
  }

  async_get_data(sort_field, sort_order, cur_data_len+start, cur_data_len+end);
}

function scrollToTop() {
    verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
    element = $('body');
    offset = element.offset();
    offsetTop = offset.top;
    $('html, body').animate({scrollTop: offsetTop}, 500, 'linear');
}

function reg_scroll_callback() {
  $(document).on( "scroll", function (e) {
    //console.log($(document).height()+","+$(window).height()+"+"+$(window).scrollTop()+"="+($(window).height() + $(window).scrollTop()));
    if ($(document).height()-35 <= ($(window).height() + $(window).scrollTop())) {
      if (isPreviousLoadComplete && isMoreDataAvailable){
        isPreviousLoadComplete = false;
        get_more_data();
      } 
    }

    if ($(window).scrollTop() > 100) {
      $('.scroll-top-wrapper').addClass('show');
    } else {
      $('.scroll-top-wrapper').removeClass('show');
    }
  });

  $('.scroll-top-wrapper').on('click', scrollToTop);
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
  reg_scroll_callback();
  async_get_data(0, "desc", 1, 30);
  // for more data
  isPreviousLoadComplete = true; 
  isMoreDataAvailable = true;

});