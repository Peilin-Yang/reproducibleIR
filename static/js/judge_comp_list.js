var all_docs;
var all_docs_cnt = 0;
var judged_dict = {};
var judged_docs_cnt = 0;
var total_docs_cnt = 0;
var docs_per_page = 50;
var cur_request_page = 1;

function update_status() {
  $("#count").html("<strong>"+judged_docs_cnt+" / "+total_docs_cnt+" Completed!</strong>");
}

function getParameterByName(name) {
  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
      results = regex.exec(location.search);
  return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function show_pagination() {
  var html = '';
  html += '<li';
  if (cur_request_page == 1) {
    html += ' class="disabled" id="left_page"><a aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>'; 
  } else {
    html += ' id="left_page"><a href="index.php?page='+(cur_request_page-1)+'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>'; 
  }
  for (var i = 0; i != Math.ceil(total_docs_cnt*1.0/docs_per_page); i++) {

    html += '<li'; 
    if (i+1 == cur_request_page) {
      html += ' class="active page"';
    } else {
      html += ' class="page"';
    }
    html += '><a href="index.php?page='+(i+1)+'">'+(i+1)+'</a></li>';
  }
  html += '<li';
  if (cur_request_page == Math.ceil(total_docs_cnt*1.0/docs_per_page)) {
    html += ' class="disabled" id="right_page"><a aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
  } else {
    html += ' id="right_page"><a href="index.php?page='+(cur_request_page+1)+'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
  }

  $(".pagination").html(html); 
  reg_pagination_click();   
}

function reg_pagination_click() {
  var a = $("ul.pagination").find("a");
  $("ul.pagination").find("a").on("click", function(e) {
    e.preventDefault();
    var hide_judged_state = false;
    if ($('input[name="filter-checkbox"]').length) {
      //console.log($('input[name="filter-checkbox"]').bootstrapSwitch("state"));
      hide_judged_state = $('input[name="filter-checkbox"]').bootstrapSwitch("state");
    }
    
    window.location.href = $(this).attr("href")+'&hide_judged_state='+hide_judged_state;
  });
}

function updateUI(docid, judgement) {
  var selected_btn = $('[id="'+docid+'-comp_judge_btn-'+judgement+'"]');
  selected_btn.removeClass("btn-default").addClass("btn-success");
  $('[id^="'+docid+'-comp_judge_btn-"]:not([id="'+docid+'-comp_judge_btn-'+judgement+'"])').removeClass("btn-success").addClass("btn-default");
  $("#panel-"+docid).addClass('panel-judged');
  if ($('input[name="filter-checkbox"]').length) {
    //console.log($('input[name="filter-checkbox"]').bootstrapSwitch("state"));
    var state = $('input[name="filter-checkbox"]').bootstrapSwitch("state");
    if (state) {
      // only show not judged!
      $("#panel-"+docid).hide(200);
    }
  }
}

function update_judgements(all_judgements) {
  $.each(all_judgements, function(i, obj) {
    var judgement = obj['judgement'];
    if (judgement === "-1") {
      judgement = "nan";
    }
    var docid = obj['docid'];
    $("#panel-"+docid).addClass('panel-judged');
    updateUI(docid, judgement);
  });

  judged_docs_cnt = all_judgements.length;
  update_status();
}

function show_docs(all_docs) {
  all_docs_cnt = all_docs.length;
  $.each(all_docs, function(i, obj) {
    y = 
    '<div id="panel-'+obj['docid']+'" class="panel panel-default"> \
      <div class="panel-heading" role="tab" id="'+obj['docid']+'"> \
        <h4 class="panel-title"> \
          <span id="span-'+obj['docid']+'"></span> \
          <a data-toggle="collapse" data-parent="#judge-list" href="#c-'+obj['docid']+'" aria-expanded="true" aria-controls="c-'+obj['docid']+'"> \
            '+obj['title'];
    y += ' \
          </a> \
        </h4> \
      </div> \
      <div id="c-'+obj['docid']+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="'+obj['docid']+'"> \
        <div class="panel-body">';

    y += '<div class="row"> \
      <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5"> \
        <button class="btn btn-default" id="'+obj['docid']+'-comp_judge_btn-1" data-docid="'+obj['docid']+'" data-year="'+obj['year']+'" style="margin-bottom:4px;white-space: normal;text-align: left;">'+obj['description']+'</button> \
      </div> \
      <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="text-align: center;"> \
        <h4><strong>OR</strong></h4> \
      </div> \
      <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5"> \
        <button class="btn btn-default" id="'+obj['docid']+'-comp_judge_btn-0" data-docid="'+obj['docid']+'" data-year="'+obj['year']+'" style="margin-bottom:4px;white-space: normal;text-align: left;">'+obj['yelp_snippet']+'</button> \
      </div> \
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> \
        <button class="btn btn-default btn-block" style="text-align:center;" id="'+obj['docid']+'-comp_judge_btn-nan" data-docid="'+obj['docid']+'" data-year="'+obj['year']+'" style="margin-bottom:4px;white-space: normal;text-align: left;">Hard or Impossible To Decide</button> \
      </div> \
    </div>';

    y += '</div> \
      </div> \
    </div>';

    //console.log(table_row);
    $('#judge-list').append(y);
  });
  $(".official-judgement-span").hide();
}

function reg_judge_btn_click() {
  $('[id*=comp_judge_btn]').on('click', function(event) {
    var docid = $(this).data("docid");
    var year = $(this).data("year");
    var current_sec = $(this).attr("id").split('-')[2];
    
    if (current_sec === "nan") {
      current_sec = "-1";
    }
    set_judgement(docid, year, current_sec);
  });
}

function set_judgement(docid, year, judgement) {
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  var jqxhr = $.post( "/reproducibleIR/api/judge_compare/set_judgement.php", 
    { 
      uid: cur_uid, 
      apikey:cur_apikey,
      docid: docid,
      year: year,
      judgement:judgement
    })
    .done(function(data) {
      if (data.status == 200) {
        //toastr.success('Successfully Judged!');
        judged_docs_cnt = parseInt(data.data.count);
        if (judgement === "-1") {
          judgement = "nan";
        }
        updateUI(docid, judgement);
        update_status();
      } else {
        toastr.error('Error Updating Judgement: Code-'+data.status+' Reason-'+data.reason);
      }
    })
    .fail(function(data) {
      toastr.error('Error Updating Judgement, Please Contact the Admin!');
    })
    .always(function(data) {
      //console.log(data);
      $('p#waiting-span').hide();
  });  
}

function add_switch() {
  $("#head").append('<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"><input type="checkbox" name="filter-checkbox"></div>');
  var state = getParameterByName('hide_judged_state');
  if (state === "true") {
    state = true;
  } else {
    state = false;
  }
  $("[name='filter-checkbox']").bootstrapSwitch({'size':'small', 'state':state, 'onText':'Show Not Judged', 'offText':'Show All Docs'});
  $('input[name="filter-checkbox"]').on('switchChange.bootstrapSwitch', function(event, state) {
    if (state) {
      $(".panel-judged").hide();
    } else {
      $(".panel-judged").show();
    }
  });
}

function get_user_judgement() {
  $('p#waiting-span').show();
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  var cur_year = $("#cur_year").text();
  var jqxhr = $.getJSON( "/reproducibleIR/api/judge_compare/get_judgement.php", 
    { 
      uid: cur_uid, 
      apikey:cur_apikey
    })
    .done(function(data) {
      if (data.status == 200) {
        add_switch();
        update_judgements(data.data);
      } else {
        toastr.error('Error Loading Judgement: Code-'+data.status+' Reason-'+data.reason);
      }
    })
    .fail(function(data) {
      toastr.error('Error Loading Judgement, Please Contact the Admin!');
    })
    .always(function(data) {
      //console.log(data);
      $('p#waiting-span').hide();
  });
}

function get_documents() {
  $('p#waiting-span').show();
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  cur_request_page = parseInt($("#cur_page").text());
  var jqxhr = $.getJSON( "/reproducibleIR/api/judge_compare/get_docs.php", 
    { 
      uid: cur_uid, 
      apikey:cur_apikey, 
      page:cur_request_page
    })
    .done(function(data) {
      if (data.status == 200) {
        all_docs = data.data.docs;
        show_docs(all_docs);
        reg_judge_btn_click();
        total_docs_cnt = data.data.total;
        show_pagination();
        get_user_judgement(); // we need gurantee that the dom tree is updated!
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

function async_get_data() {
  get_documents();
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

  // unblock when ajax activity stops 
  $(document).ajaxStop($.unblockUI);

  $.blockUI();
  async_get_data();

  $(document).on( "scroll", function (e) {
    if ($(window).scrollTop() > 100) {
      $('.scroll-top-wrapper').addClass('show');
    } else {
      $('.scroll-top-wrapper').removeClass('show');
    }
  });

  $('.scroll-top-wrapper').on('click', scrollToTop);
});