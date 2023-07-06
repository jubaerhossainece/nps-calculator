//date range
$(function() {

  var start = moment().subtract(364, 'days');
  var end = moment();

  function cb(start, end) {
    console.log(start.format('MMMM D, YYYY')+" "+end.format('MMMM D, YYYY'));
    console.log(start.unix()+" "+end.unix());
    console.log(end.diff(start, 'hours'));
    if(end.diff(start, 'hours') == 24){
      alert('hello');
    }
      $('#user-report-range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      $("#user-report-range").next("input[type='hidden']").val(start.format('YYYY-MM-DD')+ ' : ' + end.format('YYYY-MM-DD'));
      // $("input[name='user_date_range']").val(start.format('YYYY-MM-DD')+ ' : ' + end.format('YYYY-MM-DD'));
  }

  $('#user-report-range').daterangepicker({
      startDate: start,
      endDate: end,
      ranges: {
         'Last 24 hours': [moment(Date.now()).subtract(23, 'h'), moment(Date.now())],
         'Last 7 Days': [moment().subtract(6, 'days'), moment()],
         'Last 30 Days': [moment().subtract(29, 'days'), moment()],
         'Last 365 days': [moment().subtract(364, 'days'), moment()]
      }
  }, cb);

  cb(start, end);

  // nps datepicker
  function cbNps(start, end) {
    
    $('#nps-report-range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    $("#nps-report-range").next("input[type='hidden']").val(start.format('YYYY-MM-DD')+ ' : ' + end.format('YYYY-MM-DD'));
  }

  $('#nps-report-range').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
       'Last 24 hours': [moment(), moment()],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'Last 365 days': [moment().subtract(364, 'days'), moment()]
    }
  }, cbNps);

  cbNps(start, end);


  // date picker for projects
  function cbProject(start, end) {
    $('#project-report-range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    $("#project-report-range").next("input[type='hidden']").val(start.format('YYYY-MM-DD')+ ' : ' + end.format('YYYY-MM-DD'));
  }

  $('#project-report-range').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
       'Last 24 hours': [moment(), moment()],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'Last 365 days': [moment().subtract(364, 'days'), moment()]
    }
  }, cbProject);

  cbProject(start, end);
  
});


//Date Range picker related
// $(document).ready(function () {
//   $('#date-range').daterangepicker({
//     opens: 'left',
//     autoUpdateInput: false,
//     locale: {
//       format: 'YYYY-MM-DD',
//     },
//   })

//   $('#date-range').on('apply.daterangepicker', function (ev, picker) {
//     $(this).val(
//       picker.startDate.format('YYYY-MM-DD') +
//         ' - ' +
//         picker.endDate.format('YYYY-MM-DD'),
//     )
//     getProjectFeedback()
//   })

//   $('#date-range').on('cancel.daterangepicker', function (ev, picker) {
//     $(this).val('')
//   })
// })



// $(document).ready(function () {
//   $('#date-range-ano').daterangepicker({
//     opens: 'left',
//     autoUpdateInput: false,
//     locale: {
//       format: 'YYYY-MM-DD',
//     },
//   });

//   $('#date-range-ano').on('apply.daterangepicker', function (ev, picker) {
//     $(this).val(
//       picker.startDate.format('YYYY-MM-DD') +
//         ' - ' +
//         picker.endDate.format('YYYY-MM-DD')
//     );
//     getNpsData();
//   });

//   $('#date-range-ano').on('cancel.daterangepicker', function (ev, picker) {
//     $(this).val('');
//   });
// });



