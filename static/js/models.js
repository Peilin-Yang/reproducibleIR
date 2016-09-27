function add_data_to_table(_data) {
  $.each(_data, function(i, obj) {
    var status_str = "";
    var status_class = ""; 
    var replacements = {
      "%ID%":obj.mid,
      "%USER%":obj.user,
      "%NAME%":obj.mname,
      "%MODELPARA%":obj.mpara,
      "%LAST_MODIFIED%":moment.utc(obj.last_modified_dt, "YYYY-MM-DD HH:mm:ss").local(),
      "%INDEX%":i
    },
    table_row = 
    '<tr id="%ID%"> \
      <td>%USER%</td> \
      <td><a href="model_code.php?mid=%ID%">%NAME%</a></td> \
      <td>%MODELPARA%</td> \
      <td data-dateformat="YYYY-MM-DD HH:mm:ss" data-value="%LAST_MODIFIED%">%LAST_MODIFIED%</td> \
    </tr>';

    table_row = table_row.replace(/%\w+%/g, function(all) {
       return replacements[all] || "";
    });
    //console.log(table_row);
    $('table#model-list-table').append(table_row);
  });
}

function async_get_data(_start, _end) {
  $('p#waiting-span').show();
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  var jqxhr = $.getJSON( "/api/play/get_all_models_list.php", 
      { 
        uid: cur_uid, 
        apikey: cur_apikey, 
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
  async_get_data(cur_data_len+start, cur_data_len+end);
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
  async_get_data(0, "desc", 1, 30);
  // for more data
  isPreviousLoadComplete = true; 
  isMoreDataAvailable = true;
  reg_scroll_callback();
});