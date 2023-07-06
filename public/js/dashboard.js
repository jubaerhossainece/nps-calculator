



$(document).ready(function () {
  getUserData();

  getProjectFeedback();
  getProjectData();

});

$('#user-report-range').on('apply.daterangepicker', function(ev, picker) {
  getUserData();
});