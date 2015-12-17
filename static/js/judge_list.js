var all_docs;
var all_docs_cnt = 0;
var judged_dict = {};
var judged_docs_cnt = 0;
var show_official_judgement = false;
var total_docs_cnt = 0;
var docs_per_page = 50;
var cur_request_page = 1;

function update_status() {
  $("#count").html("<strong>"+judged_docs_cnt+" / "+total_docs_cnt+" Completed!</strong>");
}

function show_star_rating() {
  $(".stars").rating({'step':1, 'size':'sm','glyphicon':false,'ratingClass':'rating-fa','starCaptions':{1:'Very Poor',2:'Poor',3:'OK',4:'Good',5:'Very Good'}});
}

function show_pagination() {
  var html = '';
  html += '<li';
  if (cur_request_page == 1) {
    html += ' class="disabled" id="left_page"><a aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>'; 
  } else {
    html += ' id="left_page"><a href="judge_list.php?year='+$("#cur_year").text()+'&page='+(cur_request_page-1)+'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>'; 
  }
  for (var i = 0; i != Math.ceil(total_docs_cnt*1.0/docs_per_page); i++) {

    html += '<li'; 
    if (i+1 == cur_request_page) {
      html += ' class="active page"';
    } else {
      html += ' class="page"';
    }
    html += '><a href="judge_list.php?year='+$("#cur_year").text()+'&page='+(i+1)+'">'+(i+1)+'</a></li>';
  }
  html += '<li';
  if (cur_request_page == Math.ceil(total_docs_cnt*1.0/docs_per_page)) {
    html += ' class="disabled" id="right_page"><a aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
  } else {
    html += ' id="right_page"><a href="judge_list.php?year='+$("#cur_year").text()+'&page='+(cur_request_page+1)+'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
  }

  $(".pagination").html(html);    
}

function reg_pagination_click() {
  $(".page").on("click", function() {
    cur_request_page = parseInt($(this).text());
  });
}

function updateUI(docid, sec, rating) {
  if (rating == 0) {
    if (docid in judged_dict && sec in judged_dict[docid]) {
      delete judged_dict[docid][sec];
    }
    /*if (docid in judged_dict && Object.keys(judged_dict[docid]).length == 0) {
      judged_docs_cnt--;
    }*/
    $("#div-"+docid+'-'+sec).html('');
    $("#span-"+docid).html('');
    if ($("#panel-"+docid).hasClass('panel-judged')) {
      $("#panel-"+docid).removeClass('panel-judged'); 
      judged_docs_cnt--;
    }
  } else if (rating>=1 && rating <= 5) {
    if (docid in judged_dict) {
      if (Object.keys(judged_dict[docid]).length == 4) {
        judged_docs_cnt++;
      }
      judged_dict[docid][sec] = rating;
    } else {
      judged_dict[docid] = {};
      judged_dict[docid][sec] = rating;
    }
    $("#div-"+docid+'-'+sec).html('<i class="fa fa-check fa-fw judged"></i>');
    if (Object.keys(judged_dict[docid]).length >= 5) {
      $("#span-"+docid).html('<i class="fa fa-check fa-fw judged"></i>');
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
  }
  update_status();
}

function update_star_rating(all_ratings) {
  if (all_ratings.length) {
    $.each(all_ratings, function(i, obj) {
      var rating = parseInt(obj['rating'])+1;
      var docid = obj['docid'];
      var sec = obj['sec'];
      if (rating >= 1 && rating <= 5) {
        $("#"+docid+'-'+sec).rating('update', rating);
      } else {
        $("#"+docid+'-'+sec).rating('clear');
      }
      updateUI(docid, sec, rating);
    });
  } else {
    update_status();
  }

  $.each(all_docs, function(i, obj) {
    var docid = obj['docid'];
    if (obj['desc_opening'].length == 0) {
      set_rating(docid, 1, 1);
      $("#"+docid+'-1').rating('update', 1);
      $("#row-"+docid+'-1').hide();
    }
    if (obj['desc_web'].length == 0) {
      set_rating(docid, 2, 1);
      $("#"+docid+'-2').rating('update', 1);
      $("#row-"+docid+'-2').hide();
    }
    if (obj['desc_review'].length == 0) {
      set_rating(docid, 3, 1);
      $("#"+docid+'-3').rating('update', 1);
      $("#row-"+docid+'-3').hide();
    }
    if (obj['desc_conclude'].length == 0) {
      set_rating(docid, 4, 1);
      $("#"+docid+'-4').rating('update', 1);
      $("#row-"+docid+'-4').hide();
    }
  });
}

function get_description_html(docid, tag, id, content) {
  s = '<div class="row" id="row-'+id+'">';
  //s += ;
  s += '<div id="div-'+id+'" class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>';
  s += '<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">'+tag+'</div>';
  s += '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">'+content+'</div>';
  s += '<input class="stars" id="'+id+'">';
  s += '</div>';

  return s;
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
    if (show_official_judgement) {
      y += ' <span class="official-judgement-span">'+(parseInt(obj['description_judgement'])+1)+'</span>';
    }
    y += ' \
          </a> \
        </h4> \
      </div> \
      <div id="c-'+obj['docid']+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="'+obj['docid']+'"> \
        <div class="panel-body">';

    
    y += get_description_html(obj['docid'], 'Opening: ', obj['docid']+'-1', obj['desc_opening']);
    y += '<hr/>';
    y += get_description_html(obj['docid'], 'Intro: ', obj['docid']+'-2', obj['desc_web']);
    y += '<hr/>';
    y += get_description_html(obj['docid'], 'Review: ', obj['docid']+'-3', obj['desc_review']);
    y += '<hr/>';
    y += get_description_html(obj['docid'], 'Reason: ', obj['docid']+'-4', obj['desc_conclude']);
    y += '<hr/>';
    y += get_description_html(obj['docid'], 'Whole: ', obj['docid']+'-0', obj['description']);
    

    y += '</div> \
      </div> \
    </div>';

    //console.log(table_row);
    $('#judge-list').append(y);
  });
  $(".official-judgement-span").hide();
}


