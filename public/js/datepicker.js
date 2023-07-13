//date range
$(function() {

  var start = moment().subtract(11, 'months');
  var end = moment();

  function cb(start, end) {
    let target = "#user-report-range";
    var duration = moment.duration(end.diff(start));
    var hours = duration.asHours();
    console.log(hours);
    if(hours <= 24){
      $(target+' span').html(start.format('MMMM D, YYYY HH:mm:ss') + ' - ' + end.format('MMMM D, YYYY HH:mm:ss'));
      $(target).next("input[type='hidden']").val(start.format('YYYY-MM-DD HH:mm:ss')+ ' : ' + end.format('YYYY-MM-DD HH:mm:ss'));
    }else{
      $(target+' span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      $(target).next("input[type='hidden']").val(start.format('YYYY-MM-DD')+ ' : ' + end.format('YYYY-MM-DD'));
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
         'Last 90 days': [moment().subtract(89, 'days'), moment()]
      }
  }, cb);

  cb(start, end);

  // nps datepicker
  function cbNps(start, end) {
    let target = "#nps-report-range";
    var duration = moment.duration(end.diff(start));
    var hours = duration.asHours();
    console.log(hours);
    if(hours <= 24){
      $(target+' span').html(start.format('MMMM D, YYYY HH:mm:ss') + ' - ' + end.format('MMMM D, YYYY HH:mm:ss'));
      $(target).next("input[type='hidden']").val(start.format('YYYY-MM-DD HH:mm:ss')+ ' : ' + end.format('YYYY-MM-DD HH:mm:ss'));
    }else{
      $(target+' span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      $(target).next("input[type='hidden']").val(start.format('YYYY-MM-DD')+ ' : ' + end.format('YYYY-MM-DD'));
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
         'Last 90 days': [moment().subtract(89, 'days'), moment()]
      }
  }, cbNps);

  cbNps(start, end);


  // date picker for projects
  function cbProject(start, end) {
    var duration = moment.duration(end.diff(start));
    var hours = duration.asHours();

    let target = "#project-report-range";
    if(hours <= 24){
      $(target+' span').html(start.format('MMMM D, YYYY HH:mm:ss') + ' - ' + end.format('MMMM D, YYYY HH:mm:ss'));
      $(target).next("input[type='hidden']").val(start.format('YYYY-MM-DD HH:mm:ss')+ ' : ' + end.format('YYYY-MM-DD HH:mm:ss'));
    }else{
      $(target+' span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      $(target).next("input[type='hidden']").val(start.format('YYYY-MM-DD')+ ' : ' + end.format('YYYY-MM-DD'));
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
         'Last 90 days': [moment().subtract(89, 'days'), moment()]
      }
  }, cbProject);

  cbProject(start, end);
});

