var g_all_query_sets = {};
var g_chart;
var g_report_metrics = ['map', 'P_10', 'P_20'];

function draw_highchart(data) {
  g_chart = new Highcharts.Chart({
    chart: {
            type: 'bar',
            renderTo: 'draw'
        },
    title: {
        text: data['query_tag']
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: g_report_metrics,
        title: {
            text: null
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: '',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    tooltip: {
        valueSuffix: ' '
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: true
            }
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: data['data']
  });
}

function prepare_draw_data(data) {
  var ready_data = {};
  ready_data['query_tag'] = $( "#query_select option:selected" ).text();
  ready_data['data'] = [];
  $.each(data, function(i, obj) {
    var performances_json = JSON.parse(obj.performances);
    var values = [];
    for (var i = 0; i < g_report_metrics.length; i++) {
      values.push(performances_json[g_report_metrics[i]]);
    }
    ready_data['data'].push({
      name: obj.mname,
      tooltip: obj.mnotes,
      data: values
    })
  }); 

  return ready_data;
}

function get_all_performances_of_query() {
  $.blockUI({ message: "getting the performances..." });
  var qtag = $( "#query_select option:selected" ).val();
  $.getJSON('/api/play/get_performances_nouser.php', { query_tag: qtag })
    .done(function(data) {
      if (data['status'] == 200) {
        var draw_data = prepare_draw_data(data['data']);
        draw_highchart(draw_data);
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

function update_query_description() {
  var qtag = $( "#query_select option:selected" ).val();
  $('#query_name').html("<strong>Query Set:</strong> "+g_all_query_sets[qtag]['name']);
  $('#query_des').html("<strong>Query Description:</strong> "+g_all_query_sets[qtag]['qnotes']);
  $('#index_name').html("<strong>Index:</strong> "+g_all_query_sets[qtag]['iname']);
  $('#index_des').html("<strong>Index Description:</strong> "+g_all_query_sets[qtag]['inotes']);
  $('#index_stats').html("<strong>Index Stats:</strong> "+g_all_query_sets[qtag]['stats']);
}

function on_query_select_change() {
  update_query_description();
  get_all_performances_of_query();
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

function update_global_query_sets(data) { 
  $.each(data, function(i, obj) {
    g_all_query_sets[obj.query_tag] = obj;
  });
}

function get_query_list() {
  $.blockUI({ message: "getting the query list..." });
  $.getJSON('/api/play/get_query_list_nouser.php')
    .done(function(data) {
      //console.log(data);
      if (data['status'] == 200) {
        update_query_select(data['data']);
        update_global_query_sets(data['data']);
        on_query_select_change();
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

  $( "#query_select" ).change(function() {
    on_query_select_change();
  });
});