function set_rating(docid, sec, rating) {
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  var cur_year = $("#cur_year").text();
  var jqxhr = $.post( "/reproducibleIR/api/judge/set_judgement.php", 
    { 
      uid: cur_uid, 
      apikey:cur_apikey,
      docid: docid,
      year:cur_year,
      sec:sec,
      rating:parseInt(rating)-1
    })
    .done(function(data) {
      if (data.status == 200) {
        //toastr.success('Successfully Judged!');
        updateUI(docid, sec, parseInt(rating));
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


function reg_rating_click() {
  $('.star-rating').on('rating.change', function(event, value, caption) {
    var _id = $($(this).find(".stars")[0]).attr('id');
    var docid = _id.split('-')[0];
    var sec = _id.split('-')[1];
    set_rating(docid, sec, value);
  });

  $('.star-rating').on('rating.clear', function(event) {
    var _id = $($(this).find(".stars")[0]).attr('id');
    var docid = _id.split('-')[0];
    var sec = _id.split('-')[1];
    set_rating(docid, sec, 0);
});
}

function add_switch() {
  $("#head").append('<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"><input type="checkbox" name="filter-checkbox"></div>');
  $("[name='filter-checkbox']").bootstrapSwitch({'size':'small', 'onText':'Show Not Judged', 'offText':'Show All Docs'});
  $('input[name="filter-checkbox"]').on('switchChange.bootstrapSwitch', function(event, state) {
    if (state) {
      $(".panel-judged").hide();
    } else {
      $(".panel-judged").show();
    }
  });
}

function add_official_judgement_switch() {
  $("#head").append('<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"><input type="checkbox" name="official-judgement-checkbox"></div>');
  $("[name='official-judgement-checkbox']").bootstrapSwitch({'size':'small', 'onText':'Show Official Judgement', 'offText':'Hide Official Judgement'});
  $('input[name="official-judgement-checkbox"]').on('switchChange.bootstrapSwitch', function(event, state) {
    if (state) {
      $(".official-judgement-span").show();
    } else {
      $(".official-judgement-span").hide();
    }
  });
}

function get_user_judgement() {
  $('p#waiting-span').show();
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  var cur_year = $("#cur_year").text();
  var jqxhr = $.getJSON( "/reproducibleIR/api/judge/get_judgement.php", 
    { 
      uid: cur_uid, 
      apikey:cur_apikey, 
      year:cur_year
    })
    .done(function(data) {
      if (data.status == 200) {
        update_star_rating(data.data);
        add_switch();
        if (show_official_judgement) {
          add_official_judgement_switch();
        }
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

function get_documents() {
  $('p#waiting-span').show();
  var cur_uid = $("#cur_uid").text();
  var cur_apikey = $("#cur_apikey").text();
  var cur_year = $("#cur_year").text();
  cur_request_page = parseInt($("#cur_page").text());
  var jqxhr = $.getJSON( "/reproducibleIR/api/judge/get_docs_of_a_year.php", 
    { 
      uid: cur_uid, 
      apikey:cur_apikey, 
      year:cur_year,
      page:cur_request_page
    })
    .done(function(data) {
      if (data.status == 200) {
        all_docs = data.data.docs;
        show_docs(all_docs);
        show_star_rating();
        reg_rating_click();
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

  //reg_pagination_click();

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