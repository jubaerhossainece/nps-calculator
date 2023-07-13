$(document).ready(function () {

  // Toggle the functionality of Select2
  function toggleSelect2(id) {
    var $select2 = $("#"+id);

    if ($select2.prop('disabled')) {
      // Enable Select2
      $select2.on('click').on('keydown').on('select2:opening');
      $select2.prop('disabled', false);
    } else {
      // Disable Select2
      $select2.off('click').off('keydown').off('select2:opening');
      $select2.prop('disabled', true);
    }
  }

  // generate charts
  getUserData();
  getProjectFeedback();
  getProjectData();

  // Call the function to toggle Select2
  toggleSelect2("project-nps-filter");

  // to get all projects of an user
  function getUserProjects(id){
    $.ajax({
      type: 'GET',
      url: "dashboard/user/"+id+"/projects",
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success: function (response) {
        $select2 = $("#project-nps-filter");
        // Enable Select2
        if ($select2.prop('disabled')) {
          $select2.on('click').on('keydown').on('select2:opening');
          $select2.prop('disabled', false);
        }
        $('#project-nps-filter').empty().select2({
          placeholder: 'All projects',
          data: response.data
        });
        $('#project-nps-filter').val(null).trigger('change');
        // generateChart(response, "user-summary-canvas")
      },
    })
  }

  $('#user-nps-filter').on('select2:select', function (e) {
    let id = $(this).val();

    if(id){
      getUserProjects(id);
    }else{
      // Enable Select2
      $select2.on('click').on('keydown').on('select2:opening');
      $select2.prop('disabled', true);
      $('#project-nps-filter').empty().select2({
        placeholder: 'All projects'
      });
    }

    getProjectFeedback();
 });

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
$('#project-report-range').on('apply.daterangepicker', function(ev, picker) {
  getProjectData();
});