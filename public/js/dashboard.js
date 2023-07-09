$(document).ready(function () {
  getUserData();

  getProjectFeedback();
  getProjectData();

});

// generate user chart for a selected date range
$('#user-report-range').on('apply.daterangepicker', function(ev, picker) {
  getUserData();
});


// generate nps chart for a selected date range
$('#nps-report-range').on('apply.daterangepicker', function(ev, picker) {
  getProjectFeedback();
});

// generate nps chart for a selected date range
$('#nps-report-range').on('apply.daterangepicker', function(ev, picker) {
  getProjectFeedback();
});