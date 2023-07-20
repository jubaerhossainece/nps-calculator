//date range
$(function() {

  var start = moment().subtract(11, 'months');
  var end = moment();

  function cb(start, end, label) {
    let target = "#user-report-range";
    var duration = moment.duration(end.diff(start));
    var hours = duration.asHours();
    
    if(hours <= 24){
      $(target+' span').html(label);
      $(target).next("input[type='hidden']").val(start.format('YYYY-MM-DD HH:mm:ss')+ ' : ' + end.format('YYYY-MM-DD HH:mm:ss'));
    }else{
      $(target+' span').html(label);
      $(target).next("input[type='hidden']").val(start.format('YYYY-MM-DD')+ ' : ' + end.format('YYYY-MM-DD'));
    }

    // label for custom range
    if(label.toLowerCase() == "custom range"){
      $(target+' span').html(start.format('DD/MM/YYYY')+ ' - ' + end.format('DD/MM/YYYY'));
    }
  }

  $('#user-report-range').daterangepicker({
      startDate: start,
      endDate: end,
      localToday: moment(),
      timePicker: true,
      timePicker24Hour: true,
      locale: {
        format: 'YYYY-MM-DD HH:mm:ss'
      },
      ranges: {
         'Last 24 hours': [moment().subtract(23, 'hours'), moment()],
         'Last 7 Days': [moment().subtract(6, 'days'), moment()],
         'Last 30 Days': [moment().subtract(29, 'days'), moment()],
         'Last 90 days': [moment().subtract(89, 'days'), moment()],
         'Last 1 year': [moment().subtract(11, 'months'), moment()],
      }
  }, cb);

  cb(start, end, "Last 1 year");

  // nps datepicker
  function cbNps(start, end, label) {
    let target = "#nps-report-range";
    var duration = moment.duration(end.diff(start));
    var hours = duration.asHours();
    console.log(hours);
    if(hours <= 24){
      $(target+' span').html(label);
      $(target).next("input[type='hidden']").val(start.format('YYYY-MM-DD HH:mm:ss')+ ' : ' + end.format('YYYY-MM-DD HH:mm:ss'));
    }else{
      $(target+' span').html(label);
      $(target).next("input[type='hidden']").val(start.format('YYYY-MM-DD')+ ' : ' + end.format('YYYY-MM-DD'));
    }

    // label for custom range
    if(label.toLowerCase() == "custom range"){
      $(target+' span').html(start.format('DD/MM/YYYY')+ ' - ' + end.format('DD/MM/YYYY'));
    }
  }

  $('#nps-report-range').daterangepicker({
    startDate: start,
      endDate: end,
      timePicker: true,
      timePicker24Hour: true,
      locale: {
        format: 'YYYY-MM-DD HH:mm:ss'
      },
      ranges: {
         'Last 24 hours': [moment().subtract(23, 'hours'), moment()],
         'Last 7 Days': [moment().subtract(6, 'days'), moment()],
         'Last 30 Days': [moment().subtract(29, 'days'), moment()],
         'Last 90 days': [moment().subtract(89, 'days'), moment()],
         'Last 1 year': [moment().subtract(11, 'months'), moment()],
      }
  }, cbNps);

  cbNps(start, end, "Lat 1 year");


  // date picker for projects
  function cbProject(start, end, label) {
    var duration = moment.duration(end.diff(start));
    var hours = duration.asHours();

    let target = "#project-report-range";
    if(hours <= 24){
      $(target+' span').html(label);
      $(target).next("input[type='hidden']").val(start.format('YYYY-MM-DD HH:mm:ss')+ ' : ' + end.format('YYYY-MM-DD HH:mm:ss'));
    }else{
      $(target+' span').html(label);
      $(target).next("input[type='hidden']").val(start.format('YYYY-MM-DD')+ ' : ' + end.format('YYYY-MM-DD'));
    }

    // label for custom range
    if(label.toLowerCase() == "custom range"){
      $(target+' span').html(start.format('DD/MM/YYYY')+ ' - ' + end.format('DD/MM/YYYY'));
    }
  }

  $('#project-report-range').daterangepicker({
    startDate: start,
      endDate: end,
      timePicker: true,
      timePicker24Hour: true,
      locale: {
        format: 'YYYY-MM-DD HH:mm:ss'
      },
      ranges: {
         'Last 24 hours': [moment().subtract(23, 'hours'), moment()],
         'Last 7 Days': [moment().subtract(6, 'days'), moment()],
         'Last 30 Days': [moment().subtract(29, 'days'), moment()],
         'Last 90 days': [moment().subtract(89, 'days'), moment()],
         'Last 1 year': [moment().subtract(11, 'months'), moment()],
      }
  }, cbProject);

  cbProject(start, end, "Lat 1 year");
});

