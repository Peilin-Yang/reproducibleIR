
function reg_year_btn_click() {
  $(".year").on("click", function(){
    //alert("year btn clicked!");
    //console.log($(this).attr('id'));
    window.location.href = "judge_list.php?year="+$(this).attr('id');
  });
}

function show_year(_data) {
  $.each(_data, function(i, obj) {
    y = 
    '<div class="row"> \
      <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5"> \
        <button type="button" id="'+obj['year']+'" class="btn btn-primary btn-lg btn-block year">'+obj['year']+'</button> \
      </div> \
    </div> <hr/>';

    //console.log(table_row);
    $('#year').append(y);
  });
}

function async_get_data() {
  $('p#waiting-span').show();
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  var jqxhr = $.getJSON( "/reproducibleIR/api/judge/get_year.php", 
      { 
        uid: cur_uid, 
        apikey:cur_apikey
      })
      .done(function(data) {
        if (data.status == 200) {
          show_year(data.data);
          reg_year_btn_click();
        } else {
          toastr.error('Error Loading Data: Code-'+data.status+' Reason-'+data.reason);
        }
      })
      .fail(function(data) {
        toastr.error('Error Loading Data, Please Contact the Admin!');
      })
      .always(function(data) {
        //console.log(data);
        $('p#waiting-span').hide();
    });
}

function scrollToTop() {
  verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
  element = $('body');
  offset = element.offset();
  offsetTop = offset.top;
  $('html, body').animate({scrollTop: offsetTop}, 500, 'linear');
}

$(document).ready(function() {
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "progressBar": false,
    "positionClass": "toast-bottom-left",
    "onclick": null,
    "showDuration": "500",
    "hideDuration": "500",
    "timeOut": "3000",
    "extendedTimeOut": "500",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  };

  async_get_data();

  $('.scroll-top-wrapper').on('click', scrollToTop);
